// This is a count of the number of order lines to calculate on.
// There is also a lineCount setting in the rep.order.1.php you
// need to change to be able to tell it how many lines to display.

var lineCount = 25;

$(function() { //on doc ready
    $('#apply_credit').each(function() {
	if ($(this).is(':checked')) {
	    $('#credit').css('color', 'navy');
	}

	$(this).click(function() {
	    if ($(this).is(':checked')) {
		$('#credit').css('color', 'navy');
	    } else {
		$('#credit').css('color', '#000');
	    }
	    reCalc();
	});
    });

})

function r2(n) {

    ans = n * 1000
    ans = Math.round(ans / 10) + ""
    while (ans.length < 3) {
	ans = "0" + ans
    }
    len = ans.length
    ans = ans.substring(0, len - 2) + "." + ans.substring(len - 2, len)
    return ans
}

function r3(n) {
    ans = n * 10000
    ans = Math.round(ans / 10) + "";
    while (ans.length < 4) {
	ans = "0" + ans;
    }

    len = ans.length
    ans = ans.substring(0, len - 3) + "." + ans.substring(len - 3, len)
    return ans
}

function reCalc() {
    var disc_pct = 1
    if ($('#discount_code').val() != '') {
	var code_vals = $('#discount_code').val().split("|");
	var dcode = code_vals[0];
	var disc_pct = code_vals[1];
	disc_pct = 1 - disc_pct;
    }


    for (i = 1; i <= lineCount; i++) {
	if (eval('document.rep_orderform.quantity' + i + '.value.valueOf() != "" || document.rep_orderform.price' + i + '.value.valueOf() != ""')) {
	    eval('document.rep_orderform.disc_price' + i + '.value = r3( document.rep_orderform.price' + i + '.value * disc_pct )');
	    eval('document.rep_orderform.price' + i + '.value = r2( document.rep_orderform.price' + i + '.value )');

	    eval('var total' + i + ' = r3(document.rep_orderform.quantity' + i + '.value * document.rep_orderform.disc_price' + i + '.value)');
	    eval('document.rep_orderform.total' + i + '.value = r3( total' + i + ' - 0 )');
	} else {
	    eval('var total' + i + ' = ""');
	}
    }

    var subtotal = total1.valueOf() - 0;

    for (x = 2; x <= lineCount; x++) {
	if (eval('total' + x) != "") {
	    subtotal += eval('total' + x).valueOf() - 0;
	}
    }

    subtotal = r3(subtotal);
    $('#subtotal').val(subtotal);

    finishCalc();
}

function finishCalc() {
    var total = document.rep_orderform.subtotal.value.valueOf() - 0;
    total += document.rep_orderform.shipping.value.valueOf() - 0;
    total -= document.rep_orderform.discount.value.valueOf() - 0;

    if ($('#apply_credit').is(':checked')) {
	total -= document.rep_orderform.credit.value.valueOf() - 0;
    }

    total = r2(total);
    document.rep_orderform.total.value = total;

    var quantity_total = 0;
    parseFloat(quantity_total);

    for (y = 1; y <= lineCount; y++) {
	if (eval('document.rep_orderform.quantity' + y + '.value.valueOf() != ""')) {
	    eval('quantity_total += document.rep_orderform.quantity' + y + '.value.valueOf() - 0');
	}
    }

    document.rep_orderform.shipping.value = r2(document.rep_orderform.shipping.value);
    document.rep_orderform.discount.value = r2(document.rep_orderform.discount.value);

    parseFloat(quantity_total);
    document.rep_orderform.item_count.value = quantity_total - 0;
}

var win = null;

function newWindow(mypage, myname, w, h, features) {
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    if (winl < 0) winl = 0;
    if (wint < 0) wint = 0;
    var settings = 'height=' + h + ',';
    settings += 'width=' + w + ',';
    settings += 'top=' + wint + ',';
    settings += 'left=' + winl + ',';
    settings += features;
    win = window.open(mypage, myname, settings);
    win.window.focus();
}

$(function() { //on doc ready
    $('#shipest').click(function() {
	ShipLoad()
    });
    $('#recalc').click(function() {
	reCalc()
    });
    $('#discount_code').change(function() {
	reCalc()
    });

    reCalc();
});

function ShipLoad(lastSelected) {

    reCalc();
    var tot_weight = 0;
    var tot_quant = 0;

    $('input[@name*=quantity]').each(function() {
	if (!isNaN($(this).val()) && $(this).val() > 0) {
	    tot_quant += $(this).val() * 1;
	    var this_name = $(this).attr('name');
	    var this_id = this_name.substring(8)

	    $sku_name_field = $('select[@name=name' + this_id + ']');
	    var tmp_var = $sku_name_field.val().split('|');
	    var this_weight = tmp_var[2];
	    tot_weight += this_weight * 1;
	}
    });

    var orig_html = $('#shipest_wrapper').html();
    var ship_url = 'calcs.php?recalc_ship_admin=1&tot_weight=' + tot_weight + '&shipping_method=&tot_quant=' + tot_quant + '&subtotal=' + $('#subtotal').val();

    $('#shipest_wrapper').each(function() {

	$(this).small_spinner();

	$.get(ship_url, function(data) {
	    var selected_option = $("select[@id=shipping_estimate] option[@selected]");
	    var selected_val = $(selected_option).val();
	    $('#shipping_estimate').html(data);
	    $('#shipest_wrapper').html(orig_html);
	    $('#shipest').click(function() {
		ShipLoad()
	    });

	    $('#shipping_estimate').change(function() {
		//setShippingValue();
	    });
	    if (lastSelected) {
		$('#shipping_estimate').val(lastSelected);
	    }
	    //setShippingValue();
	});
    });

}

function setShippingValue() {

    var shipParts = $('#shipping_estimate').val().split('|');

    var shipMethodCost = 0;
    if ((shipParts[1] * 1) > 0) {
	shipMethodCost = (shipParts[1] * 1);
    }

    var handling = $('#handling').html();

    var newval = 0;

    if ((shipMethodCost * 1) > 0) {
	if (!isNaN(handling) && (handling * 1) > 0) {
	    newval = r2((shipMethodCost * 1) + (handling * 1));
	} else {
	    newval = r2(shipMethodCost * 1);
	}
	$('#shipping').val(newval);
	reCalc();
    }
}

function setPrice(i, product_change) {

    var nameFld = 'name' + i;
    var priceFld = 'price' + i;

    if ($('#quantity' + i).val() != "") {

	if (product_change) {
	    var parts = $('#' + nameFld).val().split('|');
	    //alert(parts);
	    $('#' + priceFld).val(parts[2]);
	    reCalc();
	} else {
	    if ($('#' + priceFld).val() != "") {
		reCalc();
	    }
	}
    } else {
	$('#' + priceFld).val(0);
	reCalc();
    }
}
