<link rel="stylesheet" type="text/css" href="css/block.css">

<?php
    require 'include/connect.php';
	require 'include/xFunction.php';
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
				$vBr[1] = $Row["xS01"];
				$vBr[2] = $Row["xS02"];
				$vBr[3] = $Row["xS03"];
				$vBr[4] = $Row["xS04"];
				$vBr[5] = $Row["xS05"];
				$xV711 = $Row["xS06"] - ($Row["xS06"]*7)/107;
				$xV45 = $xV711 - ($xV711*45)/100;
				$vBr[6] = $xV45;
			}
		mysql_close($meConnect);		
	}


	echo "<div align='center'>";
	echo "<TABLE>";
	echo "<TR>";
	echo "<TH width='200px'></TH>";
	for($j=1;$j<7;$j++){
		echo "<TH width='200px' align='center'>$Br[$j]</TH>";
	}
	echo "</TR>";
	
	echo "<TR>";
	echo "<TD width='100px'>ยอดขาย</TD>";
	for($j=1;$j<7;$j++){
		echo "<TD width='100px' align='center'>".number_format((float)$vBr[$j], 0, '.', ',')."</TD>";
	}
	echo "</TR>";
	
	echo "<TABLE>";	
	echo "</div>";
?>    