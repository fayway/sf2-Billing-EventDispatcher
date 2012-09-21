<?php
namespace BillingBundle\Event;

use BillingBundle\Event\FilterBillEvent;

class FreedomListener
{
    public function onFreeCall(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling FreedomListener');
        
        $bill = $event->getBill();
        $bill->setUnitPrice(0);
        $event->setBill($bill);
        
        $bill->addRemark('FreedomListener was called');
        
        $event->stopPropagation();
    }
}