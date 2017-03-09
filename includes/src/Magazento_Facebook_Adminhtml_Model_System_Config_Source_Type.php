<?php
class Magazento_Facebook_Adminhtml_Model_System_Config_Source_Type
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('facebook')->__('')),
            array('value'=>'activity', 'label'=>Mage::helper('facebook')->__('activity')),
            array('value'=>'actor', 'label'=>Mage::helper('facebook')->__('actor')),
            array('value'=>'article', 'label'=>Mage::helper('facebook')->__('article')),
            array('value'=>'athlete', 'label'=>Mage::helper('facebook')->__('athlete')),
            array('value'=>'author', 'label'=>Mage::helper('facebook')->__('author')),
            array('value'=>'band', 'label'=>Mage::helper('facebook')->__('band')),
            array('value'=>'bar', 'label'=>Mage::helper('facebook')->__('bar')),
            array('value'=>'blog', 'label'=>Mage::helper('facebook')->__('blog')),
            array('value'=>'book', 'label'=>Mage::helper('facebook')->__('book')),
            array('value'=>'cafe', 'label'=>Mage::helper('facebook')->__('cafe')),
            array('value'=>'cause', 'label'=>Mage::helper('facebook')->__('cause')),
            array('value'=>'city', 'label'=>Mage::helper('facebook')->__('city')),
            array('value'=>'company', 'label'=>Mage::helper('facebook')->__('company')),
            array('value'=>'country', 'label'=>Mage::helper('facebook')->__('country')),
            array('value'=>'director', 'label'=>Mage::helper('facebook')->__('director')),
            array('value'=>'drink', 'label'=>Mage::helper('facebook')->__('drink')),
            array('value'=>'food', 'label'=>Mage::helper('facebook')->__('food')),
            array('value'=>'game', 'label'=>Mage::helper('facebook')->__('game')),
            array('value'=>'government', 'label'=>Mage::helper('facebook')->__('government')),
            array('value'=>'hotel', 'label'=>Mage::helper('facebook')->__('hotel')),
            array('value'=>'landmark', 'label'=>Mage::helper('facebook')->__('landmark')),
            array('value'=>'movie', 'label'=>Mage::helper('facebook')->__('movie')),
            array('value'=>'musician', 'label'=>Mage::helper('facebook')->__('musician')),
            array('value'=>'non_profit', 'label'=>Mage::helper('facebook')->__('non_profit')),
            array('value'=>'politician', 'label'=>Mage::helper('facebook')->__('politician')),
            array('value'=>'product', 'label'=>Mage::helper('facebook')->__('product')),
            array('value'=>'public_figure', 'label'=>Mage::helper('facebook')->__('public_figure')),
            array('value'=>'restaurant', 'label'=>Mage::helper('facebook')->__('restaurant')),
            array('value'=>'school', 'label'=>Mage::helper('facebook')->__('school')),
            array('value'=>'sport', 'label'=>Mage::helper('facebook')->__('sport')),
            array('value'=>'sports_league', 'label'=>Mage::helper('facebook')->__('sports_league')),
            array('value'=>'sports_team', 'label'=>Mage::helper('facebook')->__('sports_team')),
            array('value'=>'state_province', 'label'=>Mage::helper('facebook')->__('state_province')),
            array('value'=>'tv_show', 'label'=>Mage::helper('facebook')->__('tv_show')),
            array('value'=>'university', 'label'=>Mage::helper('facebook')->__('university')),
            array('value'=>'website', 'label'=>Mage::helper('facebook')->__('website'))
        );
    }
}