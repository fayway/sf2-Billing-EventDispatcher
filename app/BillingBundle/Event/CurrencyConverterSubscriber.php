<?php
namespace BillingBundle\Event;

use BillingBundle\BillingEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use BillingBundle\Bill;
use BillingBundle\Event\FilterBillEvent;

class CurrencyConverterSubscriber implements EventSubscriberInterface
{
    protected $change;
    protected $currency;
    
    public function __construct($currency = '$', $change = 1.5)
    {
        $this->change = $change;
        $this->currency = $currency;
    }
    
    static public function getSubscribedEvents()
    {
        return array(
                BillingEvents::onAmericanCustomerCallBill     => array('onAmericanCustomerCallBill', 0),
        );
    }

    public function onAmericanCustomerCallBill(FilterBillEvent $event)
    {
        global $firephp;
        $firephp->log('Calling CurrencyConverterSubscriber using rate '.$this->change);
        
        $bill = $event->getBill();
        $bill->setUnitPrice($bill->getUnitPrice() * $this->change);
        $bill->setCurrency( $this->currency );
        $event->setBill($bill);
        $bill->addRemark('CurrencyConverterSubscriber was called');
        
        $event->getDispatcher()->dispatch(BillingEvents::onInternationalBill, $event);
    }
}
