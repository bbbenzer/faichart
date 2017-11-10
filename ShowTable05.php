<link rel="stylesheet" type="text/css" href="css/block.css">
<style type="text/css">
body{
    font-size:12px; 
}
.textAlignVer{
    display:block;
    writing-mode: tb-rl;
    filter: flipv fliph;
    -webkit-transform: rotate(-90deg); 
    -moz-transform: rotate(-90deg); 
    transform: rotate(-90deg); 
    position:relative;
    width:20px;
    white-space:nowrap;
    font-size:12px;
    margin-bottom:10px;
}
		.fb{font-size:18px;}
		.fbb{font-weight:bold;}
</style>
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
        	$xOr .= "data_sale_year.M" . sprintf("%02d", $i) . " + ";
        	$xOrL .= "data_sale_year.bM" . sprintf("%02d", $i) . " + ";
        }
		$xOr = substr($xOr,0,strlen($xOr) -3);
        $xOr .= " )";
		$xOrL = substr($xOrL,0,strlen($xOrL) -3);
        $xOrL .= " )";
		
		$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,
				(
					(
						(
							SELECT $xOrL
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . (intval($xYY)-1) . "'
						   AND data_sale_year.bCode = branch_fac.Cus_Code 
							) /(
							SELECT $xOr
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . (intval($xYY)-1) . "'
						   AND data_sale_year.bCode = branch_fac.Cus_Code 
							) 
					)*100
				) 
				-
				(
					(
						(
							SELECT $xOrL
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . $xYY . "'
						   AND data_sale_year.bCode = branch_fac.Cus_Code
						)/(
							SELECT $xOr
							FROM data_sale_year
							WHERE data_sale_year.xYear = '" . $xYY . "'
						   AND data_sale_year.bCode = branch_fac.Cus_Code
						)
					)*100
				) AS dd
				FROM branch_fac
				WHERE branch_fac.Active = 1
				ORDER BY dd ASC";
				
		$result = mysql_query( $xSql );
		$i=0;
		while ($Row = mysql_fetch_array($result))
		{
			$vBranch_Name[$i] = $Row["Branch_Name"];
			$vd1[$i] = $Row["dd"];
			$i++;
		}
		mysql_close($meConnect);		
	}

	echo "<div>";
	echo "<TABLE>";

		echo "<TR>";
		echo "<TH width='450px' align='center'>ปี</TH>";
		for($j=0;$j<$i;$j++){
			echo "<TH  width='250px' align='center'>$vBranch_Name[$j]</TH>";
		}
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD  align='center'>% G</TD>";
		for($j=0;$j<$i;$j++){
			if( (float)$vd1[$j] > 0 )
				echo "<TD align='center' style='color:red' class='fbb'>".number_format( (float)$vd1[$j]  , 2, '.', ',')."</TD>";
			else
				echo "<TD align='center' style='color:blue' class='fbb'>".number_format( (float)$vd1[$j]  , 2, '.', ',')."</TD>";
		}
		echo "</TR>";
		
	echo "<TABLE>";	
	echo "</div>";

?>    