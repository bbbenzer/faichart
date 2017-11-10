<?php
require 'include/connect.php';
$Chk=0;
$result = mysql_query( "SELECT Chk FROM chart_menu" );
while ($Row = mysql_fetch_array($result))
{
	$Chk=$Row["Chk"];
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fai Bakery</title> 
  <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

  <style>
		.button {
		  display: inline-block;
		  border-radius: 4px;
		  background-color: #0F93E7;
		  border: none;
		  color: #FFFFFF;
		  text-align: left;
		  font-size: 20px;
		  padding: 20px;
		  width: 550px;
		  transition: all 0.5s;
		  cursor: pointer;
		  margin: 5px;
		}

		.button span {
		  cursor: pointer;
		  display: inline-block;
		  position: relative;
		  transition: 0.5s;
		}

		.button span:after {
		  content: '\00bb';
		  position: absolute;
		  opacity: 0;
		  top: 0;
		  right: -20px;
		  transition: 0.5s;
		}

		.button:hover span {
		  padding-right: 25px;
		}

		.button:hover span:after {
		  opacity: 1;
		  right: 0;
		}

		.enjoy-css {
		  -webkit-box-sizing: content-box;
		  -moz-box-sizing: content-box;
		  box-sizing: content-box;
		  cursor: pointer;
		  border: none;
		  font: normal 72px/normal "Passero One", Helvetica, sans-serif;
		  color: rgba(15,2,2,1);
		  text-align: center;
		  -o-text-overflow: clip;
		  text-overflow: clip;
		  text-shadow: 0 1px 0 rgba(242,14,14,1) , 0 2px 0 rgba(14,10,252,1) , 0 3px 0 rgba(9,196,9,1) , 0 4px 0 rgba(247,227,12,1) , 0 5px 0 rgba(15,1,1,1) , 0 6px 1px rgba(0,0,0,0.0980392) , 0 0 5px rgba(0,0,0,0.0980392) , 0 1px 3px rgba(0,0,0,0.298039) , 0 3px 5px rgba(0,0,0,0.2) , 0 5px 10px rgba(0,0,0,0.247059) , 0 10px 10px rgba(0,0,0,0.2) , 0 20px 20px rgba(0,0,0,0.14902) ;
		  -webkit-transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1);
		  -moz-transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1);
		  -o-transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1);
		  transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1);
		}

		.enjoy-css:hover {
		  color: rgba(173,167,211,1);
		  text-shadow: 0 1px 0 rgba(255,255,255,1) , 0 2px 0 rgba(255,255,255,1) , 0 3px 0 rgba(255,255,255,1) , 0 4px 0 rgba(255,255,255,1) , 0 5px 0 rgba(255,255,255,1) , 0 6px 1px rgba(0,0,0,0.0980392) , 0 0 5px rgba(0,0,0,0.0980392) , 0 1px 3px rgba(0,0,0,0.298039) , 0 3px 5px rgba(0,0,0,0.2) , 0 -5px 10px rgba(0,0,0,0.247059) , 0 -7px 10px rgba(0,0,0,0.2) , 0 -15px 20px rgba(0,0,0,0.14902) ;
		  -webkit-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1) 10ms;
		  -moz-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1) 10ms;
		  -o-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1) 10ms;
		  transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1) 10ms;
		}
}
  </style>
  <script src="js/jquery-1.12.4.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script>
  	  function c01(e){
			switch(e){
				  case 1:window.location = "report_gp_01.php";break;
				  case 2:window.location = "report_gp_02.php";break;
				  case 3:window.location = "report_gp_06.php";break;
				  case 4:window.location = "report_gp_07.php";break;
				  case 5:window.location = "report_gp_08.php";break;
				  case 6:window.location = "report_gp_05.php";break;
				  case 7:window.location = "report_gp_09.php";break;
				  case 8:window.location = "report_gp_10.php";break;
				  case 9:window.location = "report_gp_11.php";break;
				  case 10:window.location = "report_gp_10.php";break;
				  case 11:window.location = "report_gp_11.php";break;
				  case 12:window.location = "report_gp_12.php";break;
			}
	  }
  </script>
</head>
<body>



<div align="center">
<img src="images/failogo.jpg" width="280px" height="230px">
<br />
<div <span style="font-size:50px">รายงานสรุป ฝ้ายเบเกอรี่</span></div>
    	<table width="800" border="0">
  <tbody>
    <tr>
      <td class="l01">
          <button class="button" style="vertical-align:middle" onClick="c01(1)">

                <span style="cursor:pointer">
                <? if($Chk==1){ ?>
                	<img src="images/check01.png" id="im02" width="25px" height="25px">
				<? } ?>
                1. กราฟ ยอดขายประจำเดือน
                </span>

          </button>
      </td>
      <td>

        <button class="button" style="vertical-align:middle" onClick="c01(5)">
      		<span style="cursor:pointer">
            <? if($Chk==5){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
            5. กราฟ Growth เปอร์เซนต์การโตยอดขายแต่ละสาขา</span>
        </button>
      </td>
    </tr>
    <tr>
      <td>
      	<button class="button" style="vertical-align:middle" onClick="c01(2)">
      		<span style="cursor:pointer">
            <? if($Chk==2){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
            2. กราฟ เปรียบเทียบยอดขายรวมทุกสาขา ประจำเดือน</span>
        </button>
      </td>
      <td>

          <button class="button" style="vertical-align:middle" onClick="c01(9)">
            <span style="cursor:pointer">
                <? if($Chk==9){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
                6. กราฟ เปรียบเทียบยอดขาย การตลาด ต่อเดือน</span>
          </button>
      </td>
    </tr>
    <tr>
      <td>
      	  <button class="button" style="vertical-align:middle" onClick="c01(3)">
      		<span style="cursor:pointer">
            <? if($Chk==3){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
            3. กราฟ เปรียบเทียบยอดขายสาขา</span>
          </button>
      </td>
      <td>
            <button class="button" style="vertical-align:middle" onClick="c01(7)">
               <span style="cursor:pointer">
                <? if($Chk==7){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
                7. กราฟ เปรียบเทียบยอดขาย สายส่ง ต่อเดือน</span
            ></button>

      </td>
    </tr>
    <tr>
      <td>
        <button class="button" style="vertical-align:middle" onClick="c01(4)">
      		<span style="cursor:pointer">
            <? if($Chk==4){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
            4. กราฟ เปรียบเทียบ % ของเสีย สาขาต่อเดือน</span>
        </button>
      </td>
      <td>
        <button class="button" style="vertical-align:middle" onClick="c01(8)">
      		<span style="cursor:pointer">
            <? if($Chk==8){ ?><img src="images/check01.png" id="im02" width="25px" height="25px"> <? } ?>
            8. กราฟ เปรียบเทียบยอดขาย เซเว่น ต่อเดือน</span>
        </button>
      </td>
    </tr>

  </tbody>
</table>

    </div>

 </body>
</html>
