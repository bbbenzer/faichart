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
		(
			SELECT	IFNULL(SUM(income_to_day.SumTotal),0) AS Total
			FROM income_to_day
			INNER JOIN branch_fac ON income_to_day.Branch_Code = branch_fac.Cus_Code
			WHERE income_to_day.Create_Date BETWEEN '$sDate' AND '$eDate'
			AND branch_fac.gId = 1
		) AS xS01,
		(
			SELECT	IFNULL(SUM(income_to_day.SumTotal),0) AS Total
			FROM income_to_day
			INNER JOIN branch_fac ON income_to_day.Branch_Code = branch_fac.Cus_Code
			WHERE income_to_day.Create_Date BETWEEN '$sDate' AND '$eDate'
			AND branch_fac.gId = 5
		) AS xS02,
		(
			SELECT	IFNULL(SUM(income_to_day.SumTotal),0) AS Total
			FROM income_to_day
			INNER JOIN branch_fac ON income_to_day.Branch_Code = branch_fac.Cus_Code
			WHERE income_to_day.Create_Date BETWEEN '$sDate' AND '$eDate'
			AND branch_fac.gId = 2
		) AS xS03,
		(
			SELECT	IFNULL(SUM(income_to_day.SumTotal),0) AS Total
			FROM income_to_day
			INNER JOIN branch_fac ON income_to_day.Branch_Code = branch_fac.Cus_Code
			WHERE income_to_day.Create_Date BETWEEN '$sDate' AND '$eDate'
			AND branch_fac.gId = 6
		) AS xS04,
		(
			SELECT	IFNULL(SUM(income_to_day.SumTotal),0) AS Total
			FROM income_to_day
			INNER JOIN branch_fac ON income_to_day.Branch_Code = branch_fac.Cus_Code
			WHERE income_to_day.Create_Date BETWEEN '$sDate' AND '$eDate'
			AND branch_fac.gId = 4
		) AS xS05,
		(
			SELECT	IFNULL(SUM(income_to_day.SumTotal),0) AS Total
			FROM income_to_day
			INNER JOIN branch_fac ON income_to_day.Branch_Code = branch_fac.Cus_Code
			WHERE income_to_day.Create_Date BETWEEN '$sDate' AND '$eDate'
			AND branch_fac.gId = 3
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
			array_push( $resArray,array("name"=>$Br[6],"y"=>$Row["xS06"],"drilldown"=>$Row["sM6"]));
			$i++;
		}
mysql_close($meConnect);
		echo json_encode($resArray);

		
}
?>