function ValidateDate(strDate) {

    //Basic check for format validity
    var validformat = /^\d{2}\/\d{2}\/\d{4}$/;

    if (!validformat.test(strDate)) {
        return false;
    } else { //Detailed check for valid date ranges
        var monthfield = strDate.split("/")[0];
        var dayfield = strDate.split("/")[1];
        var yearfield = strDate.split("/")[2];
        var dayobj = new Date(yearfield, monthfield - 1, dayfield);
        if ((dayobj.getMonth() + 1 != monthfield) || (dayobj.getDate() != dayfield) || (dayobj.getFullYear() != yearfield)) {
            return false;
        }
    }
    return true;
}
