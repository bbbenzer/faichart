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
        	$xOrL .= "data_report_sale_year_.xMM = '" . sprintf("%02d", $i) . "' OR ";
        }
		$xOr = substr($xOr,0,strlen($xOr) -3);
        $xOr .= " )";
		$xOrL = substr($xOrL,0,strlen($xOrL) -3);
        $xOrL .= " )";
		
		$iMM = (intval($xMM));
		$OleYear = (intval($xYY)-1);	
			
		$xSql = "SELECT ( 
				SELECT SUM(data_report_sale_year.M01)
				FROM data_report_sale_year
				WHERE data_report_sale_year.xYear = '$OleYear'
				AND  $xOr 
				) AS xOle,
				(
				SELECT SUM(data_report_sale_year.M01)
				FROM data_report_sale_year
				WHERE data_report_sale_year.xYear = '$xYY' 
				AND  $xOr
				) AS xNew";
		//echo 	$xSql;	
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
		echo "<TD width='100px' align='center'>".number_format((float)$vBr[$j], 0, '.', ',')."</TD>";
	}
	echo "</TR>";
	
	echo "<TABLE>";	
	echo "</div>";
?>    