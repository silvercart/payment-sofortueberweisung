<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package SilverCart
 * @subpackage saferpay_payment
 */

/**
 * carries multilingual attributes for SilvercartPaymentSofortueberweisung
 *
 * @package SilverCart
 * @subpackage saferpay_payment
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class SilvercartPaymentSofortueberweisungLanguage extends SilvercartPaymentMethodLanguage {
    
    /**
     * Attributes.
     *
     * @var array
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public static $db = array(
        'sofortueberweisungInfotextCheckout' => 'VarChar(255)'
    );
    
    /**
     * 1:1 or 1:n relationships.
     *
     * @var array
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public static $has_one = array(
        'SilvercartPaymentSofortueberweisung' => 'SilvercartPaymentSofortueberweisung'
    );
    
    /**
     * Returns the translated singular name of the object. If no translation exists
     * the class name will be returned.
     * 
     * @return string The objects singular name 
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function singular_name() {
        if (_t('SilvercartPaymentSofortueberweisungLanguage.SINGULARNAME')) {
            return _t('SilvercartPaymentSofortueberweisungLanguage.SINGULARNAME');
        } else {
            return parent::singular_name();
        } 
    }


    /**
     * Returns the translated plural name of the object. If no translation exists
     * the class name will be returned.
     * 
     * @return string the objects plural name
     * 
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function plural_name() {
        if (_t('SilvercartPaymentSofortueberweisungLanguage.PLURALNAME')) {
            return _t('SilvercartPaymentSofortueberweisungLanguage.PLURALNAME');
        } else {
            return parent::plural_name();
        }

    }
    
    /**
     * Field labels for display in tables.
     *
     * @param boolean $includerelations A boolean value to indicate if the labels returned include relation fields
     *
     * @return array
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function fieldLabels($includerelations = true) {
        $fieldLabels = array_merge(
            parent::fieldLabels($includerelations),             array(
                'sofortueberweisungInfotextCheckout' => _t('SilvercartPaymentSofortueberweisung.INFOTEXT_CHECKOUT')
            )
        );

        $this->extend('updateFieldLabels', $fieldLabels);
        return $fieldLabels;
    }
}

