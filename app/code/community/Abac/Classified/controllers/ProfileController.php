<?php
class Abac_Classified_ProfileController extends Mage_Core_Controller_Front_Action
{


public function viewAction() {


	$nickname = $this->getRequest()->getParam('nickname');


            $this->loadLayout( array(
                'default',
                'classified_profile_view'
            ));

			$this->getLayout()->getBlock('head')->setTitle($nickname);
        	$this->renderLayout();

		#}

	}

}
?>