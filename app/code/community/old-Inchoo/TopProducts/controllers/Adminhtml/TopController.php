<?php

/**
 * @category     Inchoo
 * @package     Inchoo Top Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_TopProducts_Adminhtml_TopController extends Mage_Adminhtml_Controller_Action {

    protected function _initProduct() {

        $product = Mage::getModel('catalog/product')
                ->setStoreId($this->getRequest()->getParam('store', 0));


        if ($setId = (int) $this->getRequest()->getParam('set')) {
            $product->setAttributeSetId($setId);
        }

        if ($typeId = $this->getRequest()->getParam('type')) {
            $product->setTypeId($typeId);
        }

        $product->setData('_edit_mode', true);

        Mage::register('product', $product);

        return $product;
    }

    public function indexAction() {
        $this->_initProduct();

        $this->loadLayout()->_setActiveMenu('catalog/topproduct');

        $this->_addContent($this->getLayout()->createBlock('topproducts/adminhtml_edit'));

        $this->renderLayout();
    }

    public function gridAction() {

        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('topproducts/adminhtml_edit_grid')->toHtml()
        );
    }

    public function saveAction() {
        $data = $this->getRequest()->getPost();
        $collection = Mage::getModel('catalog/product')->getCollection();
        $storeId = $this->getRequest()->getParam('store', 0);


        parse_str($data['top_products'], $top_products);


        $collection->addIdFilter(array_keys($top_products));

        try {

            foreach ($collection->getItems() as $product) {

                $product->setData('inchoo_top_product', $top_products[$product->getEntityId()]);
                $product->setStoreId($storeId);
                $product->save();
            }


            $this->_getSession()->addSuccess($this->__('Top product was successfully saved.'));
            $this->_redirect('*/*/index', array('store' => $this->getRequest()->getParam('store')));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/index', array('store' => $this->getRequest()->getParam('store')));
        }
    }

    protected function _validateSecretKey() {
        return true;
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/catalog/topproduct');
    }

    private function _getExportFileName($extension='csv') {

        $store_id = $this->getRequest()->getParam('store');

        $name = 'top_products_';

        if ($store_id) {
            $store = Mage::getModel('core/store')->load($store_id);

            if ($store && $store->getId()) {
                return $name . $store->getName() . '.' . $extension;
            }
        }

        return $name . 'AllStores.' . $extension;
    }

    /**
     * Export stylist grid to CSV format
     */
    public function exportCsvAction() {

        $fileName = $this->_getExportFileName('csv');

        $content = $this->getLayout()->createBlock('topproducts/adminhtml_edit_grid')
                ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export stylist grid to XML format
     */
    public function exportXmlAction() {

        $fileName = $this->_getExportFileName('xml');

        $content = $this->getLayout()->createBlock('topproducts/adminhtml_edit_grid')
                ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}