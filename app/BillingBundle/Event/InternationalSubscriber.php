<?php
namespace BillingBundle\Event;

use BillingBundle\BillingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use BillingBundle\Bill;
use BillingBundle\Event\FilterBillEvent;

class InternationalSubscriber implements EventSubscriberInterface
{
    
    public function __construct()
    {
    }
    
    static public function getSubscribedEvents()
    {
        return array(
                BillingEvents::onInternationalBill     => array('onInternationalBill', 0),
        );
    }

    public function onInternationalBill(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling InternationalSubscriber');
        
        $bill = $event->getBill();
        //do something when international bill
        $event->setBill($bill);
        $bill->addRemark('InternationalSubscriber was called');
    }
}
