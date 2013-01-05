/**
 * relies on jquery, _.jquery.js, and _.date.js
 *
 **/


    function mysqlDate(date) {
	if (date.prototype === Date) return date;
	var ymd = date.match(/\d+/g);
	if (!ymd) return new Date();
	if (eval(ymd.join("+")) == 0) return new Date();
	while (ymd.length < 3) ymd.push(1);
	while (ymd.length < 6) ymd.push(0);
	ymd[1]--;
	var result = eval("new Date(" + ymd + ")");
	if (!result) return new Date();
	return result;
    }

    function UpdateDateValue(elem){
	var elems=$(elem).parent().children();
	var date=mysqlDate(elems[0].value);
	elems[0].value=(new Date(elems[3].value,elems[1].selectedIndex,elems[2].value,date.getHours(),date.getMinutes(),date.getSeconds()))._("yyyy-mm-dd hh:ii:ss");
	UpdateDateSelector(elem);
    }

    function UpdateDecade(elem){
	var elems=$(elem).parent().children();
	var date=mysqlDate(elems[0].value);
	date=eval("date.add(0,0,"+elem.value+")");
	var decade=date.getFullYear() - (date.getFullYear() % 10);
	var Y=[];
	for(var i=0;i<10;i++)Y.push(decade+i);
	$(elems[3]).empty().append(_("option",{value:Y},Y));
	elems[3].value=date.getFullYear();
	UpdateDateValue(elem);
    }

    function UpdateYearSelector(elem){
	var elems=$(elem).parent().children();
	var date=mysqlDate(elems[0].value);
	var decade=date.getFullYear() - (date.getFullYear() % 10);
	var Y=[];
	for(var i=0;i<10;i++)Y.push(decade+i);
	$(elems[3]).empty().append(_("option",{value:Y},Y));
	elems[3].value=date.getFullYear();
    }

    function UpdateMonthSelector(elem){
	var elems=$(elem).parent().children();
	var date=mysqlDate(elems[0].value);
	elems[1].value=date.getMonth();
    }

    function UpdateDaySelector(elem){
	var elems=$(elem).parent().children();
	var date=mysqlDate(elems[0].value);
	var days=date.add(1-date.getDate()).add(0,1).add(-1).getDate();
	var D=[];
	for(var i=0;i<days;i++)D.push(i+1);
	$(elems[2]).empty().append(_("option",{value:D},D));
	elems[2].value=date.getDate();
    }

    function UpdateDateSelector(elem){
	var date= mysqlDate(elem.value);
	UpdateYearSelector(elem); //TODO: set decade
	UpdateMonthSelector(elem); //TODO: set month
	UpdateDaySelector(elem); //TODO: set days in month/year
    }

    function BuildDateSelector(elem) {
	//non-dynamic portion
	var html = _("select", "title", "month", _("option", {
	    value: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
	}, "JanFebMarAprMayJunJulAugSepOctNovDec".match(/.../g)));
	var Q = _("option","");
	html += _("select", Q);
	html += _("select", Q);
	html += _("input", {type:"button",
	    value: "-10+10".match(/.../g)
	});
	$(elem).wrap(_("span",{nowrap:"nowrap"},""));
	$(elem).after(html);
	$(elem).siblings("select").change(function(){UpdateDateValue(this)}).blur(function(){UpdateDateValue(this)});
	$(elem).siblings("input").click(function(){UpdateDecade(this)});
	$(elem).click(function(){$(this).siblings().toggle()});
	$(elem).click();
	UpdateDateSelector(elem);
    }

    (function($) {
	$.fn.datepicker = function() {
	    this.each(function() {
		BuildDateSelector(this);
	    });
	}
	return $;
    })(jQuery);

    $(document).ready(function() {
	$(".datepicker").datepicker();
	//initCalendar($("input"));
    });
