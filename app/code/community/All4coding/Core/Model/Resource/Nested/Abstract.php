<?php
/**
 * Core Nested Model
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
abstract class All4coding_Core_Model_Resource_Nested_Abstract extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * An array to cache values in recursive processes.
     *
     * @var   array
     */
    protected $_cache = array();
    
    /**
     * Method to move a row in the ordering sequence of a group of rows defined by an SQL WHERE clause.
     * Negative numbers move the row up in the sequence and positive numbers move it down.
     * 
     * @param   All4coding_Core_Model_Nested_Abstract   $object The object to be move
     * @param   integer  $delta  The direction and magnitude to move the row in the ordering sequence.
     * @param   string   $where  WHERE clause to use for limiting the selection of rows to compact the
     *                           ordering values.
     *
     * @return  mixed    Boolean true on success.
     */
    public function move($object, $delta, $where = '')
    {
        $this->_beforeMove($object, $delta, $where);
        $readAdapter = $this->_getReadAdapter();
        /* @var $readAdapter Varien_Db_Adapter_Interface */
        $select = $readAdapter->select()
            ->from($this->getMainTable(), array($this->getIdFieldName()))
            ->where('parent_id = '.$object->getParentId())
        ;
        
        if ($where) {
            $select->where($where);
        }
        
        $position = 'after';
        if ($delta > 0) {
            $select->where('rgt > '.$object->getRgt())
                ->order('rgt ASC')
            ;
            $position = 'after';
        } else {
            $select->where('lft < '.$object->getLft())
                ->order('lft DESC')
            ;
            $position = 'before';
        }
        $referenceId = $readAdapter->fetchOne($select);
        if ($referenceId) {
            $this->moveByReference($object, $referenceId, $position);
        }
        $this->_afterMove($object, $delta, $where);
        
        return $this;
    }
    
    /**
     * Method to move a node and its children to a new location in the tree.
     *
     * @param   All4coding_Core_Model_Nested_Abstract   $main       The main node to move to the new location 
     * @param   int     $referenceId  The primary key of the node to reference new location by.
     * @param   string  $position     Location type string. ['before', 'after', 'first-child', 'last-child']
     *
     * @return  All4coding_Core_Model_Resource_Nested_Abstract
     */
    public function moveByReference(All4coding_Core_Model_Nested_Abstract $main, $referenceId, $position = 'after')
    {
        $readAdapter    = $this->_getReadAdapter();
        /* @var $readAdapter Varien_Db_Adapter_Interface */
        $writeAdapter   = $this->_getWriteAdapter();
        /* @var $writeAdapter Varien_Db_Adapter_Interface */

        // get the ids of child nodes
        $select = $readAdapter->select()
            ->from($this->getMainTable(), array($this->getIdFieldName()))
            ->where('lft >= '.(int)$main->getLft())
            ->where('lft <= '.(int)$main->getRgt())
        ;
        $children = $readAdapter->fetchCol($select);
        
        // Cannot move the node to be a child of itself.
        if (in_array($referenceId, $children)) {
            Mage::throwException(Mage::helper('all4coding_core')->__('%s: :move Failed - Cannot move the node to be a child of itself', get_class($this)));
        }
        
        /*
         * Move the sub-tree out of the nested sets by negating its left and right values.
        */
        $writeAdapter->update($this->getMainTable(), 
            array(
                'lft' => new Zend_Db_Expr('lft * (-1)'), 
                'rgt' => new Zend_Db_Expr('rgt * (-1)')
            ), 
            'lft >= '.(int)$main->getLft().' AND lft <= '.(int)$main->getRgt()
        );
        
        /*
         * Close the hole in the tree that was opened by removing the sub-tree from the nested sets.
         */
        // Compress the left values.
        $writeAdapter->update($this->getMainTable(),
            array('lft' => new Zend_Db_Expr('lft - '.(int)$main->getWidth())),
            'lft > '.(int)$main->getRgt()
        );

        // Compress the right values.
        $writeAdapter->update($this->getMainTable(),
            array('rgt' => new Zend_Db_Expr('rgt - '.(int)$main->getWidth())),
            'rgt > '.(int)$main->getRgt()
        );
        
        //  We are moving the tree to be the last child of the root node if reference node is empty
        if (!$referenceId) {
           $reference = $main->getRoot();
           $position = 'last-child';
        } else {
            $reference = $main->getNode($referenceId);
        }
        
        $repositionData = $this->_getTreeRepositionData($reference, $main->getWidth(), $position);
        
        /*
         * Create space in the nested sets at the new location for the moved sub-tree.
         */
        // Shift left values.
        $writeAdapter->update($this->getMainTable(),
            array('lft' => new Zend_Db_Expr('lft + '.(int)$main->getWidth())),
            $repositionData->getLeftWhere()
        );
        // Shift right values.
        $writeAdapter->update($this->getMainTable(),
            array('rgt' => new Zend_Db_Expr('rgt + '.(int)$main->getWidth())),
            $repositionData->getRightWhere()
        );

        /*
         * Calculate the offset between where the node used to be in the tree and
         * where it needs to be in the tree for left ids (also works for right ids).
         */
        $offset = $repositionData->getNewLft() - $main->getLft();
        $levelOffset = $repositionData->getNewLevel() - $main->getLevel();
        
        // Move the nodes back into position in the tree using the calculated offsets.
        $writeAdapter->update($this->getMainTable(),
            array(
                'lft' => new Zend_Db_Expr((int)$offset.' - lft'),
                'rgt' => new Zend_Db_Expr((int)$offset.' - rgt'),
                'level' => new Zend_Db_Expr('level + '.(int)$levelOffset)
            ),
            'lft < 0'
        );
        
        // Set the correct parent id for the moved node if required.
        if ($main->getParentId() != $repositionData->getNewParentId()) {
            $writeAdapter->update($this->getMainTable(),
                array('parent_id' => $repositionData->getNewParentId()),
                $this->getIdFieldName().' = '.$main->getId()
            );
            
            // Change the path of the node and it children
            $newParent = $main->getNode($repositionData->getNewParentId());
            $writeAdapter->update($this->getMainTable(),
                array('path' => new Zend_Db_Expr('REPLACE(path, '.$writeAdapter->quote($main->getPath()).', '.$writeAdapter->quote($newParent->getPath().'/'.$main->getId()).')')),
                'path LIKE '.$writeAdapter->quote($main->getPath().'%')
            );
            $main->setPath($newParent->getPath().'/'.$main->getId());
        }

        // Set the object values.
        $main->setParentId($repositionData->getNewParentId());
        $main->setLevel($repositionData->getNewLevel());
        $main->setLft($repositionData->getNewLft());
        $main->setRgt($repositionData->getNewRgt());

        return $this;
    }    
    
    /**
     * Method to get various data necessary to make room in the tree at a location
     * for a node and its children.  The returned data object includes conditions
     * for SQL WHERE clauses for updating left and right id values to make room for
     * the node as well as the new left and right ids for the node.
     *
     * @param   object   $referenceNode  A node object with at least a 'lft' and 'rgt' with
     *                                   which to make room in the tree around for a new node.
     * @param   integer  $nodeWidth      The width of the node for which to make room in the tree.
     * @param   string   $position       The position relative to the reference node where the room
     *                                   should be made.
     *
     * @return  Varien_Object   Boolean false on failure or data object on success.
     *
     * @since   11.1
     */
    protected function _getTreeRepositionData(All4coding_Core_Model_Nested_Abstract $referenceNode, $nodeWidth, $position = 'before')
    {
        // Make sure the reference an object with a left and right id.
        if (!($referenceNode->hasData('lft') && $referenceNode->hasData('rgt'))){
            Mage::throwException(Mage::helper('all4coding_core')->__('Invalid reference Node'));
        }

        // A valid node cannot have a width less than 2.
        if ($nodeWidth < 2) {
            Mage::throwException(Mage::helper('all4coding_core')->__('Invalid main node width'));
        }

        // Initialise variables.
        $data = new Varien_Object();

        // Run the calculations and build the data object by reference position.
        switch ($position)
        {
            case 'first-child':
                $data->setLeftWhere('lft > '.$referenceNode->getLft());
                $data->setRightWhere('rgt >= '.$referenceNode->getLft());
                
                $data->setNewLft($referenceNode->getLft() + 1);
                $data->setNewRgt($referenceNode->getLft() + $nodeWidth);
                $data->setNewParentId($referenceNode->getId());
                $data->setNewLevel($referenceNode->getLevel() + 1);
                break;

            case 'last-child':
                $data->setLeftWhere('lft > '.$referenceNode->getRgt());
                $data->setRightWhere('rgt >= '.$referenceNode->getRgt());
                
                $data->setNewLft($referenceNode->getRgt());
                $data->setNewRgt($referenceNode->getRgt() + $nodeWidth - 1);
                $data->setNewParentId($referenceNode->getId());
                $data->setNewLevel($referenceNode->getLevel() + 1);
                break;

            case 'before':
                $data->setLeftWhere('lft >= '.$referenceNode->getLft());
                $data->setRightWhere('rgt >= '.$referenceNode->getLft());
                
                $data->setNewLft($referenceNode->getLft());
                $data->setNewRgt($referenceNode->getLft() + $nodeWidth - 1);
                $data->setNewParentId($referenceNode->getParentId());
                $data->setNewLevel($referenceNode->getLevel());
                break;

            default:
            case 'after':
                $data->setLeftWhere('lft > '.$referenceNode->getRgt());
                $data->setRightWhere('rgt > '.$referenceNode->getRgt());
                
                $data->setNewLft($referenceNode->getRgt() + 1);
                $data->setNewRgt($referenceNode->getRgt() + $nodeWidth);
                $data->setNewParentId($referenceNode->getParentId());
                $data->setNewLevel($referenceNode->getLevel());
                break;
        }

        return $data;
    }
    
    /**
     * Delete the object
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    public function delete(Mage_Core_Model_Abstract $object)
    {
        $this->_beforeDelete($object);
        
        $writeAdapter = $this->_getWriteAdapter();
        /* @var $writeAdapter Varien_Db_Adapter_Interface */
        
        // Should we delete all children along with the node?
        if (!$object->getKeepChildren())
        {
            // Delete the node and all of its children.
            $writeAdapter->delete($this->getMainTable(),
                'lft >= '.(int)$object->getLft().' AND lft <='.(int)$object->getRgt()
            );

            // Compress the left values.
            $writeAdapter->update($this->getMainTable(),
                array('lft' => new Zend_Db_Expr('lft - '.(int)$object->getWidth())),
                'lft > '.(int)$object->getRgt()
            );

            // Compress the right values.
            $writeAdapter->update($this->getMainTable(),
                array('rgt' => new Zend_Db_Expr('rgt - '.(int)$object->getWidth())),
                'rgt > '.(int)$object->getRgt()
            );
        }

        // Leave the children and move them up a level.
        else
        {
            // Adjust all the parent values for direct children of the deleted node.
            $writeAdapter->update($this->getMainTable(),
                array(
                    'parent_id' => (int)$object->getParentId(),
                ),
                'parent_id = '.(int)$object->getId()
            );
            
            // Delete the node.
            $writeAdapter->delete($this->getMainTable(),
                'lft = '.(int)$object->getLft()
            );

            // Shift all node's children up a level.
            $writeAdapter->update($this->getMainTable(),
                array(
                    'lft'   => new Zend_Db_Expr('lft - 1'),
                    'rgt'   => new Zend_Db_Expr('rgt - 1'),
                    'level' => new Zend_Db_Expr('level - 1')
                ),
                'lft >= '.(int)$object->getLft().' AND lft <= '.(int)$object->getRgt()
            );

            // Shift all of the left values that are right of the node.
            $writeAdapter->update($this->getMainTable(),
                array('lft' => new Zend_Db_Expr('lft - 2')),
                'lft > '.(int)$object->getRgt()
            );

            // Shift all of the right values that are right of the node.
            $writeAdapter->update($this->getMainTable(),
                array('rgt' => new Zend_Db_Expr('rgt - 2')),
                'rgt > '.(int)$object->getRgt()
            );
            
            // Change the path of it child
            $parent = $object->getNode($object->getParentId());
            $writeAdapter->update($this->getMainTable(),
                array('path' => new Zend_Db_Expr('REPLACE(path, '.$writeAdapter->quote($object->getPath()).','.$writeAdapter->quote($parent->getPath()).')')),
                'path LIKE '.$writeAdapter->quote($object->getPath().'%')
            );
        }
        
        $this->_afterDelete($object);
        return $this;
    }
    
    /**
     * Method to move a node one position to the left in the same level.
     *
     * @param   All4coding_Core_Model_Nested_Abstract  $object  The node to move.
     * @return  All4coding_Core_Model_Resource_Nested_Abstract
     */
    public function orderUp(All4coding_Core_Model_Nested_Abstract $object)
    {
        $this->_beforeOrderUp($object);
        
        // Get the left sibling node.
        $sibling = $object->getNode($object->getLft() - 1, 'right');
        
        $readAdapter = $this->_getReadAdapter();
        $writeAdapter = $this->_getWriteAdapter();

        // Get the primary keys of child nodes.
        $select = $readAdapter->select()
            ->from($this->getMainTable(), $this->getIdFieldName())
            ->where('lft >= '.$object->getLft())
            ->where('lft <= '.$object->getRgt())
        ;
        $children = $readAdapter->fetchCol($select);
        
        // Shift left and right values for the node and it's children.
        $writeAdapter->update($this->getMainTable(),
            array(
                'lft' => new Zend_Db_Expr('lft - '.(int)$sibling->getWidth()),
                'rgt' => new Zend_Db_Expr('rgt - '.(int)$sibling->getWidth())
            ),
            'lft >= '.(int)$object->getLft().' AND lft <= '.(int)$object->getRgt()
        );

        // Shift left and right values for the sibling and it's children.
        $writeAdapter->update($this->getMainTable(),
            array(
                'lft' => new Zend_Db_Expr('lft + '.(int)$object->getWidth()),
                'rgt' => new Zend_Db_Expr('rgt + '.(int)$object->getWidth())
            ),
            array(
                'lft >= '.(int)$sibling->getLft(),
                'lft <= '.(int)$sibling->getRgt(),
                $this->getIdFieldName().' NOT IN ('.implode(',', $children).')'
            )
        );
        
        $this->_afterOrderUp($object);
        return $this;
    }

    /**
     * Method to move a node one position to the right in the same level.
     *
     * @param   All4coding_Core_Model_Nested_Abstract  $object  The node to move.
     * @return  All4coding_Core_Model_Resource_Nested_Abstract
     */
    public function orderDown(All4coding_Core_Model_Nested_Abstract $object)
    {
        $this->_beforeOrderDown($object);
        // Get the right sibling node.
        $sibling = $object->getNode($object->getRgt() + 1, 'left');
        
        $readAdapter = $this->_getReadAdapter();
        $writeAdapter = $this->_getWriteAdapter();

        // Get the primary keys of child nodes.
        $select = $readAdapter->select()
            ->from($this->getMainTable(), $this->getIdFieldName())
            ->where('lft >= '.(int)$object->getLft().' AND lft <= '.(int)$object->getRgt())
        ;
        $children = $readAdapter->fetchCol($select);
        
        // Shift left and right values for the node and it's children.
        $writeAdapter->update($this->getMainTable(),
            array(
                'lft' => new Zend_Db_Expr('lft + '.(int)$sibling->getWidth()),
                'rgt' => new Zend_Db_Expr('rgt + '.(int)$sibling->getWidth())
            ),
            'lft >= '.(int)$object->getLft().' AND lft <= '.(int)$object->getRgt()
        );

        // Shift left and right values for the sibling and it's children.
        $writeAdapter->update($this->getMainTable(),
            array(
                'lft' => new Zend_Db_Expr('lft - '.(int)$object->getWidth()),
                'rgt' => new Zend_Db_Expr('rgt - '.(int)$object->getWidth())
            ),
            array(
                'lft >= '.(int)$sibling->getLft(),
                'lft <= '.(int)$sibling->getRgt(),
                $this->getIdFieldName().' NOT IN ('.implode(',', $children).')'
            )
        );
        
        $this->_afterOrderDown($object);
        return $this;
    }
    
    /**
     * Gets the ID of the root item in the tree
     *
     * @return  mixed    The ID of the root row, or false and the internal error is set.
     */
    public function getRootId()
    {
        $readAdapter = $this->_getReadAdapter();
        
        // Test for a unique record with parent_id = 0
        $select = $readAdapter->select()
            ->from($this->getMainTable(), $this->getIdFieldName())
            ->where('parent_id IS NULL')
        ;
        
        $result = $readAdapter->fetchCol($select);
        
        if (count($result) == 1) {
            $rootId = $result[0];
        } else {
            // Test for a unique record with lft = 0
            $select = $readAdapter->select()
                ->from($this->getMainTable(), $this->getIdFieldName())
                ->where('parent_id IS NULL')
                ->where('lft = 0')
            ;
            $result = $readAdapter->fetchCol($select);
            
            if (count($result) == 1) {
                $rootId = $result[0];
            } else {
                Mage::throwException(Mage::helper('all4coding_core')->__('Root node not found'));
            }
        }
        return $rootId;
    }
    
    /**
     * Method to rebuild the whole nested set tree.
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     *
     * @return  All4coding_Core_Model_Nested_Abstract
     */
    public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
    {
        $this->_beforeRebuild($parentId, $leftId, $level, $path);
        $this->_rebuild($parentId, $leftId, $level, $path);
        unset($this->_cache['rebuild.sql']);
        $this->_afterRebuild($parentId, $leftId, $level, $path);
        return $this;
    }
    
    /**
     * Method to recursively rebuild the whole nested set tree.
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     *
     * @return  integer  1 + value of root rgt on success, false on failure
     */
    protected function _rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
    {
        // If no parent is provided, try to find it.
        if ($parentId === null) {
            // Get the root item.
            $parentId = $this->getRootId();
            $path = $parentId;
        }
        
        $readAdapter = $this->_getReadAdapter();
        $writeAdapter = $this->_getWriteAdapter();
        
        $fields = $readAdapter->describeTable($this->getMainTable());
        $fields = array_keys($fields);
        
        // Build the structure of the recursive query.
        if (!isset($this->_cache['rebuild.sql'])) {
            $select = $readAdapter->select()
                ->from($this->getMainTable())
                ->where('parent_id = %d')
            ;
            if (in_array('position', $fields)) {
                $select->order(array('parent_id', 'position', 'lft'));
            } else {
                $select->order(array('parent_id', 'lft'));
            }
            
            $this->_cache['rebuild.sql'] = (string) $select;
        }

        // Make a shortcut to database object.

        // Assemble the query to find all children of this node.
        $children = $readAdapter->fetchCol(sprintf($this->_cache['rebuild.sql'], (int)$parentId));

        // The right value of this node is the left value + 1
        $rightId = $leftId + 1;

        // execute this function recursively over all children
        foreach ($children as $node)
        {
            // $rightId is the current right value, which is incremented on recursion return.
            // Increment the level for the children.
            // Add this item's alias to the path (but avoid a leading /)
            $rightId = $this->_rebuild($node, $rightId, $level + 1, $path.(empty($path) ? '' : '/').$node);

            // If there is an update failure, return false to break out of the recursion.
            // if ($rightId === false) return false;
        }

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value.
        $writeAdapter->update($this->getMainTable(),
            array(
                'lft'   => (int)$leftId,
                'rgt'   => (int)$rightId,
                'level' => (int)$level,
                'path'  => $path
            ),
            $this->getIdFieldName().' = '.(int)$parentId
        );

        // Return the right value of this node + 1.
        return $rightId + 1;
    }
    
    /**
     * Perform actions before object save
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!is_null($object->getId()) && (!$this->_useIsObjectNew || !$object->isObjectNew())) {
            $referenceId = null;
            if ($object->getOrigData('parent_id') != $object->getParentId()) {
                $referenceId = $object->getParentId();
                $location = 'last-child';
            } elseif ($object->getLocationId() == -1) {
                $referenceId = $object->getParentId();
                $location = 'first-child';
            } elseif ($object->getLocationId() && $object->getLocationId() != $object->getId()) {
                $referenceId = $object->getLocationId();
                $location = 'after';
            }
            if ($referenceId) {
                $this->moveByReference($object, $referenceId, $location);
            }
        } else {
            if ($object->getParentId()) {
                $reference = $object->getNode($object->getParentId());
                if (!$reference->getId()) {
                    Mage::throwException(Mage::helper('all4coding_core')->__('Invalid Parent ID'));
                }
            } else {
                $reference = $object->getRoot();
            }
            $repositionData = $this->_getTreeRepositionData($reference, 2, 'last-child');
            
            $writeAdapter = $this->_getWriteAdapter();

            // Create space in the tree at the new location for the new node in left ids.
            $writeAdapter->update($this->getMainTable(),
                array('lft' => new Zend_Db_Expr('lft + 2')),
                $repositionData->getLeftWhere()
            );

            // Create space in the tree at the new location for the new node in right ids.
            $writeAdapter->update($this->getMainTable(),
                array('rgt' => new Zend_Db_Expr('rgt + 2')),
                $repositionData->getRightWhere()
            );

            // Set the object values.
            $object->setParentId($repositionData->getNewParentId());
            $object->setLevel($repositionData->getNewLevel());
            $object->setLft($repositionData->getNewLft());
            $object->setRgt($repositionData->getNewRgt());
        }
        return $this;
    }

    /**
     * Perform actions after object save
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getPath()) {
            $path = $object->getNode($object->getParentId())->getPath().'/'.$object->getId();
            $this->_getWriteAdapter()
                ->update($this->getMainTable(),
                    array('path' => $path),
                    $this->getIdFieldName().' = '.$object->getId()
                )
            ;
            $object->setPath($path);
        }
        return $this;
    }
    
    /**
     * Perform actions before object move
     *
     * @param   All4coding_Core_Model_Nested_Abstract $object
     * @param   integer  $delta  The direction and magnitude to move the row in the ordering sequence.
     * @param   string   $where  WHERE clause to use for limiting the selection of rows to compact the
     *                           ordering values.
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _beforeMove(All4coding_Core_Model_Nested_Abstract $object, $delta, $where)
    {
        return $this;
    }

    /**
     * Perform actions after object move
     *
     * @param   All4coding_Core_Model_Nested_Abstract $object
     * @param   integer  $delta  The direction and magnitude to move the row in the ordering sequence.
     * @param   string   $where  WHERE clause to use for limiting the selection of rows to compact the
     *                           ordering values.
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _afterMove(Mage_Core_Model_Abstract $object, $delta, $where)
    {
        return $this;
    }
    
    /**
     * Perform actions before object order up
     *
     * @param   All4coding_Core_Model_Nested_Abstract $object
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _beforeOrderUp(All4coding_Core_Model_Nested_Abstract $object)
    {
        return $this;
    }

    /**
     * Perform actions after object order up
     *
     * @param   All4coding_Core_Model_Nested_Abstract $object
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _afterOrderUp(Mage_Core_Model_Abstract $object)
    {
        return $this;
    }
    
    /**
     * Perform actions before object order down
     *
     * @param   All4coding_Core_Model_Nested_Abstract $object
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _beforeOrderDown(All4coding_Core_Model_Nested_Abstract $object)
    {
        return $this;
    }

    /**
     * Perform actions after object order down
     *
     * @param   All4coding_Core_Model_Nested_Abstract $object
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _afterOrderDown(Mage_Core_Model_Abstract $object)
    {
        return $this;
    }
    
    /**
     * Perform actions before rebuild whole nested set
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     * 
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _beforeRebuild($parentId, $leftId, $level, $path)
    {
        return $this;
    }

    /**
     * Perform actions after rebuild whole nested set
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     * 
     * @return All4coding_Core_Model_Resource_Nested_Abstract
     */
    protected function _afterRebuild($parentId, $leftId, $level, $path)
    {
        return $this;
    }
}