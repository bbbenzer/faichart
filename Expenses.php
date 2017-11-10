<?php
//session_start();
require 'include/connect.php';
		$xMM = '02';
		$xYY = '2017';


		function square($mm,$tc,$br)
		{
			$sDate = date('Y-'.$mm.'-01'); 
			$eDate  = date('Y-'.$mm.'-t');
			$xSql = "SELECT SUM(expenses.Amount) AS Amount 
					FROM expenses 
					LEFT JOIN supplier ON expenses.Sup_Code = supplier.Sup_Code 
					LEFT JOIN branch_acc ON expenses.bCode = branch_acc.Branch_Code 
					LEFT JOIN check_type_detail ON expenses.BillType = check_type_detail.Chk_TD_Code 
					LEFT JOIN check_type ON check_type_detail.Chk_T_Code = check_type.Chk_T_Code 
					WHERE expenses.Modify_Code = $br
					AND expenses.NoDate BETWEEN '$sDate' AND '$eDate'
					AND check_type.Chk_T_Code  = $tc";
			$result = mysql_query( $xSql );
			while ($Row = mysql_fetch_array($result)){
				$xVal = $Row["Amount"];
			}
					
			return $xVal;
		}		
		
		function squareCheck($mm,$tc,$br)
		{
			$sDate = date('Y-'.$mm.'-01'); 
			$eDate  = date('Y-'.$mm.'-t');
			$xSql = "SELECT SUM(checkout.Amount) AS Amount 					
					FROM checkout 
					LEFT JOIN supplier ON checkout.Sup_Code = supplier.Sup_Code 
					LEFT JOIN branch_acc ON checkout.bCode = branch_acc.Branch_Code 
					LEFT JOIN check_type_detail ON checkout.BillType = check_type_detail.Chk_TD_Code 
					LEFT JOIN check_type ON check_type_detail.Chk_T_Code = check_type.Chk_T_Code  
					WHERE checkout.Modify_Code = $br
					AND checkout.NoDate BETWEEN '$sDate' AND '$eDate'
					AND check_type.Chk_T_Code  = $tc";
			$result = mysql_query( $xSql );
			while ($Row = mysql_fetch_array($result)){
				$xVal = $Row["Amount"];
			}
					
			return $xVal;
		}
		//echo square($xMM,'22');
?>
<html>
<head>
<title>Thai UTF8</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body>

<table border="1">
 <thead>
  <tr>
     <th colspan="8">ผลต่างค่าใช้จ่ายเบ็ดเตล็ด</th>
  </tr>
  <tr>
     <th rowspan="2" width="500px">ประเภท</th>
     <th colspan="3">บจก.</th>
     <th colspan="3">หจก.</th>
     <th rowspan="2" width="500px">หมายเหตุ</th>
  </tr>
    <tr>
	 <th width="200px">มกราคม</th>
     <th width="200px">กุมภาพันธ์</th>
     <th width="200px">ผลต่าง</th>
     <th width="200px">มกราคม</th>
     <th width="200px">กุมภาพันธ์</th>
     <th width="200px">ผลต่าง</th>
  </tr>
 </thead>
 <tbody>
<?
	$xSql = "SELECT Chk_T_Code,Chk_T_Name FROM check_type  ORDER BY Chk_T_Name ASC";				
	$result = mysql_query( $xSql );
	while ($Row = mysql_fetch_array($result)){
?>
<tr>
	<td> <?= $Row["Chk_T_Name"] ?></td>
    <td align="right"><?= number_format(square('01',$Row["Chk_T_Code"],52)) ?></td>
    <td align="right"><?= number_format(square('02',$Row["Chk_T_Code"],52)) ?></td>
    <? if(( square('01',$Row["Chk_T_Code"],52) - square('02',$Row["Chk_T_Code"],52) ) < 0 ){ ?>
    	<td align="right">
			<p style="color:red;">
			<?= number_format( square('01',$Row["Chk_T_Code"],52) - square('02',$Row["Chk_T_Code"],52) ) ?>
            <?
            	$xVl01 +=( square('01',$Row["Chk_T_Code"],52) - square('02',$Row["Chk_T_Code"],52) );
			?>
            </p>
        </td>
    <? }else{ ?>
    	<td align="right">
            <p style="color:blue;">
            <?= number_format( square('01',$Row["Chk_T_Code"],52) - square('02',$Row["Chk_T_Code"],52) ) ?>
            </p>
        </td>
	<? } ?>
    <td align="right"><?= number_format(square('01',$Row["Chk_T_Code"],55)) ?></td>
    <td align="right"><?= number_format(square('02',$Row["Chk_T_Code"],55)) ?></td>
    <? if(( square('01',$Row["Chk_T_Code"],55) - square('02',$Row["Chk_T_Code"],55) ) < 0 ){ ?>
    	<td align="right">
			<p style="color:red;">
			<?= number_format( square('01',$Row["Chk_T_Code"],55) - square('02',$Row["Chk_T_Code"],55) ) ?>
            <?
            	$xVl02 +=( square('01',$Row["Chk_T_Code"],55) - square('02',$Row["Chk_T_Code"],55) );
			?>
            </p>
        </td>
    <? }else{ ?>
    	<td align="right">
            <p style="color:blue;">
            <?= number_format( square('01',$Row["Chk_T_Code"],55) - square('02',$Row["Chk_T_Code"],55) ) ?>
            </p>
        </td>
	<? } ?>
    
    <td> </td>
</tr>
<? } ?>
</tbody>
<tfoot>
	<tr>
		<td> </td>
        <td> </td>
        <td> </td>
        <td align="right"> <p style="color:red;"><?= number_format($xVl01) ?> </p></td>
        <td> </td>
        <td> </td>
        <td align="right"> <p style="color:red;"><?= number_format($xVl02) ?> </p></td>
        <td> </td>
    </tr>
