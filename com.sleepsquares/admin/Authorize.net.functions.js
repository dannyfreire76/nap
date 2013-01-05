/**
 * Authorize.net.functions.js
 * rip'd from https://www.itdevworks.com/tools/AuthnetXmlApiTester/AnetXmlApiTester.asp  ;)
 **/

var MONTH_NAMES = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var DAY_NAMES = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var g_spaces = "                                                                                ";
var g_DefaultRequestText = "<enter your request xml here>";
var g_fillerDataMap;
var g_fUserFillerData = false;

InitializeFillerDataMaps();

function DoClearResponse() {
    var ctl = document.getElementById("responseText");
    if (null != ctl) {
	ctl.value = "";
    }
}

function DoClearAll() {
    DoClearResponse();
    var ctl = document.getElementById("requestText");
    if (null != ctl) {
	ctl.value = g_DefaultRequestText;
	ctl.select();
    }
}

function ClearInstructions() {
    var ctlRequest = document.getElementById("requestText");
    if (null != ctlRequest) {
	if (!IsRequestTextDirty(ctlRequest)) {
	    ctlRequest.value = "";
	}
    }
}

function RestoreInstructions() {
    var ctlRequest = document.getElementById("requestText");
    if (null != ctlRequest) {
	if (!IsRequestTextDirty(ctlRequest)) {
	    ctlRequest.value = g_DefaultRequestText;
	    ctlRequest.select();
	}
    }
}

function IsRequestTextDirty(ctlRequest) {
    var isDirty = true;
    if (null != ctlRequest) {
	var strRequestText = ctlRequest.value;
	isDirty = false;
	if (strRequestText.length > 0 && strRequestText != g_DefaultRequestText) {
	    isDirty = true;
	}
    }
    return isDirty;
}

function InsertTemplate() {
    var ctlTemplates = document.getElementById("insertTemplate");
    var ctlApiLogin = document.getElementById("apiLoginText");
    var ctlTransactionKey = document.getElementById("transKey");
    var ctlRequest = document.getElementById("requestText");
    var ctlUseFiller = document.getElementById("useFillerData");
    if (null != ctlUseFiller) {
	g_fUserFillerData = ctlUseFiller.checked;
    }
    if (null != ctlTemplates && null != ctlApiLogin && null != ctlTransactionKey && null != ctlRequest) {
	var confirmed = true;
	if (IsRequestTextDirty(ctlRequest)) {
	    confirmed = confirm("Overwrite current request text with new, blank template?");
	}
	if (confirmed) {
	    var str;
	    var chosenValue = parseInt(ctlTemplates.value);
	    var strApiLogin;
	    var strTransactionKey;

	    strApiLogin = ctlApiLogin.value;
	    strTransactionKey = ctlTransactionKey.value;

	    switch (chosenValue) {
	    case 101:
		str = MakeCreateCustomerProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 301:
		str = MakeCreateCustomerProfileWithPaymentProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 302:
		str = MakeCreateCustomerProfileWithShippingAddressRequest(strApiLogin, strTransactionKey);
		break;
	    case 303:
		str = MakeCreateCustomerProfileWithPaymentAndShippingAddressRequest(strApiLogin, strTransactionKey);
		break;
	    case 103:
		str = MakeCreateCustomerPaymentProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 104:
		str = MakeCreateCustomerShippingAddressRequest(strApiLogin, strTransactionKey);
		break;
	    case 105:
		str = MakeCreateCustomerProfileTransactionRequest(strApiLogin, strTransactionKey);
		break;
	    case 106:
		str = MakeDeleteCustomerProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 107:
		str = MakeDeleteCustomerPaymentProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 108:
		str = MakeDeleteCustomerShippingAddressRequest(strApiLogin, strTransactionKey);
		break;
	    case 109:
		str = MakeGetCustomerProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 110:
		str = MakeGetCustomerPaymentProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 111:
		str = MakeGetCustomerShippingAddress(strApiLogin, strTransactionKey);
		break;
	    case 112:
		str = MakeUpdateCustomerProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 113:
		str = MakeUpdateCustomerPaymentProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 114:
		str = MakeUpdateCustomerShippingAddressRequest(strApiLogin, strTransactionKey);
		break;
	    case 115:
		str = MakeValidateCustomerPaymentProfileRequest(strApiLogin, strTransactionKey);
		break;
	    case 116:
		str = MakeGetCustomerProfileIdsRequest(strApiLogin, strTransactionKey);
		break;
	    case 201:
		str = MakeARBCreateSubscriptionRequest(strApiLogin, strTransactionKey);
		break;
	    case 202:
		str = MakeARBUpdateSubscriptionRequest(strApiLogin, strTransactionKey);
		break;
	    case 203:
		str = MakeARBCancelSubscriptionRequest(strApiLogin, strTransactionKey);
		break;
	    default:
		break;
	    }

	    if (str.length > 0) {
		ctlRequest.value = str;
	    }
	}
	ctlTemplates.children[0].selected = true;
    }
}

