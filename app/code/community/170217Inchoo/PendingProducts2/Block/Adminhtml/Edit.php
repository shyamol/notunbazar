<?php

/**
 * @category     Inchoo
 * @package     Inchoo pending Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_PendingProducts_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Grid_Container {

    protected $_saveButtonLabel = 'Save pending Products';
    //protected $_inchooUrl = 'http://inchoo.net';

     public function __construct()
    {
        $this->_blockGroup = 'inchoo_pendingproducts';
        $this->_controller = 'adminhtml_edit';
        $this->_headerText = Mage::helper('adminhtml')->__('pending products');
 
        parent::__construct();
        $this->_removeButton('add');
        $this->_addButton('save', array(
            'label' => $this->_saveButtonLabel,
            'onclick' => 'categorySubmit(\'' . $this->getSaveUrl() . '\')',
            'class' => 'Save',
        ));
    }

    // public function __construct() {


    //     $this->_blockGroup = 'featuredproducts';
    //     $this->_controller = 'adminhtml_edit';


    //     $this->_headerText = Mage::helper('adminhtml')->__('pending products');

    //     parent::__construct();

    //     $this->_removeButton('add');

    //     $this->_addButton('save', array(
    //         'label' => $this->_saveButtonLabel,
    //         'onclick' => 'categorySubmit(\'' . $this->getSaveUrl() . '\')',
    //         'class' => 'Save',
    //     ));
    // }

    public function getSaveUrl() {
        return $this->getUrl('*/*/save', array('store' => $this->getRequest()->getParam('store')));
    }

   

}