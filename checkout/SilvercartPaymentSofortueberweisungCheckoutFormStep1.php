<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package Silvercart
 * @subpackage Forms Checkout
 */

/**
 * CheckoutProcessPaymentBeforeOrder
 *
 * @package Silvercart
 * @subpackage Forms Checkout
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class SilvercartPaymentSofortueberweisungCheckoutFormStep1 extends SilvercartCheckoutFormStepPaymentInit {

    /**
     * Here we set some preferences.
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function preferences() {
        parent::preferences();

        $this->preferences['stepTitle']     = _t('SilvercartPaymentSofortueberweisung.ENTER_DATA_AT_SOFORTUEBERWEISUNG');
        $this->preferences['stepIsVisible'] = true;
    }

    /**
     * Process the current step
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function process() {
        if (parent::process()) {
            $this->paymentMethodObj->setCancelLink(Director::absoluteURL($this->controller->Link()) . 'Cancel');
            $this->paymentMethodObj->setReturnLink(Director::absoluteURL($this->controller->Link()));

            if (!$this->paymentMethodObj->processPaymentBeforeOrder()) {
                return $this->renderError();
            }
        }
    }
}