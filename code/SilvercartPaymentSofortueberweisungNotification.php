<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package Silvercart
 * @subpackage Payment
 */

/**
 * processes sofortueberweisung reply
 *
 * @package Silvercart
 * @subpackage Payment
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class SilvercartPaymentSofortueberweisungNotification extends DataObject {

    /**
     * Contains the name of the module
     *
     * @var string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    protected $moduleName = 'Sofortueberweisung';

    /**
     * This method will be called by the distributing script and receives the
     * payment status message
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function process() {
        // load payment module
        $paymentModule = DataObject::get_one(
            'SilvercartPaymentMethod',
            sprintf(
                "`ClassName` = 'SilvercartPayment%s'",
                $this->moduleName
            )
        );

        if ($paymentModule) {
            require_once("../silvercart_payment_sofortueberweisung/thirdparty/sofortlib/sofortLib.php");

            $paymentModule->Log('SilvercartPaymentSofortueberweisungNotification', '--- Notification received --------------------------------------------------------------------');

            $notification = new SofortLib_Notification();
            $notification->getNotification();

            $transactionId = $notification->getTransactionId();

            $paymentModule->Log('SilvercartPaymentSofortueberweisungNotification', 'Notification time is '.$notification->getTime());
            $paymentModule->Log('SilvercartPaymentSofortueberweisungNotification', 'TransactionId is '.$transactionId);

            // fetch some information for the transaction id retrieved above
            $transactionData = new SofortLib_TransactionData($paymentModule->sofortueberweisungConfigKey);
            $transactionData->setTransaction($transactionId);
            $transactionData->sendRequest();

            $paymentModule->Log('SilvercartPaymentSofortueberweisungNotification', "Amount: ".$transactionData->getAmount());
            $paymentModule->Log('SilvercartPaymentSofortueberweisungNotification', "Status: ".$transactionData->getStatus());

            $paymentStatus = new SilvercartPaymentSofortueberweisungPaymentStatus();
            $paymentStatus->createEvent(
                $transactionId,
                $transactionData->getStatus(),
                $transactionData->getAmount(),
                true
            );
        }
    }
}
