<?php
	function getDateCriteria($query_month, $query_year) {
		if ($query_month==12) {
			$query_month_max = "01";
			$query_year_max = $query_year + 1;
		}
		else {
			$query_month_max = sprintf( "%02d", ($query_month + 1) );
			$query_year_max = $query_year;
		}

		$date = sprintf( "%02d", $query_month)."/".$query_year;
		$date2 = "AND ordered >= '".$query_year."-".$query_month."-01 00:00:00' AND ordered < '".$query_year_max."-".$query_month_max."-01 00:00:00'";

		return array("date" => $date, "date2" => $date2);

	}


	function getDateCriteriaYrOnly($query_year) {
		$date2 = "AND ordered >= '".$query_year."-01-01 00:00:00' AND ordered <= '".$query_year."-12-31 23:59:59'";
		return array("date" => $query_year, "date2" => $date2);
	}

?>
