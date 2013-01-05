/**
 * _.authorize.net.js
 *
 * Authorize.Net Recurring Billing xml interface jquery plug-in
 * Copyright (c) 2011 by Rick Edwards
 *
 * Note: This interface to Authorize.Net Recurring Billing relies upon my
 *      client-side xml generation library (_.jquery.js), which is a jQuery
 *      plugin. For schedule projection, my date math unit (_.date.js) comes
 *      highly recommended.
 **/

function xml(version,encoding){
    var x=_("xml",{version:version,encoding:encoding});
    return x.replace(/\<xml/g,"<?xml").replace(/ \/\>/g,"?>");
}
/**
 * The merchant’s valid API login ID [Up to 25 chars]
 * Submit the API login ID used to submit transactions.
 **/
var NAME="28YF5q4pFUr"; //TODO: Use actual Authorize.net [merchant] account name

/**
 * The merchant’s valid transaction key [16 chars]
 * Submit the transaction key obtained by the merchant from the Merchant Interface.
 **/
var TRANSACTION_KEY="67qhe99TBP6769rA"; //TODO: Actual Authorize.net transaction key

function MerchantAuthentication(){
    var merchantAuthentication=_("name",NAME)+
        _("transactionKey",TRANSACTION_KEY);
    return _("merchantAuthentication",merchantAuthentication);
}

function NameAndAddress(wrapper,firstName,lastName,company,address,city,state,
        zip,country){
    var nameAndAddress=_("firstName",firstName)+_("lastName",lastName);
    if(company)
        nameAndAddress+=_("company",company);
    if(address)
        nameAndAddress+=_("address",address);
    if(city)
        nameAndAddress+=_("city",city);
    if(state)
        nameAndAddress+=_("state",state);
    if(zip)
        nameAndAddress+=_("zip",zip);
    if(country)
        nameAndAddress+=_("country",country);
    return _(wrapper,nameAndAddress);
}

/**
 * refId: [optional] Merchant-assigned reference ID for the request [Up to 20
 *          chars]
 * name: [optional] Merchant-assigned name for the subscription [Up to 50 chars]
 * length: The measurement of time, in association with the Interval Unit,
 *          that is used to define the frequency of the billing occurrences
 *          [Up to 3 digits] 1..12 for unit="months", 7..365 for unit="days".
 * unit: The unit of time, in association with the Interval Length, between
 *          each billing occurrence ["days" or "months"].
 * startDate: The date the subscription begins (also the date the initial
 *          billing occurs) [in "yyyy-mm-dd" format] The date entered must be
 *          greater than or equal to the date the subscription was created.
 *          The validation checks against local server date, which is
 *          Mountain Time. An error might possibly occur if you try to submit
 *          a subscription from a time zone where the resulting date is
 *          different; for example, if you are in the Pacific time zone and
 *          try to submit a subscription between 11:00 PM and midnight, with
 *          a start date set for today.  Note: If the start date is the 31st,
 *          and the interval is monthly, the billing date is the last day of
 *          each month (even when the month does not have 31 days).
 * totalOccurrences: Number of billing occurrences or payments for the
 *          subscription [Up to 4 digits] To submit a subscription with no
 *          end date (an ongoing subscription), this field must be submitted
 *          with a value of “9999.” If a trial period is specified, this number
 *          should include the Trial Occurrences.
 * trialOccurrences: [optional] Number of billing occurrences or payments in the trial
 *          period [Up to 2 digits] If a trial period is specified, this number
 *          must be included in the Total Occurrences.
 * amount: The amount to be billed to the customer for each payment in the
 *          subscription [Up to 15 digits] If a trial period is specified, this
 *          is the amount that will be charged after the trial payments are
 *          completed.
 * trialAmount: [conditional] The amount to be charged for each payment during a trial period
 *          [Up to 15 digits] Required when trial occurrences is specified.
 *          Once the number of trial occurrences for the subscription is
 *          complete, the regular amount will be charged for each remaining
 *          payment.
 * cardNumber: The credit card number used for payment of the subscription
 *          [13 to 16 digits]
 * expirationDate: The expiration date of the credit card used for the
 *          subscription [in yyyy-mm format]
 * cardCode: [optional] The 3- or 4-digit card code on the back of most credit
 *          cards, on the front for American Express. [3 or 4 digits] This
 *          element should only be included when the merchant has set the card
 *          code value field to required in the account settings. The value
 *          itself is never validated.
 * [all bankAccount parameters omitted for now]
 * invoiceNumber: [optional] Merchant-assigned invoice number for the subscription
 *          [Up to 20 chars] The invoice number will be associated with
 *          each payment in the subscription.
 * description: [optional] Description of the subscription [Up to 255 chars] The
 *          description will be associated with each payment in the
 *          subscription.
 * id: [optional] Merchant-assigned identifier for the customer [Up to 20 chars]
 * email: [optional] The customer’s email address [Up to 255 chars]
 * phoneNumber: [optional] The customer’s phone number [Up to 25 digits]
 * faxNumber: [optional] The customer’s fax number [Up to 25 digits]
 * bill_firstName: The first name associated with the customer’s billing address
 *          [Up to 50 chars]
 * bill_lastName: The last name associated with the customer’s billing address
 *          [Up to 50 chars]
 * bill_company: [optional] The company associated with the customer’s billing address
 *          [Up to 50 chars]
 * bill_address: [optional] The customer’s billing address [Up to 60 chars]
 * bill_city: [optional] The city of the customer’s billing address [Up to 40 chars]
 * bill_state: [optional] The state of the customer’s billing address [2 chars] Must
 *          be a valid state code
 * bill_zip: [optional] The ZIP code of the customer’s billing address [Up to 20 chars]
 * bill_country: [optional] The country of the customer’s billing address [2 chars or up
 *          to 60 chars] Must be a valid 2-char country code or full name.
 * [All shipTo elements are optional..]
 * ship_firstName: The first name associated with the customer’s shipping
 *          address [Up to 50 chars]
 * ship_lastName: The last name associated with the customer’s shipping address
 *          [Up to 50 chars]
 * ship_company: The company associated with the customer’s shipping address
 *          [Up to 50 chars]
 * ship_address: The customer’s shipping address [Up to 60 chars]
 * ship_city: The city of the customer’s shipping address [Up to 40 chars]
 * ship_state: The state of the customer’s shipping address [2 chars] Must
 *          be a valid state code
 * ship_zip: The ZIP code of the customer’s shipping address [Up to 20 chars]
 * ship_country: The country of the customer’s shipping address [2 chars or up
 *          to 60 chars] Must be a valid 2-char country code or full name.
 **/
