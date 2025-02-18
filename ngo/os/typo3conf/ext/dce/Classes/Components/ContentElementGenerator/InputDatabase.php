<?php
namespace T3\Dce\Components\ContentElementGenerator;

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2012-2019 Armin Vieweg <armin@v.ieweg.de>
 *  |     2019 Stefan Froemken <froemken@gmail.com>
 */
use T3\Dce\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class InputDatabase
 */
class InputDatabase implements InputInterface
{
    /**
     * Returns all available DCE as array with this format
     * (just most important fields listed):
     *
     * DCE
     *    |_ uid
     *    |_ title
     *    |_ tabs <array>
     *    |    |_ title
     *    |    |_ fields <array>
     *    |        |_ uid
     *    |        |_ title
     *    |        |_ variable
     *    |        |_ configuration
     *    |_ ...
     *
     * @return array with DCE -> containing tabs -> containing fields
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getDces() : array
    {
        $tables = DatabaseUtility::adminGetTables();
        if (!\array_key_exists('tx_dce_domain_model_dce', $tables) ||
            !\array_key_exists('tx_dce_domain_model_dcefield', $tables)
        ) {
            return [];
        }

        $queryBuilder = DatabaseUtility::getConnectionPool()->getQueryBuilderForTable('tx_dce_domain_model_dce');
        $dceModelRows = $queryBuilder
            ->select('*')
            ->from('tx_dce_domain_model_dce')
            ->where('pid=0 AND deleted=0 AND hidden=0')
            ->orderBy('sorting', 'asc')
            ->execute()
            ->fetchAll();

        $queryBuilder = DatabaseUtility::getConnectionPool()->getQueryBuilderForTable('tx_dce_domain_model_dcefield');
        $dceFieldRows = $queryBuilder
            ->select('df.*')
            ->from('tx_dce_domain_model_dcefield', 'df')
            ->leftJoin(
                'df',
                'tx_dce_domain_model_dce',
                'd',
                'df.parent_dce = d.uid'
            )
            ->where('df.pid=0 AND df.deleted=0 and df.hidden=0 AND d.hidden=0 and d.deleted=0')
            ->orderBy('d.sorting', 'ASC')
            ->addOrderBy('df.sorting', 'ASC')
            ->execute()
            ->fetchAll();

        $dceFieldRowsByParentDce = $this->getFieldRowsByParentFieldName($dceFieldRows);

        $queryBuilder = DatabaseUtility::getConnectionPool()->getQueryBuilderForTable('tx_dce_domain_model_dcefield');
        $dceFieldRowsSortedByParentFields = $queryBuilder
            ->select('df.*')
            ->from('tx_dce_domain_model_dcefield', 'df')
            ->where('parent_field > 0')
            ->orderBy('df.parent_field', 'ASC')
            ->addOrderBy('df.sorting', 'ASC')
            ->execute()
            ->fetchAll();

        $dceFieldRowsByParentDceField = $this->getFieldRowsByParentFieldName(
            $dceFieldRowsSortedByParentFields,
            'parent_field'
        );

        $dces = $this->buildDcesArray($dceModelRows, $dceFieldRowsByParentDce, $dceFieldRowsByParentDceField);

        if (ExtensionManagementUtility::isLoaded('gridelements')) {
            $dces = $this->ensureGridelementsFieldCompatibility($dces);
        }
        return $dces;
    }

    /**
     * @param array|null $dceFieldRows
     * @param string $parentFieldName
     * @return array
     */
    protected function getFieldRowsByParentFieldName(
        ?array $dceFieldRows,
        string $parentFieldName = 'parent_dce'
    ) : array {
        $rowsByParent = [];
        foreach ($dceFieldRows ?? [] as $dceFieldRow) {
            if (!isset($rowsByParent[$dceFieldRow[$parentFieldName]])) {
                $rowsByParent[$dceFieldRow[$parentFieldName]] = [];
            }
            $rowsByParent[$dceFieldRow[$parentFieldName]][] = $dceFieldRow;
            unset($dceFieldRow);
        }
        return $rowsByParent;
    }

    /**
     * @param array|null $dceModelRows
     * @param array $dceFieldRowsByParentDce
     * @param array $dceFieldRowsByParentDceField
     * @return array
     */
    protected function buildDcesArray(
        ?array $dceModelRows,
        array $dceFieldRowsByParentDce,
        array $dceFieldRowsByParentDceField
    ) : array {
        $dces = [];
        foreach ($dceModelRows as $row) {
            $tabs = [
                0 => [
                    'title' => 'LLL:EXT:dce/Resources/Private/Language/locallang.xml:generaltab',
                    'variable' => 'tabGeneral',
                    'fields' => []
                ]
            ];
            $index = 0;
            if (empty($dceFieldRowsByParentDce[$row['uid']])) {
                // Skip creation of content elements, for DCEs without fields
                continue;
            }
            foreach ((array)$dceFieldRowsByParentDce[$row['uid']] as $row2) {
                if ($row2['type'] === '1') {
                    // Create new Tab
                    $index++;
                    $tabs[$index] = [];
                    $tabs[$index]['title'] = $row2['title'];
                    $tabs[$index]['variable'] = $row2['variable'];
                    $tabs[$index]['fields'] = [];
                    continue;
                }

                if ($row2['type'] === '2') {
                    $sectionFields = [];
                    foreach ((array)$dceFieldRowsByParentDceField[$row2['uid']] as $row3) {
                        if ($row3['type'] === '0') {
                            // add fields of section to fields
                            $sectionFields[] = $row3;
                        }
                    }
                    $row2['section_fields'] = $sectionFields;
                    $tabs[$index]['fields'][] = $row2;
                } else {
                    // usual element
                    $row2['configuration'] = str_replace('{$variable}', $row2['variable'], $row2['configuration']);
                    $tabs[$index]['fields'][] = $row2;
                }
            }
            if (\count($tabs[0]['fields']) === 0) {
                unset($tabs[0]);
            }

            $row['identifier'] = !empty($row['identifier']) ? 'dce_' . $row['identifier'] : 'dce_dceuid' . $row['uid'];
            $row['tabs'] = $tabs;
            $row['hasCustomWizardIcon'] = $row['wizard_icon'] === 'custom';
            $dces[] = $row;
        }
        return $dces;
    }

    /**
     * Iterates through given DCE rows and add field "colPos" to DCE palettes
     * if not already set.
     *
     * @param array $dces
     * @return array
     */
    protected function ensureGridelementsFieldCompatibility(array $dces) : array
    {
        foreach ($dces as $key => $dceRow) {
            $paletteFields = GeneralUtility::trimExplode(',', $dceRow['palette_fields'], true);
            if (!\in_array('colPos', $paletteFields, true)) {
                $paletteFields[] = 'colPos';
            }
            $dces[$key]['palette_fields'] = implode(', ', $paletteFields);
        }
        return $dces;
    }
}
