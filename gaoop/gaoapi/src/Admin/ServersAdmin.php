<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Servers;
use App\Library\Admin\MyAdmin;
use App\Library\Helper\GeneralHelper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

final class ServersAdmin extends MyAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('url')
            ->add('description');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('url')
            ->add('description');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('url')
            ->add('description');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('url')
            ->add('description');
    }

    public function prePersist($object)
    {
        parent::prePersist($object); // TODO: Change the autogenerated stub

        $object->setInfoId(GeneralHelper::getInstance()->info_id);
        $object->setCreatedAt(new \DateTime());
    }

    public function preBatchAction($actionName, ProxyQueryInterface $query, array &$idx, $allElements)
    {
        switch ($actionName) {
            case 'delete':
                $this->verifyInfo(Servers::class, $idx, $allElements);
                break;
        }

        parent::preBatchAction($actionName, $query, $idx, $allElements); // TODO: Change the autogenerated stub
    }

    public function postPersist($object)
    {
        parent::postPersist($object); // TODO: Change the autogenerated stub

        // 是否自动更新文档
        GeneralHelper::getOneInstance()->buildCurrentInfoApiConfig();
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object); // TODO: Change the autogenerated stub

        // 是否自动更新文档
        GeneralHelper::getOneInstance()->buildCurrentInfoApiConfig();
    }

    public function postRemove($object)
    {
        parent::postRemove($object); // TODO: Change the autogenerated stub

        // 是否自动更新文档
        GeneralHelper::getOneInstance()->buildCurrentInfoApiConfig();
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context); // TODO: Change the autogenerated stub

        $query->andWhere(
            $query->expr()->eq($query->getRootAliases()[0] . '.infoId', ':infoId')
        );
        $query->setParameter('infoId', GeneralHelper::getInstance()->info_id);

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection); // TODO: Change the autogenerated stub
        $collection->remove('export');
        $collection->remove('show');
    }
}
