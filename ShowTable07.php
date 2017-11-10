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
				
//				echo $xSql;
		$result = mysql_query( $xSql );
		$i=0;
		while ($Row = mysql_fetch_array($result))
		{
			$vBranch_Name[$i] = $Row["Branch_Name"];
			$vd1[$i] = $Row["d1"];
			$vd2[$i] = $Row["d2"];
			$i++;
		}
		mysql_close($meConnect);		
	}

	echo "<div>";
	echo "<TABLE>";

		echo "<TR>";
		echo "<TH width='450px' align='center' class='fb'>ปี</TH>";
		for($j=0;$j<$i;$j++){
			echo "<TH  width='250px' align='center' class='fbb'>$vBranch_Name[$j]</TH>";
		}
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD  align='center' class='fb'>$Br[1]</TD>";
		for($j=0;$j<$i;$j++){
			echo "<TD align='center' class='fbb'>".number_format((float)$vd1[$j], 2, '.', ',')."%</TD>";
		}
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD  align='center' class='fb'>$Br[2]</TD>";
		for($j=0;$j<$i;$j++){
			echo "<TD align='center' class='fbb'>".number_format((float)$vd2[$j], 2, '.', ',')."%</TD>";
		}
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD  align='center'></TD>";
		for($j=0;$j<$i;$j++){
			if( ((float)$vd1[$j] < (float)$vd2[$j]))
				echo "<TD align='center'><img src='images/arred.png'  width='25px' height='25px'></TD>";
			else
				echo "<TD align='center'><img src='images/argreen1.png' width='25px' height='25px'></TD>";
		}
		echo "</TR>";
		
	echo "<TABLE>";	
	echo "</div>";

?>    