function ARBCreateSubscriptionRequest(merchant_name, merchant_transactionKey,
				      order_number, subscription_name,
				      interval_length, interval_unit, startDate,
				      totalOccurrences, total, cc_num,
				      expirationDate, cc_first_name,
				      cc_last_name, bill_address1,
				      bill_address2, bill_city, bill_state,
				      bill_zip, bill_country, ship_first_name,
				      ship_last_name, ship_address1,
				      ship_address2, ship_city, ship_state,
				      ship_zip, ship_country) {
    return _('ARBCreateSubscriptionRequest', 'xmlns',
	     'AnetApi/xml/v1/schema/AnetApiSchema.xsd',
	     _('merchantAuthentication',
	       _('name', merchant_name) +
	       _('transactionKey', merchant_transactionKey)) +
	     _('refId', order_number) +
	     _('subscription',
	       _('name', subscription_name) +
	       _('paymentSchedule',
		 _('interval',
		   _('length', interval_length) +
		   _('unit', interval_unit)) +
		 _('startDate', startDate) +
		 _('totalOccurrences', totalOccurrences)) +
	       _('amount', total) +
	       _('payment',
		 _('creditCard',
		   _('cardNumber', cc_num) +
		   _('expirationDate', expirationDate))) +
	       _('billTo',
		 _('firstName', cc_first_name) +
		 _('lastName', cc_last_name) +
		 _('address', bill_address1 + ' ' + bill_address2) +
		 _('city', bill_city) +
		 _('state', bill_state) +
		 _('zip', bill_zip) +
		 _('country', bill_country)) +
	       _('shipTo',
		 _('firstName', ship_first_name) +
		 _('lastName', ship_last_name) +
		 _('address', ship_address1 + ' ' + ship_address2) +
		 _('city', ship_city) +
		 _('state', ship_state) +
		 _('zip', ship_zip) +
		 _('country', ship_country))));
}

function ARBUpdateSubscriptionRequest (merchant_name, merchant_transactionKey,
				       order_number, subscriptionId, subscription_name,
				       startDate, totalOccurrences, total,
				       cc_num, expirationDate,
				       bill_firstName, bill_lastName,
				       bill_address, bill_city, bill_state,
				       bill_zip, bill_country, ship_firstName,
				       ship_lastName, ship_address, ship_city,
				       ship_state, ship_zip, ship_country){
    return _('ARBUpdateSubscriptionRequest', 'xmlns',
	     'AnetApi/xml/v1/schema/AnetApiSchema.xsd',
	     _('merchantAuthentication',
	       _('name', merchant_name)+
	       _('transactionKey', merchant_transactionKey))+
	     _('refId', order_number)+
	     _('subscriptionId', subscriptionId)+
	     _('subscription',
	       _('name', subscription_name)+
	       _('paymentSchedule',
		 _('startDate', startDate)+
		 _('totalOccurrences', totalOccurrences))+
	       _('amount', total)+
	       _('payment',
		 _('creditCard',
		   _('cardNumber', cc_num)+
		   _('expirationDate', expirationDate)))+
	       _('billTo',
		 _('firstName', bill_firstName)+
		 _('lastName', bill_lastName)+
		 _('address', bill_address)+
		 _('city', bill_city)+
		 _('state', bill_state)+
		 _('zip', bill_zip)+
		 _('country', bill_country))+
	       _('shipTo',
		 _('firstName', ship_firstName)+
		 _('lastName', ship_lastName)+
		 _('address', ship_address)+
		 _('city', ship_city)+
		 _('state', ship_state)+
		 _('zip', ship_zip)+
		 _('country', ship_country))));
}

function ARBCancelSubscriptionRequest (merchant_name, merchant_transactionKey,
				       refId, subscriptionId){
    return _('ARBCancelSubscriptionRequest', 'xmlns',
	     'AnetApi/xml/v1/schema/AnetApiSchema.xsd',
	     _('merchantAuthentication',
	       _('name', merchant_name)+
	       _('transactionKey', merchant_transactionKey))+
	     _('refId', refId)+
	     _('subscriptionId', subscriptionId));
}
