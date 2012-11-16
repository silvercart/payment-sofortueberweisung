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
 * Enables payment via Sofortueberweisung.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 15.11.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2012 pixeltricks GmbH
 */
class SilvercartPaymentSofortueberweisung extends SilvercartPaymentMethod {

    /**
     * contains module name for display in the admin backend
     *
     * @var string
     */
    protected $moduleName = 'Sofortueberweisung';

    /**
     * contains description of the shopping cart content for display at the
     * saferpay site.
     *
     * @var string
     */
    protected $description = null;

    /**
     * Indicates whether a payment module has multiple payment channels or not.
     *
     * @var bool
     */
    public static $has_multiple_payment_channels = false;

    /**
     * A list of possible payment channels.
     *
     * @var array
     */
    public static $possible_payment_channels = array();

    /**
     * contains all strings of the saferpay answer which declare the
     * transaction status false
     *
     * @var array
     */
    public $failedStatus = array(
        'failed',
    );
    /**
     * contains all strings of the saferpay answer which declare the
     * transaction status true
     *
     * @var array
     */
    public $successStatus = array(
        'successOrderStatus',
    );

    /**
     * Attributes.
     *
     * @var array
     */
    public static $db = array(
        'suCanceledOrderStatus'       => 'Int',
        'suPaidOrderStatus'           => 'Int',
        'suSuccessOrderStatus'        => 'Int',

        'sofortueberweisungConfigKey' => 'VarChar(100)',
    );


    /**
     * 1:n relationships.
     *
     * @var array
     */
    public static $has_many = array(
        'SilvercartPaymentSofortueberweisungLanguages' => 'SilvercartPaymentSofortueberweisungLanguage'
    );

    /**
     * Default records.
     *
     * @var array
     */
    public static $defaults = array(
    );

