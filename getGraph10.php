<?php
//session_start();
require 'include/connect.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$xMM = $_POST['xMonth'];
		$xYY = $_POST['xYear'];
		
		//$xMM = '12';
		//$xYY = '2017';
		
		$Br[1] = (intval($xYY)-1);
		$Br[2] = $xYY;
		$resArray = array();
		
		$xOle = "";
        $xNew = "";
        for($i=1;$i<=intval($xMM);$i++){
        	$xOle .= "(
				SELECT data_sale_year.M". sprintf("%02d", $i) . "
				FROM data_sale_year 
				WHERE data_sale_year.xYear = '".(intval($xYY)-1)."'
				AND data_sale_year.bCode = branch_fac.Cus_Code
				) AS od$i,";
			
        	$xNew .= "(
				SELECT data_sale_year.M". sprintf("%02d", $i) . "
				FROM data_sale_year 
				WHERE data_sale_year.xYear = '".$xYY."'
				AND data_sale_year.bCode = branch_fac.Cus_Code
				) AS nd$i,";
        }
		$xOle = substr($xOle,0,strlen($xOle) -1);
        $xOle .= "";
		$xNew = substr($xNew,0,strlen($xNew) -1);
        $xNew .= "";

		$xSql = "SELECT branch_fac.Cus_Code,branch_fac.Branch_Name,
				$xOle,$xNew FROM branch_fac
				WHERE branch_fac.Active2 = 1
				ORDER BY branch_fac.Branch_Name ASC";
				
		//echo 	$xSql."<br>";
			
		$result = mysql_query( $xSql );
		while ($Row = mysql_fetch_array($result))
		{
			array_push( $resArray,
				array("name"=>$Row["Branch_Name"],"year"=>$Row["xYear"],
				      "od1"=>$Row["od1"],"od2"=>$Row["od2"],"od3"=>$Row["od3"],"od4"=>$Row["od4"],
					  "od5"=>$Row["od5"],"od6"=>$Row["od6"],"od7"=>$Row["od7"],"od8"=>$Row["od8"],
					  "od9"=>$Row["od9"],"od10"=>$Row["od10"],"od11"=>$Row["od11"],"od12"=>$Row["od12"],
				      "nd1"=>$Row["nd1"],"nd2"=>$Row["nd2"],"nd3"=>$Row["nd3"],"nd4"=>$Row["nd4"],
					  "nd5"=>$Row["nd5"],"nd6"=>$Row["nd6"],"nd7"=>$Row["nd7"],"nd8"=>$Row["nd8"],
					  "nd9"=>$Row["nd9"],"nd10"=>$Row["nd10"],"nd11"=>$Row["nd11"],"nd12"=>$Row["nd12"]
				) 
			);
		}

mysql_close($meConnect);
	echo json_encode($resArray);

}
?>