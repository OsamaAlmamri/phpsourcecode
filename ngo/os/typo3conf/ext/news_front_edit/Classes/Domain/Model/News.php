<?php
namespace TaoJiang\NewsFrontEdit\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 TaoJiang <ribeter267@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * News
 */
class News extends \GeorgRinger\News\Domain\Model\News{
    
    
    /**
	 * Fal media items
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TaoJiang\NewsFrontEdit\Domain\Model\FileReference>
	 * @lazy
	 */
	protected $falMedia;
    
    
    /**
	 * Fal media items with showinpreview set
	 *
	 * @var array
	 * @transient
	 */
	protected $falMediaPreviews;
    

    
    /**
	 * Get the Fal media items
	 *
	 * @return array
	 */
	public function getFalMediaPreviews() {
		if ($this->falMediaPreviews === NULL && $this->getFalMedia()) {
			$this->falMediaPreviews = array();
			/** @var $mediaItem Tx_News_Domain_Model_FileReference */
			foreach ($this->getFalMedia() as $mediaItem) {
				if ($mediaItem->getOriginalResource()->hasProperty('showinpreview') && $mediaItem->getOriginalResource()->getProperty('showinpreview')) {
					$this->falMediaPreviews[] = $mediaItem;
				}
			}
		}
		return $this->falMediaPreviews;
	}

    
}