    /**
     * Creates a unique Sofortueberweisung token.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function createSofortueberweisungToken() {
        $member       = Member::currentUser();
        $shoppingCart = $this->getShoppingCart();
        $token        = crypt(
            $member->FirstName.'-'.
            $member->Surame.'-'.
            $member->email.'-'.
            $shoppingCart->getAmountTotal()->getAmount().'-'.
            count($shoppingCart->SilvercartShoppingCartPositions()).'-'.
            time()
        );

        return $token;
    }

    /**
     * Returns the Sofortueberweisung account ID
     *
     * @return string The account ID
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getAccountId() {
        if ($this->mode == 'live') {
            return $this->sofortueberweisungAccountId_Live;
        } else {
            return $this->sofortueberweisungAccountId_Dev;
        }
    }

    /**
     * Returns the description of the order.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getDescription() {
        if ($this->description == null) {
            $templateVariables = new ArrayData(array(
                'SilvercartShoppingCart' => $this->getShoppingCart()
            ));
            $template          = new SSViewer('sofortueberweisungDescription');
            $this->description = HTTP::absoluteURLs($template->process($templateVariables));
        }

        return $this->description;
    }

    /**
     * returns CMS fields
     *
     * @param mixed $params optional
     *
     * @return FieldSet
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getCMSFields($params = null) {
        $fields     = parent::getCMSFieldsForModules($params);
        $tabApi     = new Tab('SofortueberweisungAPI');
        $tabUrls    = new Tab('SofortueberweisungURLs');

        $fields->fieldByName('Sections')->push($tabApi);
        $fields->fieldByName('Sections')->push($tabUrls);

        // API Tabset ---------------------------------------------------------
        $tabApiTabset   = new TabSet('APIOptions');
        $tabApiTab      = new Tab(_t('SilvercartPaymentSofortueberweisung.API', 'API data'));

        // API Tabs -----------------------------------------------------------
        $tabApiTabset->push($tabApiTab);

        $tabApi->push($tabApiTabset);

        // API Tab Dev fields -------------------------------------------------
        $tabApiTab->setChildren(
            new FieldSet(
                new TextField('sofortueberweisungConfigKey', _t('SilvercartPaymentSofortueberweisung.CONFIG_KEY'))
            )
        );

        // URL fields ------------------------------------------------

        return $fields;
    }

    // ------------------------------------------------------------------------
    // processing methods
    // ------------------------------------------------------------------------

    /**
     * hook to be called before order creation
     *
     * saves the Sofortueberweisung token to the session; after that redirects to Sofortueberweisung checkout
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function processPaymentBeforeOrder() {
        require_once("../silvercart_payment_sofortueberweisung/thirdparty/sofortlib/sofortLib.php");

        $shoppingCart = $this->getShoppingCart();
        $reason       = $this->getReason($this->createSofortueberweisungToken());

        $shoppingCart->saveSofortueberweisungReason($reason);

        $Sofort = new SofortLib_Multipay($this->sofortueberweisungConfigKey);
        $Sofort->setSofortueberweisung();
        $Sofort->setAmount($shoppingCart->getAmountTotal());
        $Sofort->setReason($reason);
        $Sofort->setSuccessUrl($this->getReturnLink());
        $Sofort->setAbortUrl($this->getCancelLink());
        $Sofort->setTimeoutUrl($this->getCancelLink());
        $Sofort->setNotificationUrl($this->getNotificationUrl());

        /*
        // Cart positions
        foreach ($shoppingCart->SilvercartShoppingCartPositions() as $position) {
            $Sofort->addSofortrechnungItem(
                $position->SilvercartProduct()->ID,
                $position->SilvercartProduct()->ProductNumberShop,
                $position->getTitle(),
                $position->getPrice(true)->getAmount(),
                0, // type
                $position->getCartDescription(),
                $position->Quantity,
                $position->SilvercartProduct()->getTaxRate()
            );
        }

        // add payment and shipping costs
        $taxes = $shoppingCart->getTaxRatesWithoutFeesAndCharges();
        $mostValuableTaxrate = $shoppingCart->getMostValuableTaxRate($taxes);

        $Sofort->addSofortrechnungItem(
            99999,
            0,
            "test 1 ".$shoppingCart->CarrierAndShippingMethodTitle(),
            round((float) $shoppingCart->HandlingCostShipment()->getAmount(), 2),
            1, // type
            '',
            0,
            $mostValuableTaxrate->getTaxRate()
        );

        $Sofort->addSofortrechnungItem(
            99999,
            0,
            "test 2 "._t('SilvercartPaymentMethod.SINGULARNAME'),
            round((float) $shoppingCart->HandlingCostPayment()->getAmount(), 2),
            1, // type
            '',
            0,
            $mostValuableTaxrate->getTaxRate()
        );

        // add address data
        $invoiceAddress  = $this->getInvoiceAddress();
        $shippingAddress = $this->getShippingAddress();
        $Sofort->setSofortrechnungInvoiceAddress(
            $invoiceAddress->FirstName,
            $invoiceAddress->Surname,
            $invoiceAddress->Street,
            $invoiceAddress->StreetNumber,
            $invoiceAddress->Postcode,
            $invoiceAddress->City,
            $invoiceAddress->Salutation,
            $invoiceAddress->SilvercartCountry()->ISO2
        );
        $Sofort->setSofortrechnungShippingAddress(
            $shippingAddress->FirstName,
            $shippingAddress->Surname,
            $shippingAddress->Street,
            $shippingAddress->StreetNumber,
            $shippingAddress->Postcode,
            $shippingAddress->City,
            $shippingAddress->Salutation,
            $shippingAddress->SilvercartCountry()->ISO2
        );
        */

        $Sofort->sendRequest();

