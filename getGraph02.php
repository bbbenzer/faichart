<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];

		$Br[1] = (intval($xYY)-1);
		$Br[2] = $xYY;
		$resArray = array();
		$xOr = "";
        $xOrL = "";
        for($i=1;$i<=intval($xMM);$i++){
        	$xOr .= "data_sale_year.M = '" . sprintf("%02d", $i) . "' + ";
        	$xOrL .= "data_sale_year.bM = '" . sprintf("%02d", $i) . "' + ";
        }
		$xOr = substr($xOr,0,strlen($xOr) -3);
        $xOr .= "";
		$xOrL = substr($xOrL,0,strlen($xOrL) -3);
        $xOrL .= "";

		$xSql = "SELECT 
				(
				SELECT	SUM(data_sale_year.M$xMM)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 1 
				AND branch_fac.Active = 1 
				AND data_sale_year.xYear = '$xYY'
				) AS xNew,
				(
				SELECT	SUM(data_sale_year.M$xMM)
				FROM data_sale_year
				INNER JOIN branch_fac ON data_sale_year.bCode = branch_fac.Cus_Code
				WHERE branch_fac.gId = 1
				AND data_sale_year.xYear = '".(intval($xYY)-1)."'
				) AS xOle
				";
				
		$result = mysql_query( $xSql );
		while ($Row = mysql_fetch_array($result))
		{
			$xOle = $Row["xOle"];
			$xNew = $Row["xNew"];
		}
		array_push( $resArray,array("name"=>$Br[1],"y"=>$xOle,"drilldown"=>$xOle));
		array_push( $resArray,array("name"=>$Br[2],"y"=>$xNew,"drilldown"=>$xNew));
mysql_close($meConnect);
		echo json_encode($resArray);

}
?>