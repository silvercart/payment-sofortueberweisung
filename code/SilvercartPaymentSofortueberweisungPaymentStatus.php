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
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 */

/**
 * This is a queue system for status reports from Sofortueberweisung.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 16.11.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2012 pixeltricks GmbH
 */
class SilvercartPaymentSofortueberweisungPaymentStatus extends DataObject {

    /**
     * Cache for the order object for this status.
     *
     * @var mixed boolean false|SilvercartOrder
     * @since 19.11.2012
     */
    protected $orderObject = false;

    /**
     * Attributes.
     *
     * @var array
     */
    public static $db = array(
        'transactionId' => 'VarChar(100)',
        'status'        => "Enum('created,pending,received,error,loss','pending')",
        'amount'        => 'Money',
        'queued'        => 'Boolean(0)'
    );

    /**
     * Set a payment status for the given transaction ID. Optionally an amount can
     * be given.
     *
     * @param string $transactionId The transaction ID
     * @param string $status        The status of the transaction
     * @param float  $amount        Optional the amount
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 19.11.2012
     */
    public function createEvent($transactionId, $status, $amount = 0.0) {
        // -------------------------------------------------------------------
        // Get currency
        // -------------------------------------------------------------------
        $order = DataObject::get_one(
            'SilvercartOrder',
            sprintf(
                "sofortueberweisungTransactionID = '%s'",
                $transactionId
            )
        );

        if ($order) {
            $currency = $order->AmountTotal()->getCurrency();
        } else {
            $cart = DataObject::get_one(
                'SilvercartShoppingCart',
                sprintf(
                    "sofortueberweisungTransactionID = '%s'",
                    $transactionId
                )
            );

            if ($cart) {
                $currency = $cart->getAmountTotal()->getCurrency();
            } else {
                $member = Member::currentUser();

                if ($member &&
                    $member->SilvercartShoppingCartID > 0) {

                    $currency = $member->SilvercartShoppingCart()->getAmountTotal()->getCurrency();
                }
            }
        }

        if (!$currency) {
            $currency = SilvercartConfig::DefaultCurrency();
        }

        // -------------------------------------------------------------------
        // Write event
        // -------------------------------------------------------------------
        $this->transactionId  = $transactionId;
        $this->status         = $status;
        $this->amountAmount   = $amount;
        $this->amountCurrency = $currency;
        $this->write();
    }

    /**
     * Returns wether the given transactionId has an order object.
     *
     * @param string $transactionId The transaction ID to check for
     *
     * @return boolean
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 19.11.2012
     */
    public function hasOrderObject($transactionId) {
        $hasOrder = false;
        $order    = $this->getOrderObject($transactionId);

        if ($order) {
            $hasOrder = true;
        }

        return $hasOrder;
    }

    /**
     * Returns an order object for the given transactionId.
     *
     * @param string $transactionId The transaction ID
     *
     * @return SilvercartOrder
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 19.11.2012
     */
    public function getOrderObject($transactionId) {
        if ($this->orderObject === false) {
            $order = DataObject::get_one(
                'SilvercartOrder',
                sprintf(
                    "sofortueberweisungTransactionID = '%s'",
                    $transactionId
                )
            );

            if ($order) {
                $this->orderObject = $order;
            }
        }

        return $this->orderObject;
    }
}