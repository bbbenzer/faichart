<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];
		
		//$xMM = '02';
		//$xYY = '2017';
			$gosDate = (intval($xYY)-1)."-01-01";
			$goeDate = (intval($xYY)-1)."-".$xMM."-01";
			$goeDate = date("Y-m-t", strtotime($goeDate));
			
			$gnsDate = intval($xYY)."-01-01";
			$gneDate = intval($xYY)."-".$xMM."-01";
			$gneDate = date("Y-m-t", strtotime($gneDate));
		$Br[1] = (intval($xYY)-1);
		$Br[2] = $xYY;
		$resArray = array();
		
		$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,
				(
				SELECT data_sale_year.M$xMM
				FROM data_sale_year 
				WHERE data_sale_year.xYear = '".(intval($xYY)-1)."'
				AND data_sale_year.bCode = branch_fac.Cus_Code
				) AS d1,
				(
				SELECT data_sale_year.M$xMM 
				FROM data_sale_year 
				WHERE data_sale_year.xYear = '".$xYY."'
				AND data_sale_year.bCode = branch_fac.Cus_Code
				) AS d2
				FROM branch_fac
				WHERE branch_fac.Active1 = 1 AND branch_fac.gId = 2
				ORDER BY branch_fac.Branch_Name ASC";
				
		//echo 	$xSql."<br>";
/*			
			$Sql1 = "SELECT 
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$nsDate' AND '$neDate') AS d1
				,
				(SELECT SUM(income_to_day.SumTotal) 
				FROM income_to_day 
				INNER JOIN customer ON income_to_day.Branch_Code = customer.Cus_Code
				WHERE income_to_day.Branch_Code = '$cus_code' 
				AND income_to_day.Create_Date BETWEEN '$osDate' AND '$oeDate') AS d2";
*/
		$result = mysql_query( $xSql );
		while ($Row = mysql_fetch_array($result))
		{
			array_push( $resArray,
				array("name"=>$Row["Branch_Name"],"year"=>$Row["xYear"],
				      "d1"=>$Row["d1"],"d2"=>$Row["d2"]
				) 
			);
		}

mysql_close($meConnect);
	echo json_encode($resArray);

}
?>