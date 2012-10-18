<?php

/*
 * Returns all payment methods, that are allowed at a checkout with a total of 0 (zero)
 * 
 * @category:   CSdev
 * @package:    CSdev_TotalZero
 * @author:     Christian Schrut   
 * 
 * Copyright (c) 2012 Christian Schrut
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms are permitted
 * provided that the above copyright notice and this paragraph are
 * duplicated in all such forms and that any documentation,
 * advertising materials, and other materials related to such
 * distribution and use acknowledge that the software was developed
 * by the <organization>.  The name of the
 * University may not be used to endorse or promote products derived
 * from this software without specific prior written permission.
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND WITHOUT ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
 * 
 */

class CSdev_TotalZero_Block_Onepage_Payment_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods {
    private $allowedTotalZeroMethods = array("checkmo", "costcenter");

    public function getMethods() {
        $methods = $this->getData('methods');

        if (is_null($methods)) {
            $quote = $this->getQuote();
            $store = $quote ? $quote->getStoreId() : null;
            $methods = $this->helper('payment')->getStoreMethods($store, $quote);
            $total = $quote->getBaseSubtotal() + $quote->getShippingAddress()->getBaseShippingAmount();

            foreach ($methods as $key => $method) {
                if ($this->_canUseMethod($method)
                        && ($total != 0
                        || $method->getCode() == 'free'
                        || ($total == 0 && in_array($method->getCode(), $this->allowedTotalZeroMethods))
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles()))) {

                    $this->_assignMethod($method);
                } else {
                    unset($methods[$key]);
                }
            }

            $this->setData('methods', $methods);
        }

        return $methods;
    }

}