        if($Sofort->isError()) {
            //PNAG-API didn't accept the data
            $this->addError($Sofort->getError());
        } else {
            $shoppingCart->saveSofortueberweisungTransactionID($Sofort->getTransactionID());

            $this->controller->addCompletedStep($this->controller->getCurrentStep());
            $this->controller->setCurrentStep($this->controller->getNextStep());

            //buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
            $paymentUrl = $Sofort->getPaymentUrl();
            header('Location: '.$paymentUrl);
            exit();
        }
    }

    /**
     * hook to be called after jumpback from payment provider; called before
     * order creation
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function processReturnJumpFromPaymentProvider() {
        $this->controller->NextStep();
    }

    /**
     * hook to be called after order creation
     *
     * @param array $orderObj object to be processed
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function processPaymentAfterOrder($orderObj = array()) {
        return parent::processPaymentAfterOrder($orderObj);
    }

    /**
     * possibility to return a text at the end of the order process
     * processed after order creation
     *
     * @param Order $orderObj the order object
     * 
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 30.09.2011
     */
    public function processPaymentConfirmationText($orderObj) {
    }

    // ------------------------------------------------------------------------
    // payment module specific methods
    // ------------------------------------------------------------------------

    /**
     * Generates a "Verwendungszweck" identifier.
     *
     * @param int $key An identifier like shoppingcart or order ID
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 16.11.2012
     */
    public function getReason($key) {
        $key = ($key^0x47cb8a8c) ^ ($key<<12);
        $key = ($key^0x61a988bc) ^ ($key>>19);
        $key = ($key^0x78d2a3c8) ^ ($key<<5);
        $key = ($key^0x5972b1be) ^ ($key<<9);
        $key = ($key^0x2ea72dfe) ^ ($key<<3);
        $key = ($key^0x5ff1057d) ^ ($key>>16);

        return $key;
    }

    /**
     * Set the title for the submit button on the order confirmation step.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getOrderConfirmationSubmitButtonTitle() {
        return _t('SilvercartPaymentSofortueberweisung.ORDER_CONFIRMATION_SUBMIT_BUTTON_TITLE');
    }

    /**
     * getter for the multilingual attribute sofortueberweisungInfotextCheckout
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getSofortueberweisungInfotextCheckout() {
        $text = '';
        if ($this->getLanguage()) {
            $text = $this->getLanguage()->saferpayInfotextCheckout;
        }
        return $text;
    }

    /**
     * Creates and relates required order status and logo images.
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@standardized.de>
     * @since 15.11.2012
     */
    public function requireDefaultRecords() {
        parent::requireDefaultRecords();

        $requiredStatus = array(
            'payed'                       => _t('SilvercartOrderStatus.PAYED'),
            'sofortueberweisung_success'  => _t('SilvercartOrderStatus.SOFORTUEBERWEISUNG_SUCCESS'),
            'sofortueberweisung_error'    => _t('SilvercartOrderStatus.SOFORTUEBERWEISUNG_ERROR'),
            'sofortueberweisung_canceled' => _t('SilvercartOrderStatus.SOFORTUEBERWEISUNG_CANCELED')
        );
        $paymentLogos = array(
            'Sofortueberweisung'  => SilvercartTools::getBaseURLSegment().'/silvercart_payment_sofortueberweisung/images/sofortueberweisung.png',
        );

        parent::createRequiredOrderStatus($requiredStatus);
        parent::createLogoImageObjects($paymentLogos, 'SilvercartPaymentSofortueberweisung');

        $paymentMethods = DataObject::get('SilvercartPaymentSofortueberweisung', "`suPaidOrderStatus`=0");
        if ($paymentMethods) {
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethod->suPaidOrderStatus    = DataObject::get_one('SilvercartOrderStatus', "`Code`='payed'")->ID;
                $paymentMethod->suSuccessOrderStatus = DataObject::get_one('SilvercartOrderStatus', "`Code`='sofortueberweisung_success'")->ID;
                $paymentMethod->suFailedOrderStatus  = DataObject::get_one('SilvercartOrderStatus', "`Code`='sofortueberweisung_error'")->ID;

                $paymentMethod->write();
            }
        }
    }
}
