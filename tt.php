<?php
		$xMonth = "02";

		$xOr = "( ";
        $xOrL = "( ";
        for($i=1;$i<=intval($xMonth);$i++){
        	$xOr .= "data_report_sale_year.xMM = '" . sprintf("%02d", $i) . "' OR ";
        	$xOrL .= "data_report_lose_year.xMM = '" . sprintf("%02d", $i) . "' OR ";
        }
		$xOr = substr($xOr,0,strlen($xOr) -3);
        $xOr .= " )";
		$xOrL = substr($xOrL,0,strlen($xOrL) -3);
        $xOrL .= " )";
		echo $xOr;
		echo "<br>";
		echo $xOrL;
		echo "<br>";
		
		?>