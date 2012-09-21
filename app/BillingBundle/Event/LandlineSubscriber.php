<?php
namespace BillingBundle\Event;

use BillingBundle\BillingEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use BillingBundle\Bill;
use BillingBundle\Event\FilterBillEvent;

class LandlineSubscriber implements EventSubscriberInterface
{
    protected $unit_price;
    
    public function __construct($unit_price=0.1)
    {
        $this->unit_price = $unit_price;
    }
    
    static public function getSubscribedEvents()
    {
        return array(
                BillingEvents::onLandlineCallBill     => array('onLandlineCallBill', 0),
        );
    }

    public function onLandlineCallBill(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling LandlineSubscriber');
        
        $bill = $event->getBill();
        $bill->setUnitPrice( $this->unit_price );
        $event->setBill($bill);
        $bill->addRemark('LandlineSubscriber was called');
    }
}
