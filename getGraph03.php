<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];

		$xOr = "( ";
        $xOrL = "( ";
        for($i=1;$i<=intval($xMM);$i++){
        	$xOr .= "data_report_sale_year.xMM = '" . sprintf("%02d", $i) . "' OR ";
        	$xOrL .= "data_report_lose_year.xMM = '" . sprintf("%02d", $i) . "' OR ";
        }
		$xOr = substr($xOr,0,strlen($xOr) -3);
        $xOr .= " )";
		$xOrL = substr($xOrL,0,strlen($xOrL) -3);
        $xOrL .= " )";

		$xSql = "SELECT 
						(
							( 
							SELECT	SUM(data_sale_year.bM$xMM)
							FROM data_sale_year
							INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
							WHERE branch_fac.gId = 1 
							AND data_sale_year.xYear = '".(intval($xYY)-1)."'
							) 
							/ 
							( 
							SELECT	SUM(data_sale_year.M$xMM)
							FROM data_sale_year
							INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
							WHERE branch_fac.gId = 1 
							AND data_sale_year.xYear = '".(intval($xYY)-1)."'
							)
						)
						*100 AS xOle , 
						(
							(
							SELECT	SUM(data_sale_year.bM$xMM)
							FROM data_sale_year
							INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
							WHERE branch_fac.gId = 1 
							AND branch_fac.Active = 1 
							AND data_sale_year.xYear = '$xYY'
							)
							/ 
							( 
							SELECT	SUM(data_sale_year.M$xMM)
							FROM data_sale_year
							INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
							WHERE branch_fac.gId = 1 
							AND branch_fac.Active = 1 
							AND data_sale_year.xYear = '$xYY'
							) 
						) 
						* 100 AS xNew";
			
		$result = mysql_query( $xSql );
		while ($Row = mysql_fetch_array($result))
		{
			$xOle = $Row["xOle"];
			$xNew = $Row["xNew"];
		}
//========================================
		$strSQL = "DELETE FROM report_gp_1_2";
		$objQuery = mysql_query($strSQL);
		$strSQL = "INSERT INTO report_gp_1_2 ";
		$strSQL .="(xOld,xNew)";
		$strSQL .=" VALUES ";
		$strSQL .="('".$xOle."','".$xNew."')";
		$objQuery = mysql_query($strSQL);
//========================================
		$Br[1] = (intval($xYY)-1);
		$Br[2] = $xYY;
		$resArray = array();
		$Sql = "SELECT report_gp_1_2.xOld,FORMAT(report_gp_1_2.xOld,2) AS sOld,
		report_gp_1_2.xNew,FORMAT(report_gp_1_2.xNew,2) AS sNew FROM report_gp_1_2";

		$result = mysql_query( $Sql );
		while ($Row = mysql_fetch_array($result))
		{
			array_push( $resArray,array("name"=>$Br[1],"y"=>$Row["xOld"],"drilldown"=>$Row["sOld"]));
			array_push( $resArray,array("name"=>$Br[2],"y"=>$Row["xNew"],"drilldown"=>$Row["sNew"]));
		}
mysql_close($meConnect);
		echo json_encode($resArray);
}
?>