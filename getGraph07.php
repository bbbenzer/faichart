<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];
		$Br[1] = (intval($xYY)-1);
		$Br[2] = $xYY;
		$resArray = array();
		
		$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,
				(
					(
						(
						SELECT data_sale_year.bM$xMM
						FROM data_sale_year 
						WHERE data_sale_year.xYear = '".(intval($xYY)-1)."'
						AND data_sale_year.bCode = branch_fac.Cus_Code
						)/(
						SELECT data_sale_year.M$xMM
						FROM data_sale_year 
						WHERE data_sale_year.xYear = '".(intval($xYY)-1)."'
						AND data_sale_year.bCode = branch_fac.Cus_Code
						) 
					)*100
				) AS d1,
				(
					(
						(
						SELECT data_sale_year.bM$xMM 
						FROM data_sale_year 
						WHERE data_sale_year.xYear = '".$xYY."'
						AND data_sale_year.bCode = branch_fac.Cus_Code
						)/(
						SELECT data_sale_year.M$xMM 
						FROM data_sale_year 
						WHERE data_sale_year.xYear = '".$xYY."'
						AND data_sale_year.bCode = branch_fac.Cus_Code
						)
					)*100
				) AS d2
				FROM branch_fac
				WHERE branch_fac.Active = 1
				ORDER BY d2 DESC";
				
		//echo 	$xSql."<br>";
			
		$result = mysql_query( $xSql );
		while ($Row = mysql_fetch_array($result))
		{
			//echo number_format((float)$Row["d1"], 2, '.', '')."<br>";
			array_push( $resArray,
				array("name"=>$Row["Branch_Name"],"year"=>$Row["xYear"],
				      "d1"=>number_format((float)$Row["d1"], 2, '.', ''),
					  "d2"=>number_format((float)$Row["d2"], 2, '.', '')
				) 
			);
		}

mysql_close($meConnect);
	echo json_encode($resArray);

}
?>