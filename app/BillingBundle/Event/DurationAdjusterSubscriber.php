<?php
namespace BillingBundle\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use BillingBundle\Bill;
use BillingBundle\BillingEvents;
use BillingBundle\Event\FilterBillEvent;

class DurationAdjusterSubscriber implements EventSubscriberInterface
{
    protected $min; 
    
    public function __construct($min = 60)
    {
        $this->min = $min;
    }
    
    static public function getSubscribedEvents()
    {
        return array(
                BillingEvents::onAnyCallBill     => array('onAnyCallBill', 0),
        );
    }

    public function onAnyCallBill(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling DurationAdjuster');
        
        $bill = $event->getBill();
        $call = $bill->getCall();
        if( $call['duration'] < $this->min ){
            $bill->setQuantity($this->min);
        }
        else 
        {
            $bill->setQuantity($call['duration']);
        }
        $event->setBill($bill);
        $bill->addRemark('DurationAdjuster was called');
    }
}