function ARBCreateSubscriptionRequest(refId,name,length,unit,startDate,
        totalOccurrences,trialOccurrences,amount,trialAmount,cardNumber,
        expirationDate,cardCode/*,accountType,routingNumber,accountNumber,
        nameOnAccount,echeckType,bankName*/,invoiceNumber,description,id,email,
        phoneNumber,faxNumber,bill_firstName,bill_lastName,bill_company,
        bill_address,bill_city,bill_state,bill_zip,bill_country,ship_firstName,
        ship_lastName,ship_company,ship_address,ship_city,ship_state,ship_zip,
        ship_country){
    var interval=_("length",length)+_("unit",unit);
    //interval+=_("startDate",startDate);
    interval+=_("totalOccurrences",totalOccurrences);
    if(trialOccurrences)
        interval+=_("trialOccurrences",trialOccurrences);
    var creditCard=_("cardNumber",cardNumber)+
        _("expirationDate",expirationDate);
    if(cardCode)
        creditCard+=_("cardCode",cardCode);
    creditCard=_("creditCard",creditCard);
    //var bankAccount=_("accountType",accountType)+_("routingNumber",routingNumber)+
    //    _("accountNumber",accountNumber)+_("nameOnAccount",nameOnAccount)+
    //    _("echeckType",echeckType)+_("bankName",bankName);
    //bankAccount=_("bankAccount",bankAccount);
    var payment=creditCard/*+bankAccount*/;
    var order="";
    if(invoiceNumber)
        order+=_("invoiceNumber",invoiceNumber);
    if(description)
        order+=_("description",description);
    var customer="";
    if(id)
        customer+=_("id",id);
    if(email)
        customer+=_("email",email);
    if(phoneNumber)
        customer+=_("phoneNumber",phoneNumber);
    if(faxNumber)
        customer+=_("faxNumber",faxNumber);
    var paymentSchedule=_("interval",interval)+_("amount",amount);
    if(trialOccurrences)
        paymentSchedule+=_("trialAmount",trialAmount);
    paymentSchedule+=_("payment",payment);
    if(order.length>0)
        paymentSchedule+=_("order",order);
    paymentSchedule+=_("customer",customer);
    paymentSchedule+=NameAndAddress("billTo",bill_firstName,bill_lastName,
        bill_company,bill_address,bill_city,bill_state,bill_zip,bill_country);
    paymentSchedule+=NameAndAddress("shipTo",ship_firstName,ship_lastName,
        ship_company,ship_address,ship_city,ship_state,ship_zip,ship_country);
    paymentSchedule=_("paymentSchedule",paymentSchedule);
    var subscription=_("name",name)+paymentSchedule;
    subscription=_("subscription",subscription);
    return xml("1.0","utf-8")+_("ARBCreateSubscriptionRequest",
        {xmlns:"AnetApi/xml/v1/schema/AnetApiSchema.xsd"},
        MerchantAuthentication()+_("refId",refId)+subscription);
}

