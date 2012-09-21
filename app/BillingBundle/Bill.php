<?php
namespace BillingBundle;

class Bill 
{
    protected $call;
    protected $unit_price;
    protected $quantity;
    protected $currency = '€';
    protected $remarks = array();
    
    public function getCall(){
        return $this->call;
    }
    public function setCall($call){
        $this->call = $call;
    }
    public function getUnitPrice(){
        return $this->unit_price;
    }
    public function setUnitPrice($unit_price){
        $this->unit_price = $unit_price;
    }
    public function getQuantity(){
        return $this->quantity;
    }
    public function setQuantity($quantity){
        $this->quantity = $quantity;
    }
    public function getCurrency(){
        return $this->currency;
    }
    public function setCurrency($currency){
        $this->currency = $currency;
    }
    public function getRemarks(){
        return $this->remarks;
    }
    public function addRemark($remark){
        $this->remarks[] = $remark;
    }
}