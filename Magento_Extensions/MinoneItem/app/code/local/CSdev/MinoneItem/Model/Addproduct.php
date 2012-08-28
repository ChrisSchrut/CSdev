<?php

/*
 * Adds a product to the cart if cart has no products
 * 
 * @category:   csdev
 * @package:    csdev_minoneitem
 * @author:     Christian Schrut   
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