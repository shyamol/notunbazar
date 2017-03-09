<?php

class Nbazar_Promotion_Model_Observer {

    public function handle_adminSystemConfigChangedSection(Varien_Event_Observer $observer) {

        //$adminSession = Mage::getSingleton('admin/session');
        $feature_price = Mage::getStoreConfig('promotion/feature/price', Mage::app()->getStore());
        $top_price = Mage::getStoreConfig('promotion/top/price', Mage::app()->getStore());
        $classic_price = Mage::getStoreConfig('promotion/classic/price', Mage::app()->getStore());
        $feature_product = Mage::getStoreConfig('promotion/feature/product', Mage::app()->getStore());
        $top_product = Mage::getStoreConfig('promotion/top/product', Mage::app()->getStore());
        $classic_product = Mage::getStoreConfig('promotion/classic/product', Mage::app()->getStore());
        if (!empty($feature_price)) {
            $collection = Mage::getModel('catalog/product')->load($feature_product);
            $collection->setData('price', $feature_price);
            $collection->getResource()->saveAttribute($collection, 'price');
        }
        if (!empty($top_price)) {
            $collection = Mage::getModel('catalog/product')->load($top_product);
            $collection->setData('price', $top_price);
            $collection->getResource()->saveAttribute($collection, 'price');
        }
        if (!empty($classic_price)) {
            $collection = Mage::getModel('catalog/product')->load($classic_product);
            $collection->setData('price', $classic_price);
            $collection->getResource()->saveAttribute($collection, 'price');
        }
    }

    public function saveOrderAfter(Varien_Event_Observer $observer) {
        //Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
        //$user = $observer->getEvent()->getUser();
        //$user->doSomething();
        $isPromotion = Mage::getSingleton('checkout/session')->getData('isPromotion');
        $customerData = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $productId = Mage::getSingleton('checkout/session')->getData('productId');
        $adType = Mage::getSingleton('checkout/session')->getData('adType');
        $order = $observer->getOrder();
        $orderId = $order->getId();
        if ($isPromotion == 'yes' && $productId != '') {
            $model = Mage::getModel('promotion/promotion');
            $model->setProductId($productId);
            $model->setAdtype($adType);
            $model->setOrderId($orderId);
            $model->setCustomerId($customerData);
            $model->setOrderStatus(1);
            $model->setStatus(2); //Disable
            $model->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
            $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
            $tablePromotion = Mage::getSingleton('core/resource')->getTableName('promotion'); //table name
            $result1 = "SELECT promotion_id FROM $tablePromotion where product_id = $productId && order_status = 3 && adtype = $adType && active_to > NOW() ORDER BY promotion_id desc LIMIT 1";            
            if($result1){
                $id = $connection->fetchRow($result1);
                $model1 = Mage::getModel('promotion/promotion')->load($id, 'promotion_id');
                $activeFrom = $model1->getActiveTo();
                $model->setActiveFrom($activeFrom);
            }
            $model->save();
        }
    }

    public function orderComplete(Varien_Event_Observer $observer) {
        $_event = $observer->getEvent();
        $_invoice = $_event->getInvoice();
        $_order = $_invoice->getOrder();
        $orderId = $_order->getId();
        //$invoiceId = $_invoice->getId();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $tablePromotion = Mage::getSingleton('core/resource')->getTableName('promotion'); //table name
        $result1 = "SELECT promotion_id FROM $tablePromotion where order_id = $orderId && active_from > NOW() ORDER BY promotion_id desc LIMIT 1";
        $id1 = $connection->fetchRow($result1);
        $tableCore_url_rewrite = Mage::getSingleton('core/resource')->getTableName('core_url_rewrite'); //table name
        $result2 = "SELECT promotion_id FROM $tablePromotion where order_id = $orderId";
        $id = $connection->fetchRow($result2);
        if ($id) { //check if exist            
            $model = Mage::getModel('promotion/promotion')->load($id, 'promotion_id');
            $model->setOrderStatus(3);
            $model->setStatus(1); // Enable
            if($result1){
                $model1 = Mage::getModel('promotion/promotion')->load($id1, 'promotion_id');
                $activeFrom = $model1->getActiveFrom();
            }
            else
            {
                $activeFrom = Mage::getSingleton('core/date')->gmtDate();
            }
            $model->setActiveFrom(Mage::getSingleton('core/date')->gmtDate());
            $adType1 = $model->getAdtype();
            $_productid = $model->getProductId();
            
            $collection = Mage::getModel('catalog/product')->load($_productid);
            // $adType1 = Mage::getSingleton('promotion/promotion')->getData('adtype');
            if ($adType1 == 1) {
                $collection->setStoreId(0);
                $collection->setData('inchoo_featured_product', 1);
                $collection->getResource()->saveAttribute($collection, 'inchoo_featured_product');
                //$collection->setData('status', 1);
                Mage::getModel('catalog/product_status')->updateProductStatus($_productid, 0, Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
                //$collection->getResource()->saveAttribute($collection, 'status');
                $configDate = Mage::getStoreConfig('promotion/feature/weeks', Mage::app()->getStore()) * 7;
                $model->setActiveTo(date('Y-m-d H:i:s', strtotime($activeFrom . ' + ' . $configDate . 'day')));
                $model->save();
            }
            if ($adType1 == 2) {
                $collection->setStoreId(0);
                $collection->setData('inchoo_top_product', 1);
                $collection->getResource()->saveAttribute($collection, 'inchoo_top_product');
                $collection->setData('status', 1);
                $collection->getResource()->saveAttribute($collection, 'status');
                $configDate = Mage::getStoreConfig('promotion/top/weeks', Mage::app()->getStore()) * 7;
                $model->setActiveTo(date('Y-m-d H:i:s', strtotime($activeFrom . ' + ' . $configDate . 'day')));
                $model->save();
            }
        }
    }

}
