# Billing Phone Calls - Event Driven Approach with Symfony2 

## Aim of the project

In practice, standard Object Oriented Programming can not solve certain complex design cases 
especially when it comes to ensure the code extensibility.

Even with OOP inheritance, it’s certainly not going to be easy to design a good ecosystem 
in what plugins can communicate and add extra methods and processes without interfering with each other.

The Symfony2 Event Dispatcher component implements the Observer pattern in a simple and effective 
way to make all these things possible and to make your projects truly extensible.

The second goal of this project is to prove how it’s easy to use only standalone Symfony2 components. 
The big deal is to integrate a separate Symfony2 component into existing projects 
without the need to migrate the whole thing into Symfony2 framework.

In this example only the 3 following componenets are used:
 1. *ClassLoader*
(git clone https://github.com/symfony/ClassLoader vendor/symfony/Component/ClassLoader)
 2. *HttpFoundation*
(git clone https://github.com/symfony/HttpFoundation vendor/symfony/Component/HttpFoundation)
 3. *EventDispatcher*
(git clone https://github.com/symfony/EventDispatcher vendor/symfony/Component/EventDispatcher)
 

## Use case

A telecom company will need a software application to bill its customers phone plans.
In this demonstration we will limit billing rules into the following list:

 1. Call durations are 60 second indivisible, durations under this level still 
has to be billed 60s, in the other case, the exact amount of seconds is conserved.
 2. Calls to phone numbers beginning with *0800* are free
 3. Calls to landline numbers (beginning with *0537*) are billed *1Dh* as unit price
 4. Calls to mobile numbers (others not beginning with *0800* or *0537*) are billed *3Dh* the unit
 5. Calls made during weekend and nights have a *50%* discount
 6. If the customer is American, the bill cost is calculated and converted to $

To make it simple, this table will play the project log source:

| id | customer  | recipient  | time                | duration |

|----------------------------------------------------------------|

| 1     | Mr Bob    | 0537604425 | 2012-09-04 01:09:31 | 45       |

| 2     | Mr Bob    | 0033986532 | 2012-09-07 13:12:24 | 85       |

| 3     | Jean-Pierre | 0656544545 | 2012-09-11 21:18:00 | 145      |

|----------------------------------------------------------------|

**Again, the goal of this project is not to reproduce a complete functional 
application but just a prototyping essai allowing flexible implementation of such a billing system.**

## Event Dispatcher Pattern

This use case can be implemented considering the following concepts:
 - A *Bill* is an object that the system creates and offers to other elements so 
 they can modify it before it’s actually printed to final customer
 - Every single billing rule has its own listener that tells a central dispatcher that 
 it wants to listen to a specific event, for example: *billing.night-and-weekend.call*
 - Depending on the recipient number from the phone calls log, at some point, 
 the Symfony2 kernel tells the dispatcher object to dispatch the right event (in this case *billing.night-and-weekend.call*), 
 passing with it an *Event* object that has access to the *Bill* object; 
 - The dispatcher notifies all listeners of the *billing.night-and-weekend.call*, 
 allowing each of them to make modifications to the *Bill* object.

### Using Event Subscribers

> The most common way to listen to an event is to register an event listener with the dispatcher. This listener can listen to one or more events and is notified each time those events are dispatched.
Another way to listen to events is via an event subscriber. An event subscriber is a PHP class that's able to tell the dispatcher exactly which events it should subscribe to. It implements theEventSubscriberInterface interface, which requires a single static method calledgetSubscribedEvents.

In this example, one rule was implemented using the listener pattern and the others using subscribers.

[More details](http://symfony.com/doc/master/components/event_dispatcher/introduction.html "The Event Dispatcher Component documentation")

## Existing App

Imagine we already have a built-in little MVC plain PHP functions to load calls log from a MySQL database and print a bill using simple template files.
`/app/model.php` contains functions directly calling MySQL
`/app/controllers.php` contains differents function that will be mapped with every request coming into your application.
`/app.index.php` is the front controller that loads the right object and print the right template from `/templates/*`

## System requirements

1.  Web Server
2.  PHP 5.3.2 (minimum)
3.  MySQL

## Instructions to Setup

1.  Run `/db.sql` in your database
2.  Update `open_database_connection()` located in model.php with your database information (by default; host=localhost, user=root, pass=,db=symfony-billing) 
3.  Create an alias pointing on the root folder of this project
4.  visit `http://localhost/your_alias/index.php`

## Event Dispatcher Usage

Event driven implementation is isolated under the [BillingBundle](https://github.com/fayway/sf2-Billing-EventDispatcher/tree/master/app/BillingBundle) folder, here are some key files:

* The Bill POJO ([Bill.php](https://github.com/fayway/sf2-Billing-EventDispatcher/blob/master/app/BillingBundle/Bill.php))

```php
class Bill 
{
    protected $call;
    protected $unit_price;
    protected $quantity;
    protected $currency = 'Dh';
    protected $remarks = array();
    
    public function getCall(){
        return $this->call;
    }
    public function setCall($call){
        $this->call = $call;
    }
    //other getters and setters
}
```

* Events name listing ([BillingEvents.php](https://github.com/fayway/sf2-Billing-EventDispatcher/blob/master/app/BillingBundle/BillingEvents.php))

```php  
final class BillingEvents 
{
    const onAnyCallBill                = 'billing.any.call';
    const onFreeCallBill               = 'billing.free.call';
    const onLandlineCallBill           = 'billing.landline.call';
    const onMobileCallBill             = 'billing.mobile.call';
    const onNightAndWeekendCallBill    = 'billing.night-and-weekend.call';
    const onAmericanCustomerCallBill   = 'billing.american-customer.call';
    const onInternationalCurrencyBill  = 'billing.international-currency.bill';
}
```

* The Event object ([FilterBillEvent.php](https://github.com/fayway/sf2-Billing-EventDispatcher/blob/master/app/BillingBundle/Event/FilterBillEvent.php))

It’s a subclass of the baseEvent object. This class contains methods such as getBill() and setBill(), allowing listeners/subscribers to get or even replace the Bill object.
```php  
class FilterBillEvent extends Event
{
    protected $bill;
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }
    public function getBill()
    {
        return $this->bill;
    }
    public function setBill($bill)
    {
        $this->bill = $bill;
    }
}
```

* A listener example ([FreedomListener.php](https://github.com/fayway/sf2-Billing-EventDispatcher/blob/master/app/BillingBundle/Event/FreedomListener.php))

```php 
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
```

* A Subscriber example ([DiscountSubscriber.php](https://github.com/fayway/sf2-Billing-EventDispatcher/blob/master/app/BillingBundle/Event/DiscountSubscriber.php))

```php 
class DiscountSubscriber implements EventSubscriberInterface
{
    protected $rate;
    public function __construct($rate = 0.5)
    {
        $this->rate = $rate;
    }
    static public function getSubscribedEvents()
    {
        return array(BillingEvents::onNightAndWeekendCallBill => array('onNightAndWeekendCallBill', 0),);
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
```

* The Dispatcher ([controllers.php](https://github.com/fayway/sf2-Billing-EventDispatcher/blob/master/app/controllers.php#L37))

In general only one single dispatcher is created

```php 
use Symfony\Component\EventDispatcher\EventDispatcher;
$dispatcher = new EventDispatcher();
```

To connect a listener (eg: FreedomListener):

```php 
$freeCallListener = new FreedomListener();
$dispatcher->addListener(BillingEvents::onFreeCallBill, array($freeCallListener, 'onFreeCall'));
```

Or to add a subscriber (eg: DiscountSubscriber):

```php 
$discountSubscriber = new DiscountSubscriber(0.5);
$dispatcher->addSubscriber($discountSubscriber);
```

* Dispatching events

```php   
if(preg_match('/0800.+/i', $call['recipient']))
{
    $dispatcher->dispatch(BillingEvents::onFreeCallBill, $event);
}

if( $call_day=='Sunday' || $call_day=='Saturday')
{
    $dispatcher->dispatch(BillingEvents::onNightAndWeekendCallBill, $event);
}
```

This notifies all listeners/subscribers of the given event. It also gives them access to the Bill object 
via  FilterBillEvent so they can add their own process to the default workflow.