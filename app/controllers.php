<?php
// controllers.php
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;

use BillingBundle\Bill;
use BillingBundle\BillingEvents;
use BillingBundle\Event\FilterBillEvent;
use BillingBundle\Event\DiscountSubscriber;
use BillingBundle\Event\DurationAdjusterSubscriber;
use BillingBundle\Event\CurrencyConverterSubscriber;
use BillingBundle\Event\InternationalSubscriber;
use BillingBundle\Event\LandlineSubscriber;
use BillingBundle\Event\MobileSubscriber;
use BillingBundle\Event\FreedomListener;

function list_action()
{
    $calls = get_all_calls();
    $html = render_template('templates/list.php', array('calls' => $calls));

    return new Response($html);
}

function bill_action($id)
{
    global $firephp;
    $call = get_call_by_id($id);
    
    $bill = new Bill();
    $bill->setCall($call);
    $bill->setUnitPrice(0.3);
    $bill->setQuantity($call['duration']);
    
    // saving a copy of bill before event dispatching
    $original_bill = clone $bill;
    
    //creating dispatcher
    $dispatcher = new EventDispatcher();
    $event = new FilterBillEvent($bill);
    
    //free call listener example
    $freeCallListener = new FreedomListener();
    $dispatcher->addListener(BillingEvents::onFreeCallBill, array($freeCallListener, 'onFreeCall'));
     
    //event subscribers examples
    $durationSubscriber = new DurationAdjusterSubscriber(60);
    $dispatcher->addSubscriber($durationSubscriber);
    
    $landlineSubscriber = new LandlineSubscriber(0.1);
    $dispatcher->addSubscriber($landlineSubscriber);
    
    $mobileSubscriber = new MobileSubscriber(0.3);
    $dispatcher->addSubscriber($mobileSubscriber);
    
    $discountSubscriber = new DiscountSubscriber(0.5);
    $dispatcher->addSubscriber($discountSubscriber);
    
    $dollarConverterSubscriber = new CurrencyConverterSubscriber();
    $dispatcher->addSubscriber($dollarConverterSubscriber);
    
    $internationalSubscriber = new InternationalSubscriber();
    $dispatcher->addSubscriber($internationalSubscriber);
    
    //dispatching events
    //free call ?
    if(preg_match('/0800.+/i', $call['recipient']))
    {
        $dispatcher->dispatch(BillingEvents::onFreeCallBill, $event);
    }
    
    if (!$event->isPropagationStopped()) {
        //Landline , mobile or free ?
        if(preg_match('/0537.+/i', $call['recipient']))
        {
            $dispatcher->dispatch(BillingEvents::onLandlineCallBill, $event);
        } else {
            $dispatcher->dispatch(BillingEvents::onMobileCallBill, $event);
        }
        
        //Weekend or Week days ?
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $call['time']);
        $call_day = $date->format('l');
        if( $call_day=='Sunday' || $call_day=='Saturday'){
            $dispatcher->dispatch(BillingEvents::onNightAndWeekendCallBill, $event);
        }
        
        //European or American ?
        if(preg_match('/Mr.*/i', $call['customer'])){
            $dispatcher->dispatch(BillingEvents::onAmericanCustomerCallBill, $event);
        }
        
        $dispatcher->dispatch(BillingEvents::onAnyCallBill, $event);
        //
    }
    
    $html = render_template('templates/bill.php', array('call' => $call, 'original_bill' => $original_bill, 'bill'=>$bill));

    return new Response($html);
}

// helper function to render templates
function render_template($path, array $args)
{
    extract($args);
    ob_start();
    require $path;
    $html = ob_get_clean();

    return $html;
}