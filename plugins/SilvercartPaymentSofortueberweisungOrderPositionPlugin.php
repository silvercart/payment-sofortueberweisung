<?php
/**
 * Copyright 2012 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * SilverCart is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SilverCart is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SilverCart.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package SilvercartPaymentSaferpay
 * @subpackage Plugins
 */

/**
 * Order plugin.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Plugins
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 20.11.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2012 pixeltricks GmbH
 */
class SilvercartPaymentSofortueberweisungOrderPositionPlugin extends DataObjectDecorator {

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

        $lastTransactions = DataObject::get(
            'SilvercartPaymentSofortueberweisungPaymentStatus',
            sprintf(
                "transactionId = '%s' AND
                 queued = 1",
                $order->getSofortueberweisungTransactionID()
            ),
            true,
            'CREATED ASC'
        );

        if ($lastTransactions) {
            foreach ($lastTransactions as $transaction) {
                $order->setOrderStatus(SilvercartPaymentSofortueberweisung::getOrderStatusFor($transaction->status));
            }
        }
    }
}