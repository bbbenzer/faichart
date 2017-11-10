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
		
	function gSaleTotal($cus_code,$mm,$yy){
		$xCol = "";
        for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($i)) . " , ";
        }
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= "";
		$n = 0;
		$xVal1 = 0;
		$Sql1 = "SELECT $xCol 
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy'";
		//echo $Sql1."<br>";
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			if($Row1["M01"]>0){
				$xVal1 += $Row1["M01"];
				$n++;
			}
			if($Row1["M02"]>0){
				$xVal1 += $Row1["M02"];
				$n++;
			}
			if($Row1["M03"]>0){
				$xVal1 += $Row1["M03"];
				$n++;
			}
			if($Row1["M04"]>0){
				$xVal1 += $Row1["M04"];
				$n++;
			}
			if($Row1["M05"]>0){
				$xVal1 += $Row1["M05"];
				$n++;
			}
			if($Row1["M06"]>0){
				$xVal1 += $Row1["M06"];
				$n++;
			}
			if($Row1["M07"]>0){
				$xVal1 += $Row1["M07"];
				$n++;
			}
			if($Row1["M08"]>0){
				$xVal1 += $Row1["M08"];
				$n++;
			}
			if($Row1["M09"]>0){	
				$xVal1 += $Row1["M09"];
				$n++;
			}
			if($Row1["M10"]>0){
				$xVal1 += $Row1["M10"];
				$n++;	
			}
			if($Row1["M11"]>0){
				$xVal1 += $Row1["M11"];
				$n++;
			}
			if($Row1["M12"]>0){	
				$xVal1 += $Row1["M12"];
				$n++;
			}
   		}
		//echo $xVal1/$n;
		return  $xVal1/$n;
	}
	
		$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,
				(
				SELECT data_sale_year.M$xMM
				FROM data_sale_year 
				WHERE data_sale_year.xYear = '".(intval($xYY)-1)."'
				AND data_sale_year.bCode = branch_fac.Cus_Code
				) AS d1,
				(
				SELECT data_sale_year.M$xMM 
				FROM data_sale_year 
				WHERE data_sale_year.xYear = '".$xYY."'
				AND data_sale_year.bCode = branch_fac.Cus_Code
				) AS d2
				FROM branch_fac
				WHERE branch_fac.Active = 1
				ORDER BY d2 DESC";
				
		//		echo $xSql;
		$result = mysql_query( $xSql );
		$i=0;
		while ($Row = mysql_fetch_array($result))
		{
			$vCus_Code[$i] = $Row["Cus_Code"];
			$vBranch_Name[$i] = $Row["Branch_Name"];
			$vd1[$i] = $Row["d1"];
			$vd2[$i] = $Row["d2"];
			$i++;
		}
		//mysql_close($meConnect);		
	}

	echo "<div>";
	echo "<TABLE>";
	echo "<TR>";

	
		echo "<TR>";
		echo "<TH width='450px' align='center' class='fb'>ปี</TH>";
		for($j=0;$j<$i;$j++){
			echo "<TH  width='250px' align='center' class='fbb' >$vBranch_Name[$j]</TH>";
		}
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD  align='center' class='fb'>$Br[1]</TD>";
		for($j=0;$j<$i;$j++){
			$rt = intval( $vCus_Code[$j] );
			echo "<TD align='center' class='fbb'>".number_format((float)$vd1[$j], 0, '.', ',')."</TD>";
		}
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD  align='center' class='fb'>$Br[2]</TD>";
		for($j=0;$j<$i;$j++){
			echo "<TD align='center' class='fbb'>".number_format((float)$vd2[$j], 0, '.', ',')."</TD>";
		}
		echo "</TR>";
		
		
		echo "<TR>";
		echo "<TD  align='center' class='fb'>%G</TD>";
		for($j=0;$j<$i;$j++){
			if( ( (float)$vd1[$j] ) > 0 )
				$ss = ( ( (float)$vd2[$j] - (float)$vd1[$j] ) / (float)$vd1[$j] ) * 100;
			if( (float)$ss > 0  )	
				echo "<TD align='center' style='color:blue' class='fbb'>".number_format($ss, 2, '.', ',')."%</TD>";
			else
				echo "<TD align='center' style='color:red' class='fbb'>".number_format($ss, 2, '.', ',')."%</TD>";
				
		}
		echo "</TR>";
	
	echo "<TABLE>";	
	echo "</div>";

?>    