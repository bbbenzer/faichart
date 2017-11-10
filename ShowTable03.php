<link rel="stylesheet" type="text/css" href="css/block.css">

<?php
    require 'include/connect.php';
	require 'include/xFunction.php';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];

		$Br[1] = (intval($xYY)-1);
		$Br[2] = $xYY;
		$resArray = array();
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
			$vBr[1] = $Row["xOle"];
			$vBr[2] = $Row["xNew"];
		}
		mysql_close($meConnect);		
	}


	echo "<div style='width:400px'>";
	echo "<TABLE>";
	echo "<TR>";
	echo "<TH width='200px'></TH>";
	for($j=1;$j<3;$j++){
		echo "<TH width='100px' align='center'>$Br[$j]</TH>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='200px'>ยอดขาย</TD>";
	for($j=1;$j<3;$j++){
		echo "<TD width='100px' align='center'>".number_format((float)$vBr[$j], 2, '.', ',')."</TD>";
	}
	echo "</TR>";
	
	echo "<TABLE>";	
	echo "</div>";
?>    