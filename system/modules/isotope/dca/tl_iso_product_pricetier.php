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


/**
 * Table tl_iso_product_pricetier
 */
$GLOBALS['TL_DCA']['tl_iso_product_pricetier'] = array
(

    // Config
    'config' => array
    (
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index',
            )
        ),
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'tstamp' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'min' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'price' => array
        (
            'sql'                 =>  "decimal(12,2) NOT NULL default '0.00'",
        ),
    )
);
