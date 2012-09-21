<?php
namespace BillingBundle\Event;

use BillingBundle\BillingEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use BillingBundle\Bill;
use BillingBundle\Event\FilterBillEvent;

class DiscountSubscriber implements EventSubscriberInterface
{
    protected $rate;
    
    public function __construct($rate = 0.5)
    {
        $this->rate = $rate;
    }
    
    static public function getSubscribedEvents()
    {
        return array(
                BillingEvents::onNightAndWeekendCallBill     => array('onNightAndWeekendCallBill', 0),
        );
    }

    public function onNightAndWeekendCallBill(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling DiscountSubscriber');
        
        $bill = $event->getBill();
        $bill->setUnitPrice( $bill->getUnitPrice() - ( $bill->getUnitPrice() * $this->rate ) );
        $event->setBill($bill);
        $bill->addRemark('NightAndWeekendDiscountSubscriber was called');
    }
}