/**
 * Same params as above, plus...
 * subscriptionId: The payment gateway assigned identification number for the
 *      subscription [Up to 13 digits]
 **/
function ARBUpdateSubscriptionRequest(refId,name,length,unit,startDate,
        totalOccurrences,trialOccurrences,amount,trialAmount,cardNumber,
        expirationDate,cardCode/*,accountType,routingNumber,accountNumber,
        nameOnAccount,echeckType,bankName*/,invoiceNumber,description,id,email,
        phoneNumber,faxNumber,bill_firstName,bill_lastName,bill_company,
        bill_address,bill_city,bill_state,bill_zip,bill_country,ship_firstName,
        ship_lastName,ship_company,ship_address,ship_city,ship_state,ship_zip,
        ship_country,subscriptionId){
    var interval=_("length",length)+_("unit",unit)+_("startDate",startDate);
    if(trialOccurrences)
        interval+=_("totalOccurrences",totalOccurrences)+
            _("trialOccurrences",trialOccurrences);
    var creditCard=_("cardNumber",cardNumber)+
        _("expirationDate",expirationDate);
    if(cardCode)
        creditCard+=_("cardCode",cardCode);
    creditCard=_("creditCard",creditCard);
    //var bankAccount=_("accountType",accountType)+_("routingNumber",routingNumber)+
    //    _("accountNumber",accountNumber)+_("nameOnAccount",nameOnAccount)+
    //    _("echeckType",echeckType)+_("bankName",bankName);
    //bankAccount=_("bankAccount",bankAccount);
    var payment=creditCard/*+bankAccount*/;
    var order="";
    if(invoiceNumber)
        order+=_("invoiceNumber",invoiceNumber);
    if(description)
        order+=_("description",description);
    var customer="";
    if(id)
        customer+=_("id",id);
    if(email)
        customer+=_("email",email);
    if(phoneNumber)
        customer+=_("phoneNumber",phoneNumber);
    if(faxNumber)
        customer+=_("faxNumber",faxNumber);
    var paymentSchedule=_("interval",interval)+_("amount",amount);
    if(trialOccurrences)
        paymentSchedule+=_("trialAmount",trialAmount);
    paymentSchedule+=_("payment",payment);
    if(order.length>0)
        paymentSchedule+=_("order",order);
    paymentSchedule+=_("customer",customer);
    paymentSchedule+=NameAndAddress("billTo",bill_firstName,bill_lastName,
        bill_company,bill_address,bill_city,bill_state,bill_zip,bill_country);
    paymentSchedule+=NameAndAddress("shipTo",ship_firstName,ship_lastName,
        ship_company,ship_address,ship_city,ship_state,ship_zip,ship_country);
    paymentSchedule=_("paymentSchedule",paymentSchedule);
    var subscription=_("name",name)+paymentSchedule;
    subscription=_("subscription",subscription);
    return xml("1.0","utf-8")+_("ARBUpdateSubscriptionRequest",
        {xmlns:"AnetApi/xml/v1/schema/AnetApiSchema.xsd"},
        MerchantAuthentication()+_("refId",refId)+_("subscriptionId",subscriptionId)+
        subscription);
}

/**
 * see params above
 **/
function ARBGetSubscriptionStatusRequest(refId,subscriptionId){
    return xml("1.0","utf-8")+_("ARBGetSubscriptionStatusRequest",
        {xmlns:"AnetApi/xml/v1/schema/AnetApiSchema.xsd"},
        MerchantAuthentication()+_("refId",refId)+_("subscriptionId",subscriptionId));
}

/**
 * see params above
 **/
function ARBCancelSubscriptionRequest(refId,subscriptionId){
    return xml("1.0","utf-8")+_("ARBCancelSubscriptionRequest",
        {xmlns:"AnetApi/xml/v1/schema/AnetApiSchema.xsd"},
        MerchantAuthentication()+_("refId",refId)+_("subscriptionId",subscriptionId));
}

/**
 * Defines ARB response format
 **/
function ARBCreateSubscriptionResponse(refId,resultCode,code,text,status,subscriptionId){
    var message=_("code",code)+_("text",text);
    var messages=_("resultCode",resultCode)+_("message",message)+_("Status",Status);
    messages=_("messages",messages);
    return xml("1.0","utf-8")+_("ARBCreateSubscriptionResponse",
        {xmlns:"AnetApi/xml/v1/schema/AnetApiSchema.xsd"},
        MerchantAuthentication()+_("refId",refId)+messages+_("subscriptionId",subscriptionId));
}
