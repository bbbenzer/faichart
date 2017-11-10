<?php 
require 'include/connect.php';
$result = mysql_query( "UPDATE chart_menu SET Chk = 5" );
mysql_close($meConnect);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Fai Bakery</title>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<style type="text/css">${demo.css}</style>
	<script type="text/javascript">
					$(function () {
						//getSomething();
					});
					
					function xBack(){
						window.location = "index.php";
					}


					function divFunction(){
						var mm = $("#xMonth").val();
						var yy = $("#xYear").val();
						var arr = {xMonth:mm, xYear:yy};
						$.ajax(
						   {
								url: "getGraph05.php",
								type: "POST",
								data: arr,
								dataType: 'json',
								async: false,
								success: function(data) {
								    var total = [];
									var datax = [];
									var namex = [];
									var i = 1;
									var n = 0;
									
								    $.each(data, function(key, val) {
										//alert(val.name + " :: " + parseFloat(val.y));
										total.push( {name:val.name,y:parseFloat(val.y)} );
										namex[n] = val.name;
										n++;
								    });
									datax[0] = total;
									getGraph( namex,datax );
									var url="ShowTable05.php";
									$.post(url,arr,function(data){
										$("#div1").html(data)
									});
								}
							}
						);
					}

					function getMonthThai( mm ){
								var MonthTH = "";
								switch( parseInt( mm ) ){
									case 1: MonthTH = "มกราคม";break;
									case 2: MonthTH = "กุมภาพันธ์";break;
									case 3: MonthTH = "มีนาคม";break;
									case 4: MonthTH = "เมษายน";break;
									case 5: MonthTH = "พฤษภาคม";break;
									case 6: MonthTH = "มิถุนายน";break;
									case 7: MonthTH = "กรกฎาคม";break;
									case 8: MonthTH = "สิงหาคม";break;
									case 9: MonthTH = "กันยายน";break;
									case 10: MonthTH = "ตุลาคม";break;
									case 11: MonthTH = "พฤศจิกายน";break;
									case 12: MonthTH = "ธันวาคม";break;
								}
								return MonthTH;
					}

					function getGraph( xName,Data ){
					// Create the chart
								var mm = $("#xMonth").val();
								var yy = $("#xYear").val();

								var mthai = getMonthThai( mm );
								
									  Highcharts.chart('container', {
												chart: {
													type: 'line'
												},
												title: {
													text: 'เปรียบเทียบ % ของเสีย '+(parseInt(yy)+543)+'/'+((parseInt(yy)+543)-1)
												},
												xAxis: {
													type: 'category',
													labels: {
														rotation: -55,
														style: {
															fontSize: '13px',
															fontFamily: 'Verdana, sans-serif'
														}
													},
													categories: [xName[0],xName[1],xName[2],xName[3],xName[4],xName[5],xName[6],xName[7],xName[8],xName[9],xName[10],xName[11],xName[12],xName[13],xName[14],xName[15] ]
												},
												yAxis: {
													title: {
														text: 'Percent ( % )'
													},
													plotLines: [{
														value: 0,
														width: 1,
														color: '#808080'
													}]
												},
												tooltip: {
													pointFormat: '{point.y:#,###.0f}'
												},
												series: [{
													name: 'Growth',
													data: Data[0]
												}]
										});
					// End Graph
					}
		</script>
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
	</head>
	<body>
		<script src="js/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<select id="xYear" >
			 <option value="2017">2017</option>
			 <option value="2016">2016</option>
		</select>
		<select id="xMonth" >
			 <option value="01">01</option>
			 <option value="02">02</option>
			 <option value="03">03</option>
			 <option value="04">04</option>
			 <option value="05">05</option>
			 <option value="06">06</option>
			 <option value="07">07</option>
			 <option value="08">08</option>
			 <option value="09">09</option>
			 <option value="10">10</option>
			 <option value="11">11</option>
			 <option value="12">12</option>
		</select>
		<button id="xgo" name="subject" type="button" value="go" onClick="divFunction()" >   GO  </button>
        <button id="xgo" name="subject" type="button" value="go" onClick="xBack()" >   Home  </button>

		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		<div id="div1"></div>
	</body>
</html>
