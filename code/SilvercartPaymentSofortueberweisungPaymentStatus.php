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
 * Order plugin.
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
     * Attributes.
     *
     * @var array
     */
    public static $db = array(
        'transactionId' => 'VarChar(100)',
        'status'        => "Enum('created,pending,received,error,loss','pending')",
        'queued'        => 'Boolean(0)'
    );


}