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
 * Order plugin.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Base
 * @author Sascha Koehler <skoehler@pixeltricks.de>
 * @since 15.11.2012
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyright 2012 pixeltricks GmbH
 */
class SilvercartPaymentSofortueberweisungOrder extends DataObjectDecorator {

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
                'sofortueberweisungToken'      => 'VarChar(150)',
                'sofortueberweisungIdentifier' => 'VarChar(150)'
            )
        );
    }

    /**
     * Returns the Sofortueberweisung ID.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getSofortueberweisungID() {
        return $this->owner->getField('sofortueberweisungIdentifier');
    }

    /**
     * Returns the Sofortueberweisung token.
     *
     * @return string
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function getSofortueberweisungToken() {
        return $this->owner->getField('sofortueberweisungToken');
    }

    /**
     * Writes the given ID into the shoppingcart.
     *
     * @param string $sofortueberweisungID The ID to save
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function saveSofortueberweisungID($sofortueberweisungID) {
        $this->owner->setField('sofortueberweisungIdentifier', (string) $sofortueberweisungID);
        $this->owner->write();
    }

    /**
     * Writes the given token into the shoppingcart.
     *
     * @param string $sofortueberweisungToken The token to save
     *
     * @return void
     *
     * @author Sascha Koehler <skoehler@pixeltricks.de>
     * @since 15.11.2012
     */
    public function saveSofortueberweisungToken($sofortueberweisungToken) {
        $this->owner->setField('sofortueberweisungToken', (string) $sofortueberweisungToken);
        $this->owner->write();
    }
}