// ---------------------------------------------------------------------------
// function MakeARBCreateSubscriptionRequest
//
// Parameters
//   strApiLogin
//   strTransactionKey
//
// Returns   The <createSubscriptionRequest> XML text
// ---------------------------------------------------------------------------
//
//  <refId>Sample</refId>
//  <subscription>
//      <name>Sample subscription</name>
//      <paymentSchedule>
//          <interval>
//              <length>1</length>
//              <unit>months</unit>
//          </interval>
//          <startDate>2007-03-15</startDate>
//          <totalOccurrences>12</totalOccurrences>
//          <trialOccurrences>1</trialOccurrences>
//      </paymentSchedule>
//      <amount>10.29</amount>
//      <trialAmount>0.00</trialAmount>
//      <payment>
//          <creditCard>
//              <cardNumber>4111111111111111</cardNumber>
//              <expirationDate>2008-08</expirationDate>
//          </creditCard>
//      </payment>
//      <billTo>
//          <firstName>John</firstName>
//          <lastName>Smith</lastName>
//      </billTo>
//  </subscription>
// ---------------------------------------------------------------------------


function MakeARBCreateSubscriptionRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("ARBCreateSubscriptionRequest", strApiLogin, strTransactionKey, WrapWithXmlComments(2, "\n       * Remove all elements you do not want to include in this subscription.\n  ") + "\n" + MakeEmptyElementNL(2, "refId") + WrapWithElementMultiLine(2, "subscription", "", "\n" + MakeEmptyElementNL(4, "name") + WrapWithElementMultiLine(4, "paymentSchedule", "", "\n" + WrapWithElementMultiLine(6, "interval", "", "\n" + MakeEmptyElementNL(8, "length") + MakeEmptyElementNLWithEolComment(8, "unit", "days, months")) + "\n" + MakeEmptyElementNL(6, "startDate") + MakeEmptyElementNL(6, "totalOccurrences") + MakeEmptyElementNL(6, "trialOccurrences")) + "\n" + MakeEmptyElementNL(4, "amount") + MakeEmptyElementNL(4, "trialAmount") + WrapWithElementMultiLine(4, "payment", "", "\n" + WrapWithXmlComments(6, "\n           * Use either creditCard or bankAccount elements, but not both\n      ") + "\n" + MakeCreditCardPattern(6) + "\n" + WrapWithXmlComments(0, "\n" + MakeBankAccountPattern(6) + "\n") + "\n") + "\n" + MakeARBAddressPattern(4, "billTo") + "\n")) + "\n";
}

// ---------------------------------------------------------------------------
// function MakeARBUpdateSubscriptionRequest
//
// Parameters
//   strApiLogin
//   strTransactionKey
//
// Returns   The <ARBUpdateSubscriptionRequest> XML text
// ---------------------------------------------------------------------------


function MakeARBUpdateSubscriptionRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("ARBUpdateSubscriptionRequest", strApiLogin, strTransactionKey, WrapWithXmlComments(2, "\n       * Remove all elements you do not want to update.\n  ") + "\n" + MakeEmptyElementNL(2, "refId") + MakeEmptyElementNL(2, "subscriptionId") + WrapWithElementMultiLine(2, "subscription", "", "\n" + MakeEmptyElementNL(4, "name") + WrapWithElementMultiLine(4, "paymentSchedule", "", "\n" + MakeEmptyElementNL(6, "startDate") + MakeEmptyElementNL(6, "totalOccurrences") + MakeEmptyElementNL(6, "trialOccurrences")) + "\n" + MakeEmptyElementNL(4, "amount") + MakeEmptyElementNL(4, "trialAmount") + WrapWithElementMultiLine(4, "payment", "", "\n" + WrapWithXmlComments(6, "\n           * Use either creditCard or bankAccount elements, but not both\n      ") + "\n" + MakeCreditCardPattern(6) + "\n" + WrapWithXmlComments(0, "\n" + MakeBankAccountPattern(6) + "\n") + "\n") + "\n" + MakeARBAddressPattern(4, "billTo") + "\n")) + "\n";
}

