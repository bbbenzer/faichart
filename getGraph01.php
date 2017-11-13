<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$xMonth = $_POST['xMonth'];
		$xYear = $_POST['xYear'];

		$Br[1] = "สาขา";
		$Br[2] = "การตลาด";
		$Br[3] = "สายส่ง";
		$Br[4] = "ส่วนกลาง";
		$Br[5] = "Franchise";
		$Br[6] = "7-11";
		$resArray = array();

		$sDate = intval($xYear)."-".$xMonth."-01";
		$eDate = date("Y-m-t", strtotime($sDate));
	
		$Sql = "SELECT 
				(SELECT SUM(data_sale_year.M$xMonth)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 1 
				AND branch_fac.Active = 1 
				AND data_sale_year.xYear = '$xYear'
				) AS xS01,
				(SELECT SUM(data_sale_year.M$xMonth)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 5 
				AND branch_fac.Active3 = 1 AND 
				data_sale_year.xYear = '$xYear'
				) AS xS02,
				(SELECT SUM(data_sale_year.M$xMonth)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 2 
				AND branch_fac.Active1 = 1
				AND data_sale_year.xYear = '$xYear'
				) AS xS03,
				(SELECT SUM(data_sale_year.M$xMonth)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 6 
				AND branch_fac.Active5 = 1
				AND data_sale_year.xYear = '$xYear'
				) AS xS04,
				(SELECT SUM(data_sale_year.M$xMonth)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 4 
				AND branch_fac.Active4 = 1
				AND data_sale_year.xYear = '$xYear'
				) AS xS05,
				(SELECT SUM(data_sale_year.M$xMonth)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 3 
				AND branch_fac.Active2 = 1
				AND data_sale_year.xYear = '$xYear'
				) AS xS06";

		$result = mysql_query( $Sql );
		$i=1;
		while ($Row = mysql_fetch_array($result))
		{
			array_push( $resArray,array("name"=>$Br[1],"y"=>$Row["xS01"],"drilldown"=>$Row["sM1"]));
			array_push( $resArray,array("name"=>$Br[2],"y"=>$Row["xS02"],"drilldown"=>$Row["sM2"]));
			array_push( $resArray,array("name"=>$Br[3],"y"=>$Row["xS03"],"drilldown"=>$Row["sM3"]));
			array_push( $resArray,array("name"=>$Br[4],"y"=>$Row["xS04"],"drilldown"=>$Row["sM4"]));
			array_push( $resArray,array("name"=>$Br[5],"y"=>$Row["xS05"],"drilldown"=>$Row["sM5"]));
			$xV711 = $Row["xS06"] - ($Row["xS06"]*7)/107;
			$xV45 = $xV711 - ($xV711*45)/100;
			array_push( $resArray,array("name"=>$Br[6],"y"=>$xV45,"drilldown"=>$Row["sM6"]));
			$i++;
		}
mysql_close($meConnect);
		echo json_encode($resArray);

		
}
?>