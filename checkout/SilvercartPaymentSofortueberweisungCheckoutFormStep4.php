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
 * CheckoutProcessPaymentAfterOrder
 *
 * @package Silvercart
 * @subpackage Forms Checkout
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class SilvercartPaymentSofortueberweisungCheckoutFormStep4 extends SilvercartCheckoutFormStepPaymentInit {

    /**
     * Process the current step
     *
     * @return void
     * 
     * @author Sebastian Diel <sdiel@pixeltricks.de>,
     *         Sascha Koehler <skoehler@pixeltricks.de>
     * @since 08.08.2014
     */
    public function process() {
        if (parent::process()) {
            $paymentSuccessful  = false;
            $checkoutData       = $this->controller->getCombinedStepData();
            $orderObj           = SilvercartOrder::get()->byId($checkoutData['orderId']);

            if ($this->paymentMethodObj &&
                $orderObj) {
                $this->paymentMethodObj->setOrder($orderObj);
                $paymentSuccessful = $this->paymentMethodObj->processPaymentAfterOrder();
            }

            if ($paymentSuccessful) {
                $this->controller->addCompletedStep();
                $this->controller->NextStep();
            } else {
                return $this->renderError();
            }
        }
    }
}