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
class SilvercartPaymentSofortueberweisungOrderPositionPlugin extends DataExtension {

    /**
     * Sets queued order status informations for a SilvercartOrder object.
     *
     * @param array           $arguments        Arguments
     * @param SilvercartOrder &$silvercartOrder The SilverCart order object
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 20.11.2012
     */
    public function pluginConvertShoppingCartPositionsToOrderPositions($arguments, &$silvercartOrder) {
        $order = $arguments[0];

        $lastTransactions = SilvercartPaymentSofortueberweisungPaymentStatus::get()
                ->filter(array(
                    'transactionId' => $order->getSofortueberweisungTransactionID(),
                    'queued' => 1,
                ))
                ->sort('CREATED ASC');

        if ($lastTransactions) {
            foreach ($lastTransactions as $transaction) {
                $order->setOrderStatus(SilvercartPaymentSofortueberweisung::getOrderStatusFor($transaction->status));
            }
        }
    }
}