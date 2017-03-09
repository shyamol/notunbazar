<?php
/**
 * Core Nested Model
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
abstract class All4coding_Core_Model_Nested_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Method to get the root node
     * 
     * @return All4coding_Core_Model_Nested_Abstract
     */
    public function getRoot()
    {
        $nodeId = $this->getResource()->getRootId();
        
        $className = get_class($this);
        $node = new $className();
        /* @var $node All4coding_Core_Model_Nested_Abstract */
        return $node->load($nodeId);
    }

    /**
     * Method to get nested set properties for a node in the tree.
     *
     * @param   integer  $id   Value to look up the node by.
     * @param   string   $key  Key to look up the node by.
     *
     * @return  All4coding_Core_Model_Nested_Abstract
     */
    public function getNode($id, $key = null)
    {
        // Determine which key to get the node base on.
        switch ($key)
        {
            case 'parent':
                $k = 'parent_id';
                break;
            case 'left':
                $k = 'lft';
                break;
            case 'right':
                $k = 'rgt';
                break;
            default:
                $k = $this->getResource()->getIdFieldName();
                break;
        }
        
        $select = $this->getResource()
            ->getReadConnection()
            ->select()
            ->from($this->getResource()->getMainTable(), $this->getIdFieldName())
            ->where($k.' = '.(int)$id)
        ;
        $nodeId = $this->getResource()->getReadConnection()->fetchOne($select);
        if (!$nodeId) {
            Mage::throwException(Mage::helper('all4coding_core')->__('%s: :getNode Failed', get_class($this)));
        }
        
        $className = get_class($this);
        $node = new $className();
        /* @var $node All4coding_Core_Model_Nested_Abstract */
        
        return $node->load($nodeId);
    }
    
    /**
     * Method to get a collection of nodes from a given node to its root.
     *
     * @param   integer $pk Primary key of the node for which to get the path.
     * @return  mixed   Collection Object
     */
    public function getPathCollection($id = null)
    {
        $id = is_null($id) ? $this->getId() : $id;
        
        $collection = $this->getCollection();
        /* @var $collection Varien_Data_Collection_Db */
        $collection->getSelect()
            ->from(array('tmp' => $this->getResource()->getMainTable()), array())
            ->where('tmp.lft >= main_table.lft')
            ->where('tmp.lft <= main_table.rgt')
            ->where('tmp.'.$this->getResource()->getIdFieldName().' = '.(int)$id)
            ->order('main_table.lft')
        ;
        
        return $collection;
    }
    
    /**
     * Method to get a collection of a node and all its child nodes.
     * 
     * @param   integer $pk Primary key of the node for which to get the path.
     * @return  mixed   Collection Object
     */
    public function getTreeCollection($id = null)
    {
        $id = is_null($id) ? $this->getId() : $id;
        
        $collection = $this->getCollection();
        /* @var $collection Varien_Data_Collection_Db */
        $collection->getSelect()
            ->from(array('tmp' => $this->getResource()->getMainTable()), array())
            ->where('main_table.lft >= tmp.lft')
            ->where('main_table.lft <= tmp.rgt')
            ->where('tmp.'.$this->getResource()->getIdFieldName().' = '.(int)$id)
            ->order('main_table.lft')
        ;
        
        return $collection;
    }
    
    /**
     * Method to determine if a node is a leaf node in the tree (has no children).
     *
     * @return  boolean  True if a leaf node.
     */
    public function isLeaf()
    {
        // The node is a leaf node.
        return (($this->getRgt() - $this->getLft()) == 1);
    }
    
    /**
     * Method to move a row in the ordering sequence of a group of rows defined by an SQL WHERE clause.
     * Negative numbers move the row up in the sequence and positive numbers move it down.
     *
     * @param   integer  $delta  The direction and magnitude to move the row in the ordering sequence.
     * @param   string   $where  WHERE clause to use for limiting the selection of rows to compact the
     *                           ordering values.
     *
     * @return  All4coding_Core_Model_Nested_Abstract
     */
    public function move($delta, $where = '')
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeMove();
            $this->_getResource()->move($this, $delta, $where);
            $this->_afterMove();

            $this->_getResource()->commit();
            $this->_afterMoveCommit();
        }
        catch (Exception $e){
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }
        
    /**
     * Method to move a node one position to the left in the same level.
     *
     * @return  All4coding_Core_Model_Nested_Abstract
     */
    public function orderUp()
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeOrderUp();
            $this->_getResource()->orderUp($this);
            $this->_afterOrderUp();
            
            $this->_getResource()->commit();
            $this->_afterOrderUpCommit();
        }
        catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }
        
    /**
     * Method to move a node one position to the right in the same level.
     *
     * @return  All4coding_Core_Model_Nested_Abstract
     */
    public function orderDown()
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeOrderDown();
            $this->_getResource()->orderDown($this);
            $this->_afterOrderDown();
            
            $this->_getResource()->commit();
            $this->_afterOrderDownCommit();
        }
        catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }
        
    /**
     * Method to rebuild the whole nested set tree.
     *
     * @return  All4coding_Core_Model_Nested_Abstract
     */
    public function rebuild()
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeRebuild();
            $this->_getResource()->rebuild();
            $this->_afterRebuild();
            
            $this->_getResource()->commit();
            $this->_afterRebuildCommit();
        }
        catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }
    
    /**
     * Processing object before move node
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeMove()
    {
        Mage::dispatchEvent('model_move_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_move_before', $this->_getEventData());
        $this->cleanModelCache();
        return $this;
    }
    
    /**
     * Processing object after move node
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterMove()
    {
        Mage::dispatchEvent('model_move_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_move_after', $this->_getEventData());
        return $this;
    }

    /**
     * Processing manipulation after main transaction commit
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterMoveCommit()
    {
        Mage::dispatchEvent('model_move_commit_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_move_commit_after', $this->_getEventData());
         return $this;
    }
    
    /**
     * Processing object before order up node
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeOrderUp()
    {
        Mage::dispatchEvent('model_order_up_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_up_before', $this->_getEventData());
        $this->cleanModelCache();
        return $this;
    }
    
    /**
     * Processing object after order up node
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterOrderUp()
    {
        Mage::dispatchEvent('model_order_up_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_up_after', $this->_getEventData());
        return $this;
    }

    /**
     * Processing manipulation after main transaction commit
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterOrderUpCommit()
    {
        Mage::dispatchEvent('model_order_up_commit_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_up_commit_after', $this->_getEventData());
         return $this;
    }
    
    /**
     * Processing object before order down node
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeOrderDown()
    {
        Mage::dispatchEvent('model_order_down_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_down_before', $this->_getEventData());
        $this->cleanModelCache();
        return $this;
    }
    
    /**
     * Processing object after order down node
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterOrderDown()
    {
        Mage::dispatchEvent('model_order_down_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_down_after', $this->_getEventData());
        return $this;
    }

    /**
     * Processing manipulation after main transaction commit
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterOrderDownCommit()
    {
        Mage::dispatchEvent('model_order_down_commit_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_down_commit_after', $this->_getEventData());
         return $this;
    }
    
    /**
     * Processing object before rebuild the whole nested set tree.
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeRebuild()
    {
        Mage::dispatchEvent('model_order_down_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_down_before', $this->_getEventData());
        $this->cleanModelCache();
        return $this;
    }
    
    /**
     * Processing object after rebuild the whole nested set tree.
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterRebuild()
    {
        Mage::dispatchEvent('model_order_down_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_down_after', $this->_getEventData());
        return $this;
    }

    /**
     * Processing manipulation after main transaction commit
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterRebuildCommit()
    {
        Mage::dispatchEvent('model_order_down_commit_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_order_down_commit_after', $this->_getEventData());
         return $this;
    }
    
    /**
     * Processing object after load data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterLoad()
    {
        // Do some simple calculations.
        if ($this->getId()) {
            $this->setNumChildren((int)($this->getRgt() - $this->getLft() - 1) / 2);
            $this->setWidth((int)$this->getRgt() - $this->getLft() + 1);
        } else {
            $this->setNumChildren(0);
            $this->setWidth(2);
        }
        
        return parent::_afterLoad();
    }
    
}