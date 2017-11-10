<?php
require 'include/connect.php';
$result = mysql_query( "UPDATE chart_menu SET Chk = 4" );
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
								url: "getGraph07.php",
								type: "POST",
								data: arr,
								dataType: 'json',
								async: false,
								success: function(data) {
								    var total1 = [];
									var total2 = [];
									var datax = [];
									var namex = [];
									var i = 1;
									var n = 0;

								    $.each(data, function(key, val) {
										total1.push( {name:val.name,y:parseFloat(val.d1)} );
										total2.push( {name:val.name,y:parseFloat(val.d2)} );
										namex[n] = val.name;
										n++;
								    });
									datax[0] = total1;
									datax[1] = total2;
									getGraph( namex,datax );
									var url="ShowTable07.php";
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
						console.log(Data);
					// Create the chart
								var mm = $("#xMonth").val();
								var yy = $("#xYear").val();

								var mthai = getMonthThai( mm );

									  Highcharts.chart('container', {
												chart: {
													type: 'column'
												},
												title: {
													text: 'เปรียบเทียบ % ของเสีย สาขาต่อเดือน'
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
													min: 0,
													title: {
														text: ''
													}
												},
												tooltip: {
													pointFormat: '{point.y:#,###.0f}'
												},
												series: [{
													name: '2559',
													data: Data[0]
												}, {
													name: '2560',
													data: Data[1]
												}]
										});
					// End Graph
					}
		</script>
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
