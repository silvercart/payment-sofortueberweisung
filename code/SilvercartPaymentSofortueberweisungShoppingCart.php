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
 * @subpackage Base
 */

/**
 * Extends SilvercartShoppingCart.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 15.11.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2012 pixeltricks GmbH
 */
class SilvercartPaymentSofortueberweisungShoppingCart extends DataObjectDecorator {

    /**
     * Additional datafields and relations.
     *
     * @return array
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function extraStatics() {
        return array(
            'db' => array(
                'sofortueberweisungTransactionID' => 'VarChar(150)',
                'sofortueberweisungReason'        => 'VarChar(27)'
            )
        );
    }

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