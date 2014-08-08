<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 */

/**
 * This is a queue system for status reports from Sofortueberweisung.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
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
     * @param string  $transactionId The transaction ID
     * @param string  $status        The status of the transaction
     * @param float   $amount        Optional the amount
     * @param boolean $createOrder   Set to true to create an order object
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 19.11.2012
     */
    public function createEvent($transactionId, $status, $amount = 0.0, $createOrder = true) {
        // -------------------------------------------------------------------
        // Get currency
        // -------------------------------------------------------------------
        $currency = false;
        $order    = DataObject::get_one(
            'SilvercartOrder',
            sprintf(
                "sofortueberweisungTransactionID = '%s'",
                $transactionId
            )
        );

        if ($order) {
            $currency = $order->AmountTotalCurrency;
        } else {
            $cart = DataObject::get_one(
                'SilvercartShoppingCart',
                sprintf(
                    "sofortueberweisungTransactionID = '%s'",
                    $transactionId
                )
            );

            if ($cart) {
                $currency = $cart->AmountTotalCurrency;
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

        // -------------------------------------------------------------------
        // Set order status if order is already available or queue it
        // -------------------------------------------------------------------
        $order = $this->getOrderObject($transactionId);

        if ($order) {
            $orderStatus = SilvercartPaymentSofortueberweisung::getOrderStatusFor($this->status);

            if ($orderStatus) {
                $order->setOrderStatus(
                    $orderStatus
                );
            }
        } else {
            $this->queued = true;
        }

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
        return DataObject::get_one(
            'SilvercartOrder',
            sprintf(
                "sofortueberweisungTransactionID = '%s'",
                $transactionId
            )
        );
    }
}