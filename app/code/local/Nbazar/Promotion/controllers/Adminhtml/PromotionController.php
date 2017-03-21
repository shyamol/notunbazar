<?php

class Nbazar_Promotion_Adminhtml_PromotionController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("promotion/promotion")->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion  Manager"), Mage::helper("adminhtml")->__("Promotion Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Manager Promotion"));
        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction() {
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("promotion/promotion")->load($id);
        if ($model->getId()) {
            Mage::register("promotion_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("promotion/promotion");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Manager"), Mage::helper("adminhtml")->__("Promotion Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Description"), Mage::helper("adminhtml")->__("Promotion Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("promotion/adminhtml_promotion_edit"))->_addLeft($this->getLayout()->createBlock("promotion/adminhtml_promotion_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("promotion")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction() {

        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("New Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("promotion/promotion")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("promotion_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("promotion/promotion");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Manager"), Mage::helper("adminhtml")->__("Promotion Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Description"), Mage::helper("adminhtml")->__("Promotion Description"));


        $this->_addContent($this->getLayout()->createBlock("promotion/adminhtml_promotion_edit"))->_addLeft($this->getLayout()->createBlock("promotion/adminhtml_promotion_edit_tabs"));

        $this->renderLayout();
    }

    public function saveAction() {
        $post_data = $this->getRequest()->getPost();
        // print_r($post_data);
        //echo $post_data['status'];
        // exit();
        if ($post_data) {
            try {
                $model = Mage::getModel("promotion/promotion")
                        ->addData($post_data)
                        ->setId($this->getRequest()->getParam("id"))
                        ->save();
                $product = Mage::getModel('catalog/product')->load($post_data['product_id']);
                // echo $product->getStatus();
                //  exit();
                $product->setStatus($post_data['status']);
                $product->save();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Promotion was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setPromotionData(false);
                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setPromotionData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }
        }
        $this->_redirect("*/*/");
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("promotion/promotion");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

    public function massRemoveAction() {
        try {
            $ids = $this->getRequest()->getPost('promotion_ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("promotion/promotion");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    public function massStatusAction() {
        $ids = $this->getRequest()->getPost('promotion_ids', array());
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    Mage::getSingleton('promotion/promotion')
                            ->load($id)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                    $action = $this->getRequest()->getParam('status');
                    $model = Mage::getModel('promotion/promotion')->load($id, 'promotion_id');
                    $adType1 = $model->getAdtype();
                    $_productid = $model->getProductId();
                    $collection = Mage::getModel('catalog/product')->load($_productid);
                    if ($adType1 == 1 && $action == 2) {

                        $collection->setData('inchoo_featured_product', 0);
                        $collection->getResource()->saveAttribute($collection, 'inchoo_featured_product');
                    }
                    if ($adType1 == 2 && $action == 2) {

                        $collection->setData('inchoo_top_product', 0);
                        $collection->getResource()->saveAttribute($collection, 'inchoo_top_product');
                    }
                    if ($adType1 == 1 && $action == 1) {

                        $collection->setData('inchoo_featured_product', 1);
                        $collection->getResource()->saveAttribute($collection, 'inchoo_featured_product');
                    }
                    if ($adType1 == 2 && $action == 1) {

                        $collection->setData('inchoo_top_product', 1);
                        $collection->getResource()->saveAttribute($collection, 'inchoo_top_product');
                    }
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($ids))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'promotion.csv';
        $grid = $this->getLayout()->createBlock('promotion/adminhtml_promotion_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction() {
        $fileName = 'promotion.xml';
        $grid = $this->getLayout()->createBlock('promotion/adminhtml_promotion_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}
