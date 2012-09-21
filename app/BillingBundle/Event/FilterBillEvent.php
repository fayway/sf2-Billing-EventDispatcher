<?php
namespace BillingBundle\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use BillingBundle\Bill;

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