<?php

class Abac_Classified_Block_Profile extends Mage_Catalog_Block_Product_List {

	protected $_customer = null;

    protected function _getProductCollection()
    {
        $productMediaConfig = Mage::getModel('catalog/product_media_config');
        $_productCollection = Mage::getResourceModel('reports/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('sellerid', $this->getCustomer()->getId())
            ->setOrder('created_at', 'desc')
            ->addAttributeToFilter(
            'status',
            array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
        );

        $toolbar = Mage::getBlockSingleton('catalog/product_list')->getToolbarBlock();
        $toolbar->setCollection($_productCollection);
        //  echo $toolbar->toHtml();

        return $_productCollection;
    }

	public function getCustomer() {

		if ( $this->_customer == null ) {
			$nickname = $this->getRequest()->getParam('nickname');
			$collection = Mage::getResourceModel('customer/customer_collection')
					->addAttributeToFilter('nickname', $nickname);
			if ( ($customer = $collection->getFirstItem()) !== false ) {
				$this->_customer = $customer;
			}

		}
		return $this->_customer;
	}


    public function getAvatar( $customer_id )
    {
		$cpBlock = $this->getLayout()->getBlockSingleton('classified/form_edit');
        //print_r($cpBlock);

		return $cpBlock->getAvatar( $customer_id );

    }

    private function setCustomer($customer) {
    	$this->_customer = $customer;
    }

	public function getProducts()
	{
		$storeId = Mage::app()->getStore()->getId();

		$customerId = $this->getCustomer()->getId();

		$products = Mage::getResourceModel('customerpartner/customerpartner_product_collection')
					->addAttributeToSelect(array('product_id'))
					->addAttributeToFilter('customer_id', $customerId)
					->load()
					->toArray();

		$inP = array();
		if(!empty($products)){
			foreach($products as $p){
				$inP[] = $p['product_id'];
			}
		}

		$mageP = Mage::getModel('catalog/product')
			->getCollection()
            ->addAttributeToSelect('*')
            ->addStoreFilter($storeId)
            ->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $storeId)
            ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $storeId)
            ->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $storeId)
            ->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $storeId)
            ->addAttributeToFilter('entity_id', array('in' => $inP));

		return $mageP;

	}

	public function getReviews()
	{
        $rw = Mage::getModel('review/review')->getProductCollection();
        $rw
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addCustomerFilter($this->getCustomer()->getId())
            ->setDateOrder();

        return $rw;
	}

}