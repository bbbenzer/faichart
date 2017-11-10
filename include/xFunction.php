<?php

class xDateTime {

   public function getMonthThai($mm){
        $MonthTH = "";
		switch( intval( $mm ) ){
			case 1: $MonthTH = "มกราคม";break;
			case 2: $MonthTH = "กุมภาพันธ์";break;
			case 3: $MonthTH = "มีนาคม";break;
			case 4: $MonthTH = "เมษายน";break;
			case 5: $MonthTH = "พฤษภาคม";break;
			case 6: $MonthTH = "มิถุนายน";break;
			case 7: $MonthTH = "กรกฎาคม";break;
			case 8: $MonthTH = "สิงหาคม";break;
			case 9: $MonthTH = "กันยายน";break;
			case 10: $MonthTH = "ตุลาคม";break;
			case 11: $MonthTH = "พฤศจิกายน";break;
			case 12: $MonthTH = "ธันวาคม";break;
		}
		return $MonthTH;
   }
   
   public function getMonthTH($mm){
	   $MonthTH = "";
		switch( intval( $mm ) ){
			case 1: $MonthTH = "ม.ค.";break;
			case 2: $MonthTH = "ก.พ.";break;
			case 3: $MonthTH = "มี.ค.";break;
			case 4: $MonthTH = "ม.ย.";break;
			case 5: $MonthTH = "พ.ค.";break;
			case 6: $MonthTH = "มิ.ย.";break;
			case 7: $MonthTH = "ก.ค.";break;
			case 8: $MonthTH = "ส.ค.";break;
			case 9: $MonthTH = "ก.ย.";break;
			case 10: $MonthTH = "ต.ค.";break;
			case 11: $MonthTH = "พ.ย.";break;
			case 12: $MonthTH = "ธ.ค.";break;
		}
		return $MonthTH;
   }
} 

?>