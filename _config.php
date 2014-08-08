<?php
/**
 * Copyright 2014 pixeltricks GmbH
 *
 * This file is part of SilverCart.
 *
 * @package SilvercartPaymentSofortueberweisung
 * @subpackage Config
 * @ignore
 */

SilvercartPaymentSofortueberweisung::add_extension('SilvercartDataObjectMultilingualDecorator');
SilvercartOrder::add_extension('SilvercartPaymentSofortueberweisungOrder');
SilvercartShoppingCart::add_extension('SilvercartPaymentSofortueberweisungShoppingCart');

SilvercartOrderPluginProvider::add_extension('SilvercartPaymentSofortueberweisungOrderPlugin');