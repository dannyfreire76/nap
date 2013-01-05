/**
 * zero-padded to 2-digits.
 */
Number.prototype._ = function() {
    return (this < 10) ? ('0' + this) : this
};
/**
 * formats a date/time using a formatting string thus...
 * yyyy: 4-digit year
 * mmmm: verbose month-of-year
 * mmm: abbreviated month-of-year
 * mm: 2-digit month-of-year
 * m: 1- or 2-digit month-of-year
 * dddd: verbose day-of-week
 * ddd: abbreviated day-of-week
 * dd: 2-digit day-of-month
 * d: 1- or 2-digit day-of-month
 * hh: 2-digit hour-of-day
 * h: 1- or 2-digit hour-of-day
 * ii: 2-digit minute-of-hour
 * i: 1- or 2-digit minute-of-hour
 * ss: 2-digit second-of-minute
 * s: 1- or 2-digit second-of-minute
 *
 * @param {Object}
 *            s
 */
Date.prototype._ = function(s) {
    var reProperCase = /[A-Z][a-z]*/g;
    //Numeric replacements first, textual replacements last...
    return s 	.replace(/\byyyy\b/g, this.getFullYear())
		.replace(/\bmm\b/g, (this.getMonth() + 1)._())
		.replace(/\bm\b/g, this.getMonth() + 1)
		.replace(/\bdd\b/g, this.getDate()._())
		.replace(/\bd\b/g, this.getDate())
		.replace(/\bhh\b/g, this.getHours()._())
		.replace(/\bh\b/g, this.getHours())
		.replace(/\bii\b/g, this.getMinutes()._())
		.replace(/\bi\b/g, this.getMinutes())
		.replace(/\bss\b/g, this.getSeconds()._())
		.replace(/\bs\b/g, this.getSeconds())
		.replace(/\bmmmm\b/g, 'JanuaryFebruaryMarchAprilMayJuneJulyAugustSeptemberOctoberNovemberDecember'.match(reProperCase)[this.getMonth()])
		.replace(/\bmmm\b/g, 'JanFebMarAprMayJunJulAugSepOctNovDec'.match(reProperCase)[this.getMonth()])
		.replace(/\bdddd\b/g, 'SundayMondayTuesdayWednesdayThursdayFridaySaturday'.match(reProperCase)[this.getDay()])
		.replace(/\bddd\b/g, 'SunMonTueWedThuFriSat'.match(reProperCase)[this.getDay()])
};
/**
 * adds any number of days, months and years onto an existing date (presumed
 * zero, if missing). Negative values acceptable. Time portion retained, if any.
 *
 * @param {Object}
 *            days
 * @param {Object}
 *            months
 * @param {Object}
 *            years
 */
Date.prototype.add = function(days, months, years) {
    return new Date(	this.getFullYear() + (!years ? 0 : years),
		    this.getMonth() + (!months ? 0 : months),
		    this.getDate() + (!days ? 0 : days),
		    this.getHours(),
		    this.getMinutes(),
		    this.getSeconds())
};
/**
 * Converts mysqldate string into a javascript date
 **/
String.prototype.ymd = function(DEFAULT) {
    if (!DEFAULT) DEFAULT = new Date();
    var ymdhis = this.match(/\d+/g);
    if (!ymdhis) {
	return DEFAULT;
    } else {
	if (eval(ymdhis.join("+")) > 0) {
	    if (ymdhis.length > 1) ymdhis[1]--;
	    while (ymdhis.length < 6) ymdhis.push(0);
	    return eval("new Date(" + ymdhis + ")");
	} else {
	    return DEFAULT;
	}
    }
}
/**
 * Convenience functions for date components
 **/
Date.prototype.w=Date.prototype.getDay;
Date.prototype.d=Date.prototype.getDate;
Date.prototype.m=Date.prototype.getMonth;
Date.prototype.y=Date.prototype.getFullYear;
Date.prototype.mdy=function(){return this._("m/d/yyyy")};
Date.prototype.ymd=function(){return this._("yyyy-mm-dd hh:ii:ss")}; //mysqldate format
