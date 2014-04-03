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

namespace Isotope\Module;

class WishlistEmail extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_iso_wishlistemail';

	/**
	 * Disable caching of the frontend page if this module is in use.
	 * @var bool
	 */
	protected $blnDisableCache = true;


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ISOTOPE ECOMMERCE: WISHLIST MAIL ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->import('Isotope');
		$this->import('IsotopeWishlist');
		$this->IsotopeWishlist->initializeWishlist((int) $this->Isotope->Config->id, (int) $this->Isotope->Config->store_id);

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$arrProducts = $this->IsotopeWishlist->getProducts();

		if (!count($arrProducts))
		{
			$this->Template->empty = true;
			$this->Template->message = $GLOBALS['TL_LANG']['MSC']['noItemsInWishlist'];
			return;
		}

		$this->import('IsotopeFrontend');
		$objForm = $this->IsotopeFrontend->prepareForm($this->iso_wishlist_form, 'iso_wishlist_' . $this->id);

		if($objForm->blnSubmitted && !$objForm->blnHasErrors)
		{
			$arrData = array
			(
				'items'			=> $this->IsotopeWishlist->items,
				'products'		=> $this->IsotopeWishlist->products,
				'subTotal'		=> $this->Isotope->formatPriceWithCurrency($this->IsotopeWishlist->subTotal, false),
				'taxTotal'		=> $this->Isotope->formatPriceWithCurrency($this->IsotopeWishlist->taxTotal, false),
				'shippingPrice'	=> $this->Isotope->formatPriceWithCurrency($this->IsotopeWishlist->Shipping->price, false),
				'paymentPrice'	=> $this->Isotope->formatPriceWithCurrency($this->IsotopeWishlist->Payment->price, false),
				'grandTotal'	=> $this->Isotope->formatPriceWithCurrency($this->IsotopeWishlist->grandTotal, false),
				'cart_text'		=> strip_tags($this->replaceInsertTags($this->IsotopeWishlist->getProducts('iso_products_text'))),
				'cart_html'		=> $this->replaceInsertTags($this->IsotopeWishlist->getProducts('iso_products_html_wishlist')),
			);

			// add custom form data
			// fields
			foreach ($objForm->arrFormData as $key => $value)
			{

				$arrData['form_' . $key] = $value;
			}

			// uploads
			foreach($objForm->arrFiles as $name => $file)
			{
				$arrData['form_' . $name] = $this->Environment->base . str_replace(TL_ROOT . '/', '', dirname($file['tmp_name'])) . '/' . rawurlencode($file['name']);
			}

			// recipients
			$strRecipients = '';
			if (strlen($this->iso_wishlist_definedRecipients))
			{
				$strRecipients .= $this->iso_wishlist_definedRecipients;
			}

			// @todo: maybe we want to make more than one form field available?
			if($this->iso_wishlist_recipientFromFormField)
			{
				$objFieldName = $this->Database->prepare("SELECT name FROM tl_form_field WHERE id=?")->limit(1)->execute($this->iso_wishlist_formField);
				if($objFieldName->numRows)
				{
					$strRecipients = $objForm->arrFields[$objFieldName->name]->value;


				}


			}

			$this->Isotope->sendMail($this->iso_mail_customer, $strRecipients, $this->language, $arrData);

			$_SESSION['ISO_CONFIRM'][] = $GLOBALS['TL_LANG']['MSC']['wishlistSent'];

			// clear the wishlist if activated in module settings
			if ($this->iso_wishlist_clearList)
			{
				$this->IsotopeWishlist->delete();
			}

			$this->jumpToOrReload($this->jumpTo);
		}

		$this->Template->action		= $this->Environment->request;
		$this->Template->fields		= $objForm->arrFields;
		$this->Template->formId		= 'iso_wishlist_' . $this->id;
		$this->Template->hasError	= $objForm->blnHasErrors;
	}
}

