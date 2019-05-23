<?php

class Favicode_QuadPayHideOrders_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    public function setCollection($collection)
    {
        $collection->addFieldToFilter('status', array('neq' => 'pending_quadpay')); //MR_QuadPay_Model_Method_Quadpay::ORDER_STATUS_PENDING_QUADPAY
        parent::setCollection($collection);
    }

}
