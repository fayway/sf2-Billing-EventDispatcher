<?php
namespace BillingBundle;

final class BillingEvents 
{
    const onAnyCallBill                  = 'billing.any.call';
    const onFreeCallBill                 = 'billing.free.call';
    const onLandlineCallBill             = 'billing.landline.call';
    const onMobileCallBill               = 'billing.mobile.call';
    const onNightAndWeekendCallBill      = 'billing.night-and-weekend.call';
    const onAmericanCustomerCallBill     = 'billing.american-customer.call';
    const onInternationalBill    = 'billing.international.bill';
}