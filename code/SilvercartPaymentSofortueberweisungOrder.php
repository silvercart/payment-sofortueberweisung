<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package SilvercartPaymentSaferpay
 * @subpackage Base
 */

/**
 * Order plugin.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 * @author Sebastian Diel <sdiel@pixeltricks.de>,
 *         Sascha Koehler <skoehler@pixeltricks.de>
 * @since 08.08.2014
 * @copyright 2014 pixeltricks GmbH
 * @license see license file in modules root directory
 */
class SilvercartPaymentSofortueberweisungOrder extends DataExtension {
    
    /**
     * DB attributes.
     *
     * @var array
     */
    private static $db = array(
        'sofortueberweisungTransactionID' => 'VarChar(150)',
        'sofortueberweisungReason'        => 'VarChar(27)',
    );

    /**
     * Returns the Sofortueberweisung reason.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 16.11.2012
     */
    public function getSofortueberweisungReason() {
        return $this->owner->getField('sofortueberweisungReason');
    }

    /**
     * Returns the Sofortueberweisung ID.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getSofortueberweisungTransactionID() {
        return $this->owner->getField('sofortueberweisungTransactionID');
    }

    /**
     * Writes the given ID into the shoppingcart.
     *
     * @param string $reason The ID to save
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 16.11.2012
     */
    public function saveSofortueberweisungReason($reason) {
        $this->owner->setField('sofortueberweisungReason', (string) $reason);
        $this->owner->write();
    }

    /**
     * Writes the given ID into the shoppingcart.
     *
     * @param string $transactionID The ID to save
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function saveSofortueberweisungTransactionID($transactionID) {
        $this->owner->setField('sofortueberweisungTransactionID', (string) $transactionID);
        $this->owner->write();
    }
}