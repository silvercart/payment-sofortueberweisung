<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package SilvercartPaymentSaferpay
 * @subpackage Plugins
 */

/**
 * Order plugin.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Plugins
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class SilvercartPaymentSofortueberweisungOrderPlugin extends DataExtension {

    /**
     * Injects the Sofortueberweisung information from the shopping cart into the order
     * object.
     *
     * @param array           $arguments        Arguments
     * @param SilvercartOrder &$silvercartOrder The SilverCart order object
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function pluginCreateFromShoppingCart($arguments, &$silvercartOrder) {
        $order                  = $arguments[0];
        $silvercartShoppingCart = $arguments[1];

        $order->sofortueberweisungTransactionID = $silvercartShoppingCart->getSofortueberweisungTransactionID();
        $order->sofortueberweisungReason        = $silvercartShoppingCart->getSofortueberweisungReason();
        $order->write();
    }
}