</tfoot>
</table>

<br>
<?
	$xVl01 = 0;
	$xVl02 = 0;
?>

<table border="1">
 <thead>
  <tr>
     <th colspan="8">ผลต่างค่าใช้จ่ายเช็ค</th>
  </tr>
  <tr>
     <th rowspan="2" width="500px">ประเภท</th>
     <th colspan="3">บจก.</th>
     <th colspan="3">หจก.</th>
     <th rowspan="2" width="500px">หมายเหตุ</th>
  </tr>
    <tr>
	 <th width="200px">มกราคม</th>
     <th width="200px">กุมภาพันธ์</th>
     <th width="200px">ผลต่าง</th>
     <th width="200px">มกราคม</th>
     <th width="200px">กุมภาพันธ์</th>
     <th width="200px">ผลต่าง</th>
  </tr>
 </thead>
 <tbody>
<?
	$xSql = "SELECT Chk_T_Code,Chk_T_Name FROM check_type  ORDER BY Chk_T_Name ASC";				
	$result = mysql_query( $xSql );
	while ($Row = mysql_fetch_array($result)){
?>
<tr>
	<td> <?= $Row["Chk_T_Name"] ?></td>
    <td align="right"><?= number_format(squareCheck('01',$Row["Chk_T_Code"],52)) ?></td>
    <td align="right"><?= number_format(squareCheck('02',$Row["Chk_T_Code"],52)) ?></td>
    <? if(( squareCheck('01',$Row["Chk_T_Code"],52) - squareCheck('02',$Row["Chk_T_Code"],52) ) < 0 ){ ?>
    	<td align="right">
			<p style="color:red;">
			<?= number_format( squareCheck('01',$Row["Chk_T_Code"],52) - squareCheck('02',$Row["Chk_T_Code"],52) ) ?>
            <?
            	$xVl01 +=( squareCheck('01',$Row["Chk_T_Code"],52) - squareCheck('02',$Row["Chk_T_Code"],52) );
			?>
            </p>
        </td>
    <? }else{ ?>
    	<td align="right">
            <p style="color:blue;">
            <?= number_format( squareCheck('01',$Row["Chk_T_Code"],52) - squareCheck('02',$Row["Chk_T_Code"],52) ) ?>
            </p>
        </td>
	<? } ?>
    <td align="right"><?= number_format(squareCheck('01',$Row["Chk_T_Code"],55)) ?></td>
    <td align="right"><?= number_format(squareCheck('02',$Row["Chk_T_Code"],55)) ?></td>
    <? if(( squareCheck('01',$Row["Chk_T_Code"],55) - squareCheck('02',$Row["Chk_T_Code"],55) ) < 0 ){ ?>
    	<td align="right">
			<p style="color:red;">
			<?= number_format( squareCheck('01',$Row["Chk_T_Code"],55) - squareCheck('02',$Row["Chk_T_Code"],55) ) ?>
            <?
            	$xVl02 +=( squareCheck('01',$Row["Chk_T_Code"],55) - squareCheck('02',$Row["Chk_T_Code"],55) );
			?>
            </p>
        </td>
    <? }else{ ?>
    	<td align="right">
            <p style="color:blue;">
            <?= number_format( squareCheck('01',$Row["Chk_T_Code"],55) - squareCheck('02',$Row["Chk_T_Code"],55) ) ?>
            </p>
        </td>
	<? } ?>
    
    <td> </td>
</tr>
<? } ?>
</tbody>
<tfoot>
	<tr>
		<td> </td>
        <td> </td>
        <td> </td>
        <td align="right"> <p style="color:red;"><?= number_format($xVl01) ?> </p></td>
        <td> </td>
        <td> </td>
        <td align="right"> <p style="color:red;"><?= number_format($xVl02) ?> </p></td>
        <td> </td>
    </tr>
</tfoot>
</table>
</body>
</html>
