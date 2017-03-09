<?php
class Nbazar_Promotion_Block_Adminhtml_Promotion_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("promotion_form", array("legend"=>Mage::helper("promotion")->__("Item information")));

				
						$fieldset->addField("product_id", "text", array(
						"label" => Mage::helper("promotion")->__("Product ID"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "product_id",
						//"readonly" => "readonly",
						));
                                                $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('promotion')->__('Status'),
						'values'   => Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getValueArray5(),
						'name' => 'status',					
						"class" => "required-entry",
						"required" => true,
						//'disabled' => 'disabled',
						));
									
						 $fieldset->addField('adtype', 'select', array(
						'label'     => Mage::helper('promotion')->__('AD Type'),
						'values'   => Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getValueArray1(),
						'name' => 'adtype',					
						"class" => "required-entry",
						"required" => true,
						//'disabled' => 'disabled',
						));
						$fieldset->addField("customer_id", "text", array(
						"label" => Mage::helper("promotion")->__("Customer ID"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "customer_id",
						//"readonly" => "readonly",
						));
					
						$fieldset->addField("order_id", "text", array(
						"label" => Mage::helper("promotion")->__("Order ID"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "order_id",
						//"readonly" => "readonly",
						));
									
						 $fieldset->addField('order_status', 'select', array(
						'label'     => Mage::helper('promotion')->__('Order Status'),
						'values'   => Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getValueArray4(),
						'name' => 'order_status',
						//'disabled' => 'disabled',
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('active_from', 'date', array(
						'label'        => Mage::helper('promotion')->__('Active From'),
						'name'         => 'active_from',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('active_to', 'date', array(
						'label'        => Mage::helper('promotion')->__('Active To'),
						'name'         => 'active_to',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('created_at', 'hidden', array(
						'label'        => Mage::helper('promotion')->__('Created Date'),
						'name'         => 'created_at',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('updated_at', 'hidden', array(
						'label'        => Mage::helper('promotion')->__('Update Date'),
						'name'         => 'updated_at',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));

				if (Mage::getSingleton("adminhtml/session")->getPromotionData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getPromotionData());
					Mage::getSingleton("adminhtml/session")->setPromotionData(null);
				} 
				elseif(Mage::registry("promotion_data")) {
				    $form->setValues(Mage::registry("promotion_data")->getData());
				}
				return parent::_prepareForm();
		}
}
