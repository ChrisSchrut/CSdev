<?php

/*
 * Adds a product to the cart if cart has no products
 * 
 * @category:   CSdev
 * @package:    CSdev_minoneitem
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

class CSdev_Minoneitem_Model_Addproduct extends Mage_Core_Model_Abstract {
    /*
     * Adds a product to the cart if it´s empty (only in Developer-Mode)
     * 
     * @param observer $observer
     * 
     */

    public function addProduct() {
        if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE']) || true) {
            $cart = Mage::getSingleton("checkout/cart");
            $count = $cart->getItemsCount();

            if ($count == 0) {
                $product_helper = Mage::getModel("catalog/product");
                $product_collection = $product_helper->getCollection();

                if ($product_collection->count() > 0) {
                    foreach ($product_collection as $item) {
                        $product = $product_helper->load($item->getEntityId());

                        try {
                            $cart->addProduct($product, array("qty" => 1));
                            $cart->save();
                            Mage::getSingleton("customer/session")->setCartWasUpdated(true);

                            return true;
                        } catch (Exception $ex) {
                            
                        }
                    }
                }
            }
        }

        return false;
    }

}