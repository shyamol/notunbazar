<?php
/**
 * Form AjaxSelect Element
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */

class All4coding_Core_Block_Widget_Form_Element_AjaxSelect extends Mage_Adminhtml_Block_Abstract
{
    /**
     * AjaxSelect mapper (by names)
     * array(
     *     'source_field_id' => array(
     *         'dest_field_id_1' => 'ajax link',
     *         'dest_field_id_1' => 'another ajax link',
     *         ...
     *     )
     * )
     * @var array
     */
    protected $_selections = array();
    
    /**
     * Register field id selections one from each other by specified values
     *
     * @param string $srcFieldId
     * @param string $destFieldId
     * @param string $ajaxLink
     * @return All4coding_Core_Block_Widget_Form_Element_AjaxSelect
     */
    public function addFieldSelection($srcFieldId, $destFieldId, $ajaxLink)
    {
        $this->_selections[$srcFieldId][$destFieldId] = $ajaxLink;
        
        return $this;
    }

    /**
     * HTML output getter
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_selections) {
            return '';
        }
        
        $html = '<script type="text/javascript"> document.observe(\'dom:loaded\', function() {';
        foreach($this->_selections as $srcFieldId => $data) {
            $html .= 'Event.observe($(\''.$srcFieldId.'\'), \'change\', function() {';
            foreach($data as $destFieldId => $ajaxLink) {
                $html .= "
                if ($('$srcFieldId').value == '') {
                    $('$destFieldId').update();
                    fireEvent($('$destFieldId'),'change');
                } else {
                    new Ajax.Request('$ajaxLink', {
                        method: 'get',
                        parameters: {value: $('$srcFieldId').value},
                        onSuccess: function(transport) {
                            var html = '';
                            var data = transport.responseJSON;
                            for (var i = 0, n = data.length; i < n; i++) {
                                html += '<option value=\"' + data[i].value + '\">' + data[i].label + '</option>';
                            }
                            $('$destFieldId').update(html);
                            fireEvent($('$destFieldId'), 'change');
                        },
                        onFailure: function(transport) {
                            location.href = BASE_URL;
                        }
                    });
                }";
            }
            $html .= '});';
        }
        $html .= '}); </script>';
        
        return $html;
    }
}