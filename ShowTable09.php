<link rel="stylesheet" type="text/css" href="css/block.css">
<style type="text/css">
body{
    font-size:12px; 
}
.textAlignVer{
    display:block;
    writing-mode: tb-rl;
    filter: flipv fliph;
    -webkit-transform: rotate(-90deg); 
    -moz-transform: rotate(-90deg); 
    transform: rotate(-90deg); 
    position:relative;
    width:20px;
    white-space:nowrap;
    font-size:12px;
    margin-bottom:10px;
}
		.fb{font-size:18px;}
		.fbb{font-weight:bold;}
</style>
<?php
    require 'include/connect.php';
	require 'include/xFunction.php';
	$xDateTime = new xDateTime();
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];
	}
	$aoDate = (intval($xYY)-1)."-$xMM-01";
	$anDate = intval($xYY)."-$xMM-01";
	
	$gosDate = (intval($xYY)-1)."-01-01";
	$goeDate = (intval($xYY)-1)."-".$xMM."-01";
	$goeDate = date("Y-m-t", strtotime($goeDate));
	
	$gnsDate = intval($xYY)."-01-01";
	$gneDate = intval($xYY)."-".$xMM."-01";
	$gneDate = date("Y-m-t", strtotime($gneDate));

	function xOTALL($cus_code){
			$xVal1 = 0;
			$Sql1 = "SELECT COUNT(*) AS Cnt FROM customer AS cs1 
					INNER JOIN customer AS cs2 ON cs1.ChildOf = cs2.Cus_Code 
					WHERE cs1.ChildOf = '$cus_code'";
			$result1 = mysql_query( $Sql1 );
			while ($Row1 = mysql_fetch_array($result1)){
				$xVal1 = $Row1["Cnt"];
   			 }
			return $xVal1;
	}
	
	function xOTACTIVE($cus_code,$sDate){
		$xVal1 = 0;
		$Sql1 = "SELECT cs1.Cus_Code AS Cnt
				FROM customer AS cs1
				INNER JOIN customer AS cs2 ON cs1.ChildOf = cs2.Cus_Code
				INNER JOIN sale_pack ON cs1.Cus_Code = sale_pack.Cus_Code
				WHERE cs1.ChildOf = '$cus_code'
				AND sale_pack.DocDate BETWEEN '$sDate' 
				AND '".date("Y-m-t", strtotime($sDate))."'
				GROUP BY cs1.Cus_Code";
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1++;
   		}
		return $xVal1;
	}	

	function xOTNEW($cus_code,$sDate){
			$xVal1 = 0;
			$Sql1 = "SELECT cs1.Cus_Code
					FROM customer AS cs1 
					INNER JOIN customer AS cs2 ON cs1.ChildOf = cs2.Cus_Code 
					WHERE cs1.ChildOf = '$cus_code'
					AND cs1.StratDate BETWEEN '$sDate' 
					AND '".date("Y-m-t", strtotime($sDate))."'
					GROUP BY cs1.Cus_Code";
			$result1 = mysql_query( $Sql1 );
			while ($Row1 = mysql_fetch_array($result1)){
				$xVal1++;
   			 }
			 
			return $xVal1;
	}
	
	function xGrowth($cus_code,$osDate,$oeDate,$nsDate,$neDate){
		$xVal1 = 0;
		$Sql1 = "SELECT (
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$nsDate' AND '$neDate')
				-
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$osDate' AND '$oeDate') )
				/
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$osDate' AND '$oeDate') * 100 AS Cnt";

		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = number_format($Row1["Cnt"], 2);
   		}
		return $xVal1;
	}
	
	function xSaleTotal($cus_code,$osDate,$oeDate,$nsDate,$neDate,$df){
		$xVal1 = 0;
		$Sql1 = "SELECT 
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$nsDate' AND '$neDate') AS SaleNew
				,
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$osDate' AND '$oeDate') AS SaleOle";
		//echo $Sql1;
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			if($df==1)
				$xVal1 = number_format($Row1["SaleNew"], 0);
			else
				$xVal1 = number_format($Row1["SaleOle"], 0);
   		}
		return $xVal1;
	}
	
		function xLoseTotal($cus_code,$osDate,$oeDate,$nsDate,$neDate,$df){
		$xVal1 = 0;
		$Sql1 = "SELECT 
				(SELECT SUM(income_to_day.Lost) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$nsDate' AND '$neDate') AS SaleNew
				,
				(SELECT SUM(income_to_day.Lost) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$osDate' AND '$oeDate') AS SaleOle";
		//echo $Sql1;
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			if($df==1)
				$xVal1 = number_format($Row1["SaleNew"], 0);
			else
				$xVal1 = number_format($Row1["SaleOle"], 0);
   		}
		return $xVal1;
	}
	
	$i=0;
	$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,branch_fac.gId
			FROM branch_fac
			WHERE branch_fac.Active1 = 1 AND branch_fac.gId = 2
			ORDER BY branch_fac.Branch_Name ASC";
	$result = mysql_query( $xSql );
	while ($Row = mysql_fetch_array($result)){
		$xCode[$i] = $Row["Cus_Code"];
		$xVal[$i] = $Row["Branch_Name"];
		$i++;
    }


	echo "<div style='width:1200px'>";
	echo "<TABLE>";
	
	echo "<TR>";
	echo "<TH width='200px'></TH>";
	for($j=0;$j<$i;$j++){
		echo "<TH class='box1' width='200px' align='center'>$xVal[$j]</TH>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>ยอดขาย ".$xDateTime->getMonthTH($xMM)."</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".
		xSaleTotal($xCode[$j],$aoDate,$goeDate,$anDate,$gneDate,1).
		"</TD>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>% ของเสีย ".$xDateTime->getMonthTH($xMM)."</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".
		number_format( xLoseTotal($xCode[$j],$aoDate,$goeDate,$anDate,$gneDate,1)/xSaleTotal($xCode[$j],$aoDate,$goeDate,$anDate,$gneDate,1)*100 , 2, '.', ',') .
		"%</TD>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>OUTLET</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".xOTALL( $xCode[$j] )."</TD>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>ACTIVE OUTLET</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".xOTACTIVE( $xCode[$j],$anDate )."</TD>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>NEW OUTLET</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".xOTNEW( $xCode[$j],$anDate )."</TD>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>ยอดขาย ม.ค. - ".$xDateTime->getMonthTH($xMM)."</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".
		xSaleTotal($xCode[$j],$gosDate,$goeDate,$gnsDate,$gneDate,1)
		."</TD>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px' class='fb'>%ของเสีย ม.ค. - ".$xDateTime->getMonthTH($xMM)."</TD>";
	for($j=0;$j<$i;$j++){
		echo "<TD width='200px' align='center' class='fb'>".
		number_format( xLoseTotal($xCode[$j],$gosDate,$goeDate,$gnsDate,$gneDate,1)/xSaleTotal($xCode[$j],$gosDate,$goeDate,$gnsDate,$gneDate,1)*100 , 2, '.', ',')
		."%</TD>";
	}
	echo "</TR>";
	
	echo "<TABLE>";	
	echo "</div>";
?>    