// ---------------------------------------------------------------------------
// function MakeARBCancelSubscriptionRequest
//
// Parameters
//   strApiLogin
//   strTransactionKey
//
// Returns   The <ARBCancelSubscriptionRequest> XML text
// ---------------------------------------------------------------------------


function MakeARBCancelSubscriptionRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("ARBCancelSubscriptionRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "refId") + MakeEmptyElementNL(2, "subscriptionId"));
}

// ---------------------------------------------------------------------------
// function MakeCreateCustomerProfileRequest
//
// Parameters
//   strApiLogin
//   strTransactionKey
//
// Returns   The <createCustomerProfileRequest> XML text
// ---------------------------------------------------------------------------
//
// <?xml version="1.0" encoding="utf-8"?>
// <createCustomerProfileRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
//   <merchantAuthentication>
//      <name>API Login ID here</name>
//      <transactionKey>Transaction Key here</transactionKey>
//   </merchantAuthentication>
//   <profile>
//      <merchantCustomerId>Merchant Customer ID here</merchantCustomerId>
//      <description>Profile description here</description>
//      <email>customer profile email address here</email>
//      <paymentProfiles>
//          <customerType>individual</customerType>
//          <payment>
//              <creditCard>
//                  <cardNumber>Credit card number here</cardNumber>
//                  <expirationDate>Credit card expiration date here</expirationDate>
//              </creditCard>
//          </payment>
//      </paymentProfiles>
//   </profile>
//   <validationMode>liveMode</validationMode>
// </createCustomerProfileRequest>
//


function MakeCreateCustomerProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerProfileRequest", strApiLogin, strTransactionKey, WrapWithElementMultiLine(2, "profile", "", "\n" + MakeEmptyElementNL(4, "merchantCustomerId") + MakeEmptyElementNL(4, "description") + MakeEmptyElementNL(4, "email")));
}

function MakeCreateCustomerProfileWithPaymentProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerProfileRequest", strApiLogin, strTransactionKey, WrapWithElementMultiLine(2, "profile", "", "\n" + MakeEmptyElementNL(4, "merchantCustomerId") + MakeEmptyElementNL(4, "description") + MakeEmptyElementNL(4, "email") + MakePaymentProfilePattern(4, "paymentProfiles") + "\n") + "\n" + MakeEmptyElementNL(2, "validationMode"));
}

function MakeCreateCustomerProfileWithShippingAddressRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerProfileRequest", strApiLogin, strTransactionKey, WrapWithElementMultiLine(2, "profile", "", "\n" + MakeEmptyElementNL(4, "merchantCustomerId") + MakeEmptyElementNL(4, "description") + MakeEmptyElementNL(4, "email") + MakeCIMAddressPattern(4, "shipToList") + "\n"));
}

function MakeCreateCustomerProfileWithPaymentAndShippingAddressRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerProfileRequest", strApiLogin, strTransactionKey, WrapWithElementMultiLine(2, "profile", "", "\n" + MakeEmptyElementNL(4, "merchantCustomerId") + MakeEmptyElementNL(4, "description") + MakeEmptyElementNL(4, "email") + MakePaymentProfilePattern(4, "paymentProfiles") + "\n" + MakeCIMAddressPattern(4, "shipToList") + "\n") + MakeEmptyElementNL(2, "validationMode"));
}

function MakeCreateCustomerPaymentProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerPaymentProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakePaymentProfilePattern(4, "paymentProfile"));

    //<customerProfileId>5141</customerProfileId>
    //<paymentProfile>
    //  <customerType>individual</customerType>
    //  <billTo/>
    //  <payment>
    //    <creditCard>
    //      <cardNumber>4111111111112222</cardNumber>
    //      <expirationDate>2010-02</expirationDate>
    //    </creditCard>
    //  </payment>
    //</paymentProfile>
}

function MakeCreateCustomerShippingAddressRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerShippingAddressRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakeCIMAddressPattern(2, "address"));

    //<customerProfileId>5141</customerProfileId>
    //<address>
    //  <firstName>George</firstName>
    //  <lastName>Washington</lastName>
    //  <company>Mount Vernon Cherrywood Supply</company>
    //  <address>3200 Mount Vernon Memorial Highway</address>
    //  <city>Mount Vernon</city>
    //  <state>VA</state>
    //  <zip>22121</zip>
    //  <country>United States</country>
    //  <phoneNumber>703-780-2000</phoneNumber>
    //</address>
}

function MakeCreateCustomerProfileTransactionRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("createCustomerProfileTransactionRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "refId") + WrapWithElementMultiLine(2, "transaction", "", "\n" + WrapWithElementMultiLine(4, "profileTransAuthCapture", "", "\n" + MakeEmptyElementNL(6, "amount") + WrapWithElementMultiLine(6, "tax", "", "\n" + MakeEmptyElementNL(8, "amount") + MakeEmptyElementNL(8, "name") + MakeEmptyElementNL(8, "description")) + "\n" + WrapWithElementMultiLine(6, "shipping", "", "\n" + MakeEmptyElementNL(8, "amount") + MakeEmptyElementNL(8, "name") + MakeEmptyElementNL(8, "description")) + "\n" + WrapWithElementMultiLine(6, "duty", "", "\n" + MakeEmptyElementNL(8, "amount") + MakeEmptyElementNL(8, "name") + MakeEmptyElementNL(8, "description")) + "\n" + WrapWithElementMultiLine(6, "lineItems", "", "\n" + MakeEmptyElementNL(8, "itemId") + MakeEmptyElementNL(8, "name") + MakeEmptyElementNL(8, "description") + MakeEmptyElementNL(8, "quantity") + MakeEmptyElementNL(8, "unitPrice") + MakeEmptyElementNL(8, "taxable")) + "\n" + WrapWithElementMultiLine(6, "lineItems", "", "\n" + MakeEmptyElementNL(8, "itemId") + MakeEmptyElementNL(8, "name") + MakeEmptyElementNL(8, "description") + MakeEmptyElementNL(8, "quantity") + MakeEmptyElementNL(8, "unitPrice") + MakeEmptyElementNL(8, "taxable")) + "\n" + MakeEmptyElementNL(6, "customerProfileId") + MakeEmptyElementNL(6, "customerPaymentProfileId") + MakeEmptyElementNL(6, "customerShippingAddressId") + WrapWithElementMultiLine(6, "order", "", "\n" + MakeEmptyElementNL(8, "invoiceNumber") + MakeEmptyElementNL(8, "description") + MakeEmptyElementNL(8, "purchaseOrderNumber")) + "\n" + MakeEmptyElementNL(6, "taxExempt") + MakeEmptyElementNL(6, "recurringBilling") + MakeEmptyElementNL(6, "cardCode")) + "\n") + "\n" + MakeEmptyElement(2, "extraOptions"));
    //<refId>ref001</refId>
    //<transaction>
    //  <profileTransAuthCapture>
    //    <amount>12264.386</amount>
    //    <lineItems>
    //      <itemId>Item001.1</itemId>
    //      <name>Line Item 001.1</name>
    //      <description>Description for Line Item 001.1</description>
    //      <quantity>1</quantity>
    //      <unitPrice>299.99</unitPrice>
    //      <taxable>true</taxable>
    //    </lineItems>
    //    <lineItems>
    //      <itemId>Item001.2</itemId>
    //      <name>Line Item 001.2</name>
    //      <description>Description for Line Item 001.2</description>
    //      <quantity>1</quantity>
    //      <unitPrice>29.95</unitPrice>
    //      <taxable>true</taxable>
    //    </lineItems>
    //    <lineItems>
    //      <itemId>Item001.3</itemId>
    //      <name>Line Item 001.3</name>
    //      <description>Description for Line Item 001.3</description>
    //      <quantity>1</quantity>
    //      <unitPrice>6125</unitPrice>
    //      <taxable>true</taxable>
    //    </lineItems>
    //    <customerProfileId>5141</customerProfileId>
    //    <customerPaymentProfileId>3871</customerPaymentProfileId>
    //    <customerShippingAddressId>3871</customerShippingAddressId>
    //  </profileTransAuthCapture>
    //</transaction>
}

function MakeDeleteCustomerProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("deleteCustomerProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElement(2, "customerProfileId"));
    //<customerProfileId>5141</customerProfileId>
}

function MakeDeleteCustomerPaymentProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("deleteCustomerPaymentProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakeEmptyElement(2, "customerPaymentProfileId"));
    //<customerProfileId>5141</customerProfileId>
    //<customerPaymentProfileId>3871</customerPaymentProfileId>
}

function MakeDeleteCustomerShippingAddressRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("deleteCustomerShippingAddressRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakeEmptyElement(2, "customerAddressId"));
    //<customerProfileId>5141</customerProfileId>
    //<customerAddressId>3871</customerAddressId>
}

function MakeGetCustomerProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("getCustomerProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElement(2, "customerProfileId"));
    //<customerProfileId>5141</customerProfileId>
}

function MakeGetCustomerProfileIdsRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("getCustomerProfileIdsRequest", strApiLogin, strTransactionKey, '');
}

function MakeGetCustomerPaymentProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("getCustomerPaymentProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakeEmptyElement(2, "customerPaymentProfileId"));
    //<customerProfileId>5141</customerProfileId>
    //<customerPaymentProfileId>3871</customerPaymentProfileId>
}

function MakeGetCustomerShippingAddress(strApiLogin, strTransactionKey) {
    return MakeRequestXml("getCustomerShippingAddress", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakeEmptyElement(2, "customerAddressId"));
    //<customerProfileId>5141</customerProfileId>
    //<customerAddressId>3871</customerAddressId>
}

function MakeUpdateCustomerProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("updateCustomerProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + WrapWithElementMultiLine(2, "profile", "", "\n" + MakeEmptyElementNL(4, "merchantCustomerId") + MakeEmptyElementNL(4, "description") + MakeEmptyElementNL(4, "email")));
    //<customerProfileId>5141</customerProfileId>
    //<profile>
    //  <merchantCustomerId>MerchantID_43</merchantCustomerId>
    //  <description>This is Merchant 43</description>
    //  <email>merch43@somemerchant.com</email>
    //  <customerProfileId>5141</customerProfileId>
    //</profile>
}

function MakeUpdateCustomerPaymentProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("updateCustomerPaymentProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + WrapWithElementMultiLine(2, "paymentProfile", "", "\n" + MakeEmptyElementNLWithEolComment(4, "customerType", "individual or business") + MakeEmptyElementNL(4, "billTo") + WrapWithElementMultiLine(4, "payment", "", "\n" + WrapWithElementMultiLine(6, "creditCard", "", "\n" + MakeEmptyElementNL(8, "cardNumber") + MakeEmptyElementNL(8, "expirationDate")) + "\n") + "\n" + MakeEmptyElement(4, "customerPaymentProfileId") + "\n"));
    //<customerProfileId>5141</customerProfileId>
    //<paymentProfile>
    //  <customerType>individual</customerType>
    //  <billTo/>
    //  <payment>
    //    <creditCard>
    //      <cardNumber>4111111111113333</cardNumber>
    //      <expirationDate>2010-03</expirationDate>
    //    </creditCard>
    //  </payment>
    //  <customerPaymentProfileId>03</customerPaymentProfileId>
    //</paymentProfile>
}

function MakeUpdateCustomerShippingAddressRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("updateCustomerShippingAddressRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + WrapWithElementMultiLine(2, "address", "", "\n" + MakeEmptyElementNL(4, "firstName") + MakeEmptyElementNL(4, "lastName") + MakeEmptyElementNL(4, "company") + MakeEmptyElementNL(4, "address") + MakeEmptyElementNL(4, "city") + MakeEmptyElementNL(4, "state") + MakeEmptyElementNL(4, "zip") + MakeEmptyElementNL(4, "country") + MakeEmptyElementNL(4, "phoneNumber") + MakeEmptyElementNL(4, "customerAddressId")));
    //<customerProfileId>5141</customerProfileId>
    //<address>
    //  <firstName>George</firstName>
    //  <lastName>Washington</lastName>
    //  <company>Mount Vernon Cherrywood Supply</company>
    //  <address>3200 Mount Vernon Memorial Highway</address>
    //  <city>Mount Vernon</city>
    //  <state>VA</state>
    //  <zip>22121</zip>
    //  <country>United States</country>
    //  <phoneNumber>703-780-2000</phoneNumber>
    //  <customerAddressId>3871</customerAddressId>
    //</address>
}

function MakeValidateCustomerPaymentProfileRequest(strApiLogin, strTransactionKey) {
    return MakeRequestXml("validateCustomerPaymentProfileRequest", strApiLogin, strTransactionKey, MakeEmptyElementNL(2, "customerProfileId") + MakeEmptyElementNL(2, "customerPaymentProfileId") + MakeEmptyElementNL(2, "customerShippingAddressId") + MakeEmptyElement(2, "validationMode"));
    //<customerProfileId>5141</customerProfileId>
    //<customerPaymentProfileId>3871</customerPaymentProfileId>
    //<customerShippingAddressId>3871</customerShippingAddressId>
    //<validationMode>liveMode</validationMode>
}

// **************************************************************************
// Support Functions for Template Generators
// **************************************************************************


function MakeRequestXml(strElement, strApiLogin, strTransactionKey, strBodyXml) {
    var textToWrap = "\n" + MakeMerchantAuthenticationText(strApiLogin, strTransactionKey) + "\n";
    if (strBodyXml != '') {
	textToWrap += strBodyXml + "\n";
    }
    return "<?xml version=\"1.0\"?>\n" + WrapWithElementMultiLine(0, strElement, "xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\"", textToWrap);
}

function MakePaymentProfilePattern(indentLevel, parentElement) {
    return WrapWithElementMultiLine(indentLevel, parentElement, "", "\n" + MakeEmptyElementNLWithEolComment(indentLevel + 2, "customerType", "individual or business") + MakeCIMAddressPattern(indentLevel + 2, "billTo") + "\n" + WrapWithElementMultiLine(indentLevel + 2, "payment", "", "\n" + MakeCreditCardPattern(indentLevel + 4) + "\n") + "\n");
}

function MakeCIMAddressPattern(indentLevel, parentElement) {
    return WrapWithElementMultiLine(indentLevel, parentElement, "", "\n" + MakeEmptyElementNL(indentLevel + 2, "firstName") + MakeEmptyElementNL(indentLevel + 2, "lastName") + MakeEmptyElementNL(indentLevel + 2, "company") + MakeEmptyElementNL(indentLevel + 2, "address") + MakeEmptyElementNL(indentLevel + 2, "city") + MakeEmptyElementNL(indentLevel + 2, "state") + MakeEmptyElementNL(indentLevel + 2, "zip") + MakeEmptyElementNL(indentLevel + 2, "country") + MakeEmptyElementNL(indentLevel + 2, "phoneNumber"));
}

function MakeARBAddressPattern(indentLevel, parentElement) {
    return WrapWithElementMultiLine(indentLevel, parentElement, "", "\n" + MakeEmptyElementNL(indentLevel + 2, "firstName") + MakeEmptyElementNL(indentLevel + 2, "lastName") + MakeEmptyElementNL(indentLevel + 2, "company") + MakeEmptyElementNL(indentLevel + 2, "address") + MakeEmptyElementNL(indentLevel + 2, "city") + MakeEmptyElementNL(indentLevel + 2, "state") + MakeEmptyElementNL(indentLevel + 2, "zip") + MakeEmptyElementNL(indentLevel + 2, "country"));
}

function MakeOrderPattern(indentLevel) {
    return WrapWithElementMultiLine(indentLevel, "order", "", "\n" + MakeEmptyElementNL(indentLevel + 2, "invoiceNumber") + MakeEmptyElementNL(indentLevel + 2, "description"));
}

function MakeCustomerPattern(indentLevel) {
    return WrapWithElementMultiLine(indentLevel, "customer", "", "\n" + MakeEmptyElementNL(indentLevel + 2, "id") + MakeEmptyElementNL(indentLevel + 2, "email") + MakeEmptyElementNL(indentLevel + 2, "phoneNumber") + MakeEmptyElementNL(indentLevel + 2, "faxNumber"));
}

function MakeCreditCardPattern(indentLevel) {
    return WrapWithElementMultiLine(indentLevel, "creditCard", "", "\n" + MakeEmptyElementNL(indentLevel + 2, "cardNumber") + MakeEmptyElementNL(indentLevel + 2, "expirationDate"));
}

function MakeBankAccountPattern(indentLevel) {
    return WrapWithElementMultiLine(indentLevel, "bankAccount", "", "\n" + MakeEmptyElementNLWithEolComment(indentLevel + 2, "accountType", "checking, businessChecking, or savings") + MakeEmptyElementNL(indentLevel + 2, "routingNumber") + MakeEmptyElementNL(indentLevel + 2, "accountNumber") + MakeEmptyElementNL(indentLevel + 2, "nameOnAccount") + MakeEmptyElementNLWithEolComment(indentLevel + 2, "echeckType", "For checking or savings accounts, PPD or WEB For business checking accounts, CCD") + MakeEmptyElementNL(indentLevel + 2, "bankName"));
}


function InitializeFillerDataMaps() {
    g_fillerDataMap = new Object({
	"accountNumber": "1234567",
	"accountType": "checking",
	"address": "123 Some Street",
	"amount": "19.95",
	"bankName": "Bank of America",
	"cardNumber": "4111111111111111",
	"city": "Some City",
	"company": "Some Company",
	"country": "United States",
	"customerType": "individual",
	"description": "This is our first customer",
	"echeckType": "WEB",
	"email": "john.doe@somedomain.com",
	"expirationDate": "2009-02",
	"firstName": "John",
	"lastName": "Doe",
	"length": "1",
	"merchantCustomerId": "customer001",
	"nameOnAccount": "John Doe",
	"phoneNumber": "425-555-1212",
	"refId": Math.round(Math.random() * 999999),
	"routingNumber": "125000024",
	"startDate": GetToday(),
	"state": "WA",
	"totalOccurrences": "12",
	"trialAmount": "4.95",
	"trialOccurrences": "1",
	"unit": "months",
	"validationMode": "liveMode",
	"zip": "98033"
    });
}

// ---------------------------------------------------------------------------
// function MakeMerchantAuthenticationText
//
// Generates the XML text for the <merchantAuthentication> element and its
// sub-elements.
//
// Parameters
//   strApiLogin         The value to put for the <name> element
//   strTransactionKey   The value to put for the <transactionKey> element
//
// Returns   The full XML for the <merchantAuthentication> element
// ---------------------------------------------------------------------------


function MakeMerchantAuthenticationText(strApiLogin, strTransactionKey) {
    return WrapWithElementMultiLine(2, "merchantAuthentication", "", "\n" + WrapWithElementInLine(4, "name", "", strApiLogin) + "\n" + WrapWithElementInLine(4, "transactionKey", "", strTransactionKey) + "\n");
}

function MakeEmptyElementNL(cIndent, strElement) {
    return MakeEmptyElement(cIndent, strElement) + "\n";
}

function MakeEmptyElementNLWithEolComment(cIndent, strElement, eolComment) {
    return MakeEmptyElement(cIndent, strElement) + WrapWithXmlComments(2, eolComment) + "\n";
}

function MakeEmptyElement(cIndent, strElement) {
    var strValue = "";
    if (g_fUserFillerData && null != g_fillerDataMap && null != g_fillerDataMap[strElement]) {
	strValue = g_fillerDataMap[strElement];
    }

    return Pad(cIndent) + "<" + strElement + ">" + strValue + "</" + strElement + ">";
}

function MakeElement(cIndent, strName, blnAddNewLine) {
    return Pad(cIndent) + "<" + strElement + ">" + (blnAddNewLine) ? "\n" : "";
}

function WrapWithElementInLine(cIndent, strElement, strElementAttrs, strTextToWrap) {
    return Pad(cIndent) + "<" + strElement + ((strElementAttrs.length > 0) ? " " + strElementAttrs : "") + ">" + strTextToWrap + "</" + strElement + ">";
}

function WrapWithElementMultiLine(cIndent, strElement, strElementAttrs, strTextToWrap) {
    return Pad(cIndent) + "<" + strElement + ((strElementAttrs.length > 0) ? " " + strElementAttrs : "") + ">" + strTextToWrap + Pad(cIndent) + "</" + strElement + ">";
}

function MakeCommentBlock(cIndent, text) {
    return "\n" + WrapWithXmlComments(cIndent, text) + "\n";
}

function WrapWithXmlComments(cIndent, text) {
    return Pad(cIndent) + "<!-- " + text + " -->";
}

function Pad(cChars) {
    return g_spaces.substr(0, cChars);
}

function GetToday() {
    return formatDate(new Date(), "yyyy-MM-dd");
}

// ------------------------------------------------------------------
// These functions use the same 'format' strings as the
// java.text.SimpleDateFormat class, with minor exceptions.
// The format string consists of the following abbreviations:
//
// Field        | Full Form          | Short Form
// -------------+--------------------+-----------------------
// Year         | yyyy (4 digits)    | yy (2 digits), y (2 or 4 digits)
// Month        | MMM (name or abbr.)| MM (2 digits), M (1 or 2 digits)
//              | NNN (abbr.)        |
// Day of Month | dd (2 digits)      | d (1 or 2 digits)
// Day of Week  | EE (name)          | E (abbr)
// Hour (1-12)  | hh (2 digits)      | h (1 or 2 digits)
// Hour (0-23)  | HH (2 digits)      | H (1 or 2 digits)
// Hour (0-11)  | KK (2 digits)      | K (1 or 2 digits)
// Hour (1-24)  | kk (2 digits)      | k (1 or 2 digits)
// Minute       | mm (2 digits)      | m (1 or 2 digits)
// Second       | ss (2 digits)      | s (1 or 2 digits)
// AM/PM        | a                  |
//
// NOTE THE DIFFERENCE BETWEEN MM and mm! Month=MM, not mm!
// Examples:
//  "MMM d, y" matches: January 01, 2000
//                      Dec 1, 1900
//                      Nov 20, 00
//  "M/d/yy"   matches: 01/20/00
//                      9/2/00
//  "MMM dd, yyyy hh:mm:ssa" matches: "January 01, 2000 12:30:45AM"
// ------------------------------------------------------------------
// ------------------------------------------------------------------
// formatDate (date_object, format)
// Returns a date in the output format specified.
// The format string uses the same abbreviations as in getDateFromFormat()
// ------------------------------------------------------------------


function formatDate(date, format) {
    format = format + "";
    var result = "";
    var i_format = 0;
    var c = "";
    var token = "";
    var y = date.getYear() + "";
    var M = date.getMonth() + 1;
    var d = date.getDate();
    var E = date.getDay();
    var H = date.getHours();
    var m = date.getMinutes();
    var s = date.getSeconds();
    var yyyy, yy, MMM, MM, dd, hh, h, mm, ss, ampm, HH, H, KK, K, kk, k;
    // Convert real date parts into formatted versions
    var value = new Object();
    if (y.length < 4) {
	y = "" + (y - 0 + 1900);
    }
    value["y"] = "" + y;
    value["yyyy"] = y;
    value["yy"] = y.substring(2, 4);
    value["M"] = M;
    value["MM"] = LZ(M);
    value["MMM"] = MONTH_NAMES[M - 1];
    value["NNN"] = MONTH_NAMES[M + 11];
    value["d"] = d;
    value["dd"] = LZ(d);
    value["E"] = DAY_NAMES[E + 7];
    value["EE"] = DAY_NAMES[E];
    value["H"] = H;
    value["HH"] = LZ(H);
    if (H == 0) {
	value["h"] = 12;
    } else if (H > 12) {
	value["h"] = H - 12;
    } else {
	value["h"] = H;
    }
    value["hh"] = LZ(value["h"]);
    if (H > 11) {
	value["K"] = H - 12;
    } else {
	value["K"] = H;
    }
    value["k"] = H + 1;
    value["KK"] = LZ(value["K"]);
    value["kk"] = LZ(value["k"]);
    if (H > 11) {
	value["a"] = "PM";
    } else {
	value["a"] = "AM";
    }
    value["m"] = m;
    value["mm"] = LZ(m);
    value["s"] = s;
    value["ss"] = LZ(s);
    while (i_format < format.length) {
	c = format.charAt(i_format);
	token = "";
	while ((format.charAt(i_format) == c) && (i_format < format.length)) {
	    token += format.charAt(i_format++);
	}
	if (value[token] != null) {
	    result = result + value[token];
	} else {
	    result = result + token;
	}
    }
    return result;
}

function LZ(x) {
    return (x < 0 || x > 9 ? "" : "0") + x;
}
