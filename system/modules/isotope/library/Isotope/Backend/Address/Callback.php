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

namespace Isotope\Backend\Address;

use Isotope\Model\Address;

class Callback extends \Backend
{

    /**
     * Generate and return the address label
     * @param array
     * @return string
     */
    public function renderLabel($arrAddress)
    {
        $objAddress = new \Isotope\Model\Address();
        $objAddress->setRow($arrAddress);
        $strBuffer = $objAddress->generateHtml();

        $strBuffer .= '<div style="color:#b3b3b3;margin-top:8px">' . $GLOBALS['TL_LANG']['tl_iso_address']['store_id'][0] . ' ' . $arrAddress['store_id'];

        if ($arrAddress['isDefaultBilling']) {
            $strBuffer .= ', ' . $GLOBALS['TL_LANG']['tl_iso_address']['isDefaultBilling'][0];
        }

        if ($arrAddress['isDefaultShipping']) {
            $strBuffer .= ', ' . $GLOBALS['TL_LANG']['tl_iso_address']['isDefaultShipping'][0];
        }

        $strBuffer .= '</div>';

        return $strBuffer;
    }


    /**
     * Reset all default checkboxes when setting a new address as default
     * @param mixed
     * @param object
     * @return mixed
     * @link http://www.contao.org/callback.html#save_callback
     */
    public function updateDefault($varValue, $dc)
    {
        if ($varValue == '1' && $dc->activeRecord->{$dc->field} != $varValue) {
            \Database::getInstance()->prepare("
                UPDATE " . Address::getTable() . " SET {$dc->field}='' WHERE pid=? AND ptable=? AND store_id=?
            ")->execute($dc->activeRecord->pid, $dc->activeRecord->ptable, $dc->activeRecord->store_id);
        }

        return $varValue;
    }
}
