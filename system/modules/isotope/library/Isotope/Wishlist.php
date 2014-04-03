<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2014 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://isotopeecommerce.org
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace Isotope;

class Wishlist extends Frontend
{

	/**
	 * Wishlist object
	 * @var object
	 */
	public $Wishlist;


	/**
	 * Initialize the wishlist object
	 */
	public function __construct()
	{
		parent::__construct();

		if (TL_MODE == 'FE')
		{
			$this->import('Isotope');
			$this->import('IsotopeWishlist');
			$this->IsotopeWishlist->initializeWishlist((int) $this->Isotope->Config->id, (int) $this->Isotope->Config->store_id);
		}
	}


	/**
	 * Generate a wishlist button
	 * @param array
	 */
	public function generateButton($arrButtons)
	{
		$arrButtons['add_to_wishlist'] = array
		(
			'label' => $GLOBALS['TL_LANG']['MSC']['buttonLabel']['add_to_wishlist'],
			'callback' => array('Wishlist', 'addToWishlist')
		);

		return $arrButtons;
	}


	/**
	 * Adds a particular product to wishlist
	 * @param object
	 * @param mixed
	 */
	public function addToWishlist($objProduct, $objModule=null)
	{
		$intQuantity = ($objModule->iso_use_quantity && intval($this->Input->post('quantity_requested')) > 0) ? intval($this->Input->post('quantity_requested')) : 1;

		if ($this->IsotopeWishlist->addProduct($objProduct, $intQuantity) !== false)
		{
			if($objModule->iso_wishlist_jumpTo)
			{
				// Get current "jumpTo" page
				$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($objModule->iso_wishlist_jumpTo);

				$strUrl = '<a href="'.ampersand($this->generateFrontendUrl($objPage->row())).'" title="'.$GLOBALS['TL_LANG']['MSC']['viewWishlist'].'">'.$GLOBALS['TL_LANG']['MSC']['viewWishlist'].'</a>';
			}

			$_SESSION['ISO_CONFIRM'][] = $GLOBALS['TL_LANG']['MSC']['addedToWishlist'] . ' ' . $strUrl;
			$this->jumpToOrReload($objModule->iso_addProductJumpTo);
		}
	}
}

