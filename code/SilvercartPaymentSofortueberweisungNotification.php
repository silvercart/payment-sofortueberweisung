<?php
/**
 * Copyright 2012 pixeltricks GmbH
 *
 * This file is part of SilvercartPaypalPayment.
 *
 * SilvercartPaypalPayment is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SilvercartPaypalPayment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SilvercartPaypalPayment.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Silvercart
 * @subpackage Payment
 */

/**
 * processes sofortueberweisung reply
 *
 * @return void
 *
 * @package Silvercart
 * @subpackage Payment
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @copyright 2012 pixeltricks GmbH
 * @since 15.11.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
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
