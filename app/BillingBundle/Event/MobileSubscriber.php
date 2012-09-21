<?php
namespace BillingBundle\Event;

use BillingBundle\BillingEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use BillingBundle\Bill;
use BillingBundle\Event\FilterBillEvent;

class MobileSubscriber implements EventSubscriberInterface
{
    protected $unit_price;
    
    public function __construct($unit_price=0.3)
    {
        $this->unit_price = $unit_price;
    }
    
    static public function getSubscribedEvents()
    {
        return array(
                BillingEvents::onMobileCallBill     => array('onMobileCallBill', 0),
        );
    }

    public function onMobileCallBill(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling MobileSubscriber');
        
        $bill = $event->getBill();
        $bill->setUnitPrice( $this->unit_price );
        $event->setBill($bill);
        $bill->addRemark('MobileSubscriber was called');
    }
}
