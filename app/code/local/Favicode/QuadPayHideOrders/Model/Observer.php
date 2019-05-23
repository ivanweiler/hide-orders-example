<?php

class Favicode_QuadPayHideOrders_Model_Observer
{
    /**
     * Example of hooking directly to sales_order_grid collection
     *
     * @param $observer
     */
    public function filterPendingOrders($observer)
    {

    }

    /**
     * Cron job that cancels pending QuadPay orders after 90 minutes
     *
     * @throws Exception
     */
    function cancelPendingOrders()
    {
        $orderCollection = Mage::getResourceModel('sales/order_grid_collection');

        $orderCollection
            ->addFieldToFilter('status', MR_QuadPay_Model_Method_Quadpay::ORDER_STATUS_PENDING_QUADPAY)
            ->addFieldToFilter('created_at', array(
                'lt' =>  new Zend_Db_Expr("DATE_ADD('".now()."', INTERVAL -'90:00' HOUR_MINUTE)")))
            ->getSelect()
            ->order('e.entity_id')
            ->limit(10)
        ;
        
        foreach($orderCollection->getItems() as $order) {
            $orderModel = Mage::getModel('sales/order');
            $orderModel->load($order->getId());

            if(!$orderModel->getId() || !$orderModel->canCancel()) {
                continue;
            }

            $orderModel->cancel();
            //$orderModel->setStatus('canceled_quadpay');
            $orderModel->save();
        }
    }
}