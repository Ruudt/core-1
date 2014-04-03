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

namespace Isotope\Model\Attribute;

use Isotope\Interfaces\IsotopeAttribute;
use Isotope\Interfaces\IsotopeProduct;
use Isotope\Model\Attribute;


/**
 * Attribute to implement TextArea widget
 *
 * @copyright  Isotope eCommerce Workgroup 2009-2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 */
class TextArea extends Attribute implements IsotopeAttribute
{

    public function saveToDCA(array &$arrData)
    {
        parent::saveToDCA($arrData);

        $arrData['fields'][$this->field_name]['sql'] = "text NULL";

        // Textarea cannot be w50
        if ($this->rte != '') {
            $arrData['fields'][$this->field_name]['eval']['tl_class'] = 'clr';
        }
    }

    public function generate(IsotopeProduct $objProduct, array $arrOptions = array())
    {
        if ($this->rte == '') {
            return nl2br($objProduct->{$this->field_name});
        } else {
            return parent::generate($objProduct);
        }
    }
}
