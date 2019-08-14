<?php
	
	class Webcreta_Prevnext_Model_Prevnext extends Mage_Core_Model_Abstract
	{
		public function _construct()
		{
			parent::_construct();
			$this->_init('prevnext/prevnext');
			
		}
		
		public function adminhtmlWidgetContainerHtmlBefore(Varien_Event_Observer $observer)
		{
			$enable = Mage::getStoreConfig('webcreta/webcreta_group/webcreta_order_select',Mage::app()->getStore());
			
			if($enable == 1){
				$block = $observer->getBlock();
				$orderId = Mage::app()->getRequest()->getParam('order_id');
				
				if(isset($orderId) && !empty($orderId)){
					$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
					$sql = "select entity_id from sales_flat_order where entity_id < $orderId order by entity_id desc limit 1;";
					$nextRows = $connection->fetchRow($sql);
					
					$nextOrderId = $nextRows['entity_id'];
					
					$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
					$sql = "select entity_id from sales_flat_order where entity_id > $orderId order by entity_id asc limit 1;";
					$prevRows = $connection->fetchRow($sql);
					//	echo "<pre>";print_r($prevRows);die;
					$prevOrderId = $prevRows['entity_id'];
				}
				
				if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
					if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
						if(isset($nextOrderId) && !empty($nextOrderId)){
							$prevUrl = Mage::helper("adminhtml")->getUrl("*/sales_order/view",array('order_id'=>$nextOrderId)); 
							$block->addButton('Prev', array(
							'label'     => Mage::helper('core')->__('Prev'),
							'onclick'   => "setLocation('{$prevUrl}')",
							));
						}
						
						if(isset($prevOrderId) && !empty($prevOrderId)){
							$nextUrl = Mage::helper("adminhtml")->getUrl("*/sales_order/view",array('order_id'=>$prevOrderId)); 
							$block->addButton('Next', array(
							'label'     => Mage::helper('core')->__('Next'),
							'onclick'   => "setLocation('{$nextUrl}')",
							));
						}
						
					}
					return $this;
				}
			}
		}
		
		public function adminhtmlWidgetContainerHtmlBeforecustomer(Varien_Event_Observer $observer)
		{
			
			$enable = Mage::getStoreConfig('webcreta/webcreta_group/webcreta_customer_select',Mage::app()->getStore());
			
			if($enable == 1){
				
				$block = $observer->getBlock();
				$custId = Mage::app()->getRequest()->getParam('id');
				
				if(isset($custId) && !empty($custId)){
					$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
					$sql = "select entity_id from customer_entity where entity_id < $custId order by entity_id desc limit 1;";
					$nextRows = $connection->fetchRow($sql);
					
					$nextCustId = $nextRows['entity_id'];
					
					$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
					$sql = "select entity_id from customer_entity where entity_id > $custId order by entity_id asc limit 1;";
					$prevRows = $connection->fetchRow($sql);
					//	echo "<pre>";print_r($prevRows);die;
					$prevCustId = $prevRows['entity_id'];
				}
				
				if ($block instanceof Mage_Adminhtml_Block_Customer_Edit) {
					if ($block instanceof Mage_Adminhtml_Block_Customer_Edit) {
						if(isset($nextCustId) && !empty($nextCustId)){
							$prevUrl = Mage::helper("adminhtml")->getUrl("*/customer/edit",array('id'=>$nextCustId)); 
							$block->addButton('Prev', array(
							'label'     => Mage::helper('core')->__('Prev'),
							'onclick'   => "setLocation('{$prevUrl}')",
							));
						}
						
						if(isset($prevCustId) && !empty($prevCustId)){
							$nextUrl = Mage::helper("adminhtml")->getUrl("*/customer/edit",array('id'=>$prevCustId)); 
							$block->addButton('Next', array(
							'label'     => Mage::helper('core')->__('Next'),
							'onclick'   => "setLocation('{$nextUrl}')",
							));
						}
					}
					return $this;
				}
				
			}
		}
		
		
		public function adminhtmlWidgetContainerHtmlBeforeproduct(Varien_Event_Observer $observer)
		{
			$enable = Mage::getStoreConfig('webcreta/webcreta_group/webcreta_product_select',Mage::app()->getStore());
			
			if($enable == 1){
				$layout = Mage::app()->getLayout();
				$productEditBlock = $layout->getBlock('product_edit');
				$block = $observer->getBlock();
				$proId = Mage::app()->getRequest()->getParam('id');
				//echo "<pre>";print_r($custId);die;
				$saveAndContinueButton = $productEditBlock->getChild('save_and_edit_button');
				
				if(isset($proId) && !empty($proId)){
					$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
					$sql = "select entity_id from catalog_product_entity where entity_id > $proId order by entity_id asc limit 1;";
					$nextRows = $connection->fetchRow($sql);
					$nextProId = $nextRows['entity_id'];
					
					$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
					$sql = "select entity_id from catalog_product_entity where entity_id < $proId order by entity_id desc limit 1;";
					$prevRows = $connection->fetchRow($sql);
					$prevProId = $prevRows['entity_id'];
				}
				
				$container = $layout->createBlock('core/text_list', 'button_container');
				$container->append($saveAndContinueButton);
				// Create new button
				
				if(isset($prevProId) && !empty($prevProId)){
					$prevUrl = Mage::helper("adminhtml")->getUrl("*/catalog_product/edit",array('id'=>$prevProId)); 	
					$next = $layout->createBlock('adminhtml/widget_button')
					->setData(array(
					'label'     => Mage::helper('prevnext')->__('Prev'),
					'onclick'   => "setLocation('{$prevUrl}')",
					'class'  => 'save'
					));
					$container->append($next);	
				}
				
				if(isset($nextProId) && !empty($nextProId)){
					$nextUrl = Mage::helper("adminhtml")->getUrl("*/catalog_product/edit",array('id'=>$nextProId)); 
					$prev = $layout->createBlock('adminhtml/widget_button')
					->setData(array(
					'label'     => Mage::helper('prevnext')->__('Next'),
					'onclick'   => "setLocation('{$nextUrl}')",
					'class'  => 'save'
					));
					$container->append($prev);
				}
				
				// Create a container that will gather existing "Save and Continue Edit" button and the new button
				
				
				// Append existing "Save and Continue Edit" button and the new button to the container
				
				
				// Replace the existing "Save and Continue Edit" button with our container
				$productEditBlock->setChild('save_and_edit_button', $container);
			}
		}
		
	}													