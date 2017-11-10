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
	$xDateTime = new xDateTime();
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];
	}
	$aoDate = intval($xYY)."-$xMM-01";
	$anDate = intval($xYY)."-$xMM-01";
	
	$gosDate = (intval($xYY)-1)."-01-01";
	$goeDate = (intval($xYY)-1)."-".$xMM."-01";
	$goeDate = date("Y-m-t", strtotime($goeDate));
	
	$gnsDate = intval($xYY)."-01-01";
	$gneDate = intval($xYY)."-".$xMM."-01";
	$gneDate = date("Y-m-t", strtotime($gneDate));

	function xOTALL($cus_code){
			$xVal1 = 0;
			$Sql1 = "SELECT COUNT(*) AS Cnt FROM customer AS cs1 
					INNER JOIN customer AS cs2 ON cs1.ChildOf = cs2.Cus_Code 
					WHERE cs1.ChildOf = '$cus_code'";
					
			$result1 = mysql_query( $Sql1 );
			while ($Row1 = mysql_fetch_array($result1)){
				$xVal1 = $Row1["Cnt"];
   			 }
			return $xVal1;
	}
	
	function xOTACTIVE($cus_code,$sDate){
		$xVal1 = 0;
		$Sql1 = "SELECT cs1.Cus_Code AS Cnt
				FROM customer AS cs1
				INNER JOIN customer AS cs2 ON cs1.ChildOf = cs2.Cus_Code
				INNER JOIN sale ON cs1.Cus_Code = sale.Cus_Code
				WHERE cs1.ChildOf = '$cus_code'
				AND sale.DocDate BETWEEN '$sDate' 
				AND '".date("Y-m-t", strtotime($sDate))."'
				GROUP BY cs1.Cus_Code";
				
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1++;
   		}
		return $xVal1;
	}	

	function xOTNEW($cus_code,$sDate){
			$xVal1 = 0;
			$Sql1 = "SELECT cs1.Cus_Code
					FROM customer AS cs1 
					INNER JOIN customer AS cs2 ON cs1.ChildOf = cs2.Cus_Code 
					WHERE cs1.ChildOf = '$cus_code'
					AND cs1.StratDate BETWEEN '$sDate' 
					AND '".date("Y-m-t", strtotime($sDate))."'
					GROUP BY cs1.Cus_Code";
			$result1 = mysql_query( $Sql1 );
			while ($Row1 = mysql_fetch_array($result1)){
				$xVal1++;
   			 }
			 
			return $xVal1;
	}
	
	function xGrowth($cus_code,$mm,$yy){
		$xCol = "( ";
        for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($i)) . " + ";
        }
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= " ) ";

		$xVal1 = 0;
		$Sql1 = "SELECT (
				(SELECT $xCol
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy')
				-
				(SELECT $xCol
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '".(intval($yy)-1)."') )
				/
				(SELECT $xCol
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '".(intval($yy)-1)."') * 100 AS Cnt";

		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = number_format($Row1["Cnt"], 0);
   		}
		return $xVal1;
	}
	
	function xGrowthAll($cus_code,$mm,$yy){
		$xCol = "( ";
        for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($i)) . " + ";
        }
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= " ) ";
		
		
		$xVal1 = 0;
		$Sql1 = "SELECT (
				(SELECT $xCol
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy')
				-
				(SELECT $xCol
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '".(intval($yy)-1)."') )
				/
				(SELECT $xCol
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '".(intval($yy)-1)."') * 100 AS Cnt";
		
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = number_format($Row1["Cnt"], 0);
   		}
		return $xVal1;
	}
	
	function xSaleTotal($cus_code,$mm,$yy){
		$xCol = "( ";
        //for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($mm)) . " + ";
        //}
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= " ) ";

		$xVal1 = 0;
		$Sql1 = "SELECT $xCol AS Cnt
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy'";
		
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = $Row1["Cnt"];
   		}
		
		return  $xVal1;
	}
	
	function gSaleTotal($cus_code,$mm,$yy){
		$xCol = "( ";
        for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($i)) . " + ";
        }
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= " ) ";

		$xVal1 = 0;
		$Sql1 = "SELECT $xCol AS Cnt
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy'";
		//echo $Sql1."<br>";
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = $Row1["Cnt"];
   		}
		
		return  $xVal1;
	}
	
	function xSaleTotal711($cus_code,$mm,$yy){
		$xCol = "( ";
        //for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($mm)) . " + ";
        //}
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= " ) ";
		
		
		$xVal1 = 0;
		$Sql1 = "SELECT $xCol AS Cnt
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy'";
		
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = $Row1["Cnt"];
   		}
		
		$xV711 = ($xVal1*100)/107;
		$xV45 = $xV711*0.45;
		return  $xV45;
	}
	
	function gSaleTotal711($cus_code,$mm,$yy){
		$xCol = "( ";
        for($i=1;$i<=intval($mm);$i++){
        	$xCol .= "data_sale_year.M" . sprintf("%02d", intval($i)) . " + ";
        }
		$xCol = substr($xCol,0,strlen($xCol) -2);
        $xCol .= " ) ";
		
		
		$xVal1 = 0;
		$Sql1 = "SELECT $xCol AS Cnt
				FROM data_sale_year 
				WHERE data_sale_year.bCode = '$cus_code' 
				AND data_sale_year.xYear = '$yy'";
		
		$result1 = mysql_query( $Sql1 );
		while ($Row1 = mysql_fetch_array($result1)){
			$xVal1 = $Row1["Cnt"];
   		}
		
		$xV711 = ($xVal1*100)/107;
		$xV45 = $xV711*0.45;
		return  $xV45;
	}
	
	$i=0;
	$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,branch_fac.gId
			FROM branch_fac
			WHERE branch_fac.gId = 3
			ORDER BY branch_fac.Branch_Name ASC";
	$result = mysql_query( $xSql );
	while ($Row = mysql_fetch_array($result)){
		$xCode[$i] = $Row["Cus_Code"];
		$xVal[$i] = $Row["Branch_Name"];
		$i++;
    }

	echo "<div>";
	echo "<table>";
	echo "<tr>";
	echo "<th width='300px' class='fb'>$xVal[0]</th>";
	for($j=1;$j<13;$j++){
		echo "<th class='fb' width='200px' align='center'>".$xDateTime->getMonthTH($j)."</th>";
	}
	echo "<th class='fb' width='200px' align='center'>@MTH</th>";
	echo "</tr>";

	echo "<tr>";
	echo "<td width='200px' class='fb'>ยอดขาย ".(intval($xYY)-1)."</td>";
	for($j=1;$j<13;$j++){
		echo "<td  class='fb' width='200px' align='center'>".number_format(xSaleTotal($xCode[0],$j,(intval($xYY)-1)),0)."</td>";
	}
	echo "<td  class='fb' width='200px' align='center'>".number_format(gSaleTotal($xCode[0],12,(intval($xYY)-1))/12,0)."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td width='200px' class='fb'>ยอดขาย ".intval($xYY)."</td>";
	for($j=1;$j<13;$j++){
		echo "<td  class='fb' width='200px' align='center'>".
		number_format(xSaleTotal711($xCode[0],$j,$xYY),0)
		."</td>";
	}
	echo "<td  class='fb' width='200px' align='center'>". number_format( gSaleTotal711($xCode[0],$xMM,$xYY)/intval($xMM),0) ."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td width='200px' class='fb'>%G ม.ค. - ".$xDateTime->getMonthTH($xMM)."</td>";
	for($j=1;$j<13;$j++){
		
		$gr = number_format( 
			(
				(gSaleTotal711($xCode[0],$j,$xYY)
					-
				gSaleTotal($xCode[0],$j,(intval($xYY)-1))
			)
			/
			gSaleTotal($xCode[0],$j,(intval($xYY)-1)))*100,2);
		
		if( intval($gr)  > 0 )
			echo "<td style='color:blue' width='200px' align='center' class='fb'>".$gr."%</td>";
		else
			echo "<td style='color:red' width='200px' align='center' class='fb'>".$gr."%</td>";
	}
	
	$gr = number_format( ((gSaleTotal711($xCode[0],$xMM,$xYY)-gSaleTotal($xCode[0],$xMM,(intval($xYY)-1)))/gSaleTotal($xCode[0],$xMM,(intval($xYY)-1)))*100,2);
		if( intval($gr)  > 0 )
			echo "<td style='color:blue' width='200px' align='center' class='fb'>".$gr."%</td>";
		else
			echo "<td style='color:red' width='200px' align='center' class='fb'>".$gr."%</td>";
	echo "</tr>";

	echo "<table>";	
	echo "</div>";

?> 


