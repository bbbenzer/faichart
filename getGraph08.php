<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
		$resArray = array();
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];
		
		$xOr = "( ";
        $xOrL = "( ";
        for($i=1;$i<=intval($xMM);$i++){
        	$xOr .= "data_sale_year.M" . sprintf("%02d", $i) . " + ";
        	$xOrL .= "data_sale_year.bM" . sprintf("%02d", $i) . " + ";
        }
		$xOr = substr($xOr,0,strlen($xOr) -3);
        $xOr .= " )";
		$xOrL = substr($xOrL,0,strlen($xOrL) -3);
        $xOrL .= " )";

				$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,(
						(
							(
							SELECT $xOr
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . $xYY . "'
						   AND data_sale_year.bCode = branch_fac.Cus_Code
							)
							-
							(
							SELECT $xOr
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . (intval($xYY)-1) . "'
						   AND data_sale_year.bCode = branch_fac.Cus_Code 
							) 
						)/( 
							SELECT $xOr 
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . (intval($xYY)-1) . "' 
						   AND data_sale_year.bCode = branch_fac.Cus_Code 
							) 
						)*100 AS dd FROM branch_fac 
						WHERE branch_fac.Active = 1 ORDER BY dd DESC";
		//echo $xSql;
		$result = mysql_query( $xSql );
		while ($Row = mysql_fetch_array($result))
		{
			array_push( 
				$resArray,
				array("name"=>$Row["Branch_Name"],"y"=>number_format((float)$Row["dd"], 2, '.', ''),"drilldown"=>$Row["Branch_Name"])
			);
		}

mysql_close($meConnect);
		echo json_encode($resArray);
}
?>