<?php
/**
 * Copyright 2012 pixeltricks GmbH
 *
 * This file is part of SilvercartPrepaymentPayment.
 *
 * SilvercartPaypalPayment is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SilvercartPrepaymentPayment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SilvercartPrepaymentPayment.  If not, see <http://www.gnu.org/licenses/>.
 *
 * German (Germany) language pack
 *
 * @package Silvercart
 * @subpackage i18n
 * @ignore
 */

i18n::include_locale_file('silvercart_payment_sofortueberweisung', 'en_US');

global $lang;

if (array_key_exists('de_DE', $lang) && is_array($lang['de_DE'])) {
    $lang['de_DE'] = array_merge($lang['en_US'], $lang['de_DE']);
} else {
    $lang['de_DE'] = $lang['en_US'];
}

$lang['de_DE']['SilvercartOrderStatus']['SOFORTUEBERWEISUNG_CANCELED']    = 'SOFORT Überweisung abgebrochen';
$lang['de_DE']['SilvercartOrderStatus']['SOFORTUEBERWEISUNG_ERROR']       = 'SOFORT Überweisung Fehler';
$lang['de_DE']['SilvercartOrderStatus']['SOFORTUEBERWEISUNG_LOSS']        = 'SOFORT Überweisung Verlust';
$lang['de_DE']['SilvercartOrderStatus']['SOFORTUEBERWEISUNG_SUCCESS']     = 'Bezahlt via SOFORT Überweisung';

$lang['de_DE']['SilvercartPaymentSofortueberweisung']['API']                                    = 'API Daten';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['CONFIG_KEY']                             = 'Konfigurations-Schlüssel';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['ENTER_DATA_AT_SOFORTUEBERWEISUNG']       = 'Bezahlung bei SOFORT Überweisung';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['INFOTEXT_CHECKOUT']                      = 'Mit dem TÜV-zertifizierten Bezahlsystem SOFORT Überweisung können Sie ohne Registrierung, einfach und sicher, mit ihren gewohnten Online-Banking-Daten zahlen (PIN & TAN). <a href="https://documents.sofort.com/sue/kundeninformationen/" target="_blank">Mehr hier</a>.';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['ORDER_CONFIRMATION_SUBMIT_BUTTON_TITLE'] = 'Kaufen & weiter zur Bezahlung bei SOFORT Überweisung';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['ORDERSTATUS_CANCELED']                   = 'Bestellstatus für Meldung "abgebrochen"';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['ORDERSTATUS_PAYED']                      = 'Bestellstatus für Meldung "bezahlt"';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['PLURALNAME']                             = 'SOFORT Überweisung';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['SINGULARNAME']                           = 'SOFORT Überweisung';
$lang['de_DE']['SilvercartPaymentSofortueberweisung']['SHOWLANGUAGES']                          = 'Anzeige der Sprachauswahl im Sofortüberweisung VT Menü';

$lang['de_DE']['SilvercartPaymentSofortueberweisungLanguage']['SINGULARNAME'] = 'Übersetzung der Zahlart SOFORT Überweisung';
$lang['de_DE']['SilvercartPaymentSofortueberweisungLanguage']['PLURALNAME']   = 'Übersetzungen der Zahlart SOFORT Überweisung';