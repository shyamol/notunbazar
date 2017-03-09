<?php

class Nbazar_CountryRegion_Adminhtml_ModelController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("countryregion/model")->_addBreadcrumb(Mage::helper("adminhtml")->__("Model  Manager"),Mage::helper("adminhtml")->__("Model Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("CountryRegion"));
			    $this->_title($this->__("Manager Model"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("CountryRegion"));
				$this->_title($this->__("Model"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("countryregion/model")->load($id);
				if ($model->getId()) {
					Mage::register("model_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("countryregion/model");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Model Manager"), Mage::helper("adminhtml")->__("Model Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Model Description"), Mage::helper("adminhtml")->__("Model Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("countryregion/adminhtml_model_edit"))->_addLeft($this->getLayout()->createBlock("countryregion/adminhtml_model_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("countryregion")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("CountryRegion"));
		$this->_title($this->__("Model"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("countryregion/model")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("model_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("countryregion/model");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Model Manager"), Mage::helper("adminhtml")->__("Model Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Model Description"), Mage::helper("adminhtml")->__("Model Description"));


		$this->_addContent($this->getLayout()->createBlock("countryregion/adminhtml_model_edit"))->_addLeft($this->getLayout()->createBlock("countryregion/adminhtml_model_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("countryregion/model")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Model was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setModelData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setModelData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("countryregion/model");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('country_region_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("countryregion/model");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'model.csv';
			$grid       = $this->getLayout()->createBlock('countryregion/adminhtml_model_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'model.xml';
			$grid       = $this->getLayout()->createBlock('countryregion/adminhtml_model_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
