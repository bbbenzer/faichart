<?php 
require 'include/connect.php';
$result = mysql_query( "UPDATE chart_menu SET Chk = 8" );
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
								url: "getGraph10.php",
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
									var V711=0;
									var V45=0;
									
								    $.each(data, function(key, val) {
										/*
										total1.push( {name:'มกราคม',y:parseFloat(val.od1)} );
										total1.push( {name:'กุมภาพันธ์',y:parseFloat(val.od2)} );
										total1.push( {name:'มีนาคม',y:parseFloat(val.od3)} );
										total1.push( {name:'เมษายน',y:parseFloat(val.od4)} );
										total1.push( {name:'เมษายน',y:parseFloat(val.od5)} );
										total1.push( {name:'',y:parseFloat(val.od6)} );
										total1.push( {name:'',y:parseFloat(val.od7)} );
										total1.push( {name:'',y:parseFloat(val.od8)} );
										total1.push( {name:'',y:parseFloat(val.od9)} );
										total1.push( {name:'',y:parseFloat(val.od10)} );
										total1.push( {name:'',y:parseFloat(val.od11)} );
										total1.push( {name:'',y:parseFloat(val.od12)} );
										total2.push( {name:'มกราคม',y:parseFloat(val.nd1)} );
										total2.push( {name:'กุมภาพันธ์',y:parseFloat(val.nd2)} );
										total2.push( {name:'มีนาคม',y:parseFloat(val.nd3)} );
										total2.push( {name:'เมษายน',y:parseFloat(val.nd4)} );
										total2.push( {name:'เมษายน',y:parseFloat(val.nd5)} );
										total2.push( {name:'',y:parseFloat(val.nd6)} );
										total2.push( {name:'',y:parseFloat(val.nd7)} );
										total2.push( {name:'',y:parseFloat(val.nd8)} );
										total2.push( {name:'',y:parseFloat(val.nd9)} );
										total2.push( {name:'',y:parseFloat(val.nd10)} );
										total2.push( {name:'',y:parseFloat(val.nd11)} );
										total2.push( {name:'',y:parseFloat(val.nd12)} );
										*/
										total1.push( parseFloat(val.od1) );
										total1.push( parseFloat(val.od2) );
										total1.push( parseFloat(val.od3) );
										total1.push( parseFloat(val.od4) );
										total1.push( parseFloat(val.od5) );
										total1.push( parseFloat(val.od6) );
										total1.push( parseFloat(val.od7) );
										total1.push( parseFloat(val.od8) );
										total1.push( parseFloat(val.od9) );
										total1.push( parseFloat(val.od10) );
										total1.push( parseFloat(val.od11) );
										total1.push( parseFloat(val.od12) );
										
										V711 = parseFloat(val.nd1)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd2)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd3)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd4)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd5)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd6)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd7)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd8)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd9)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd10)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd11)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										V711 = parseFloat(val.nd12)*100/107;
										V45 = V711*0.45;
										total2.push( parseFloat(V45) );
										
										namex[n] = val.name;
										n++;
								    });
									namex[0] = "มกราคม";
									namex[1] = "กุมภาพันธ์";
									namex[2] = "มีนาคม";
									namex[3] = "เมษายน";
									namex[4] = "พฤษภาคม";
									namex[5] = "มิถุนายน";
									namex[6] = "กรกฎาคม";
									namex[7] = "สิงหาคม";
									namex[8] = "กันยายน";
									namex[9] = "ตุลาคม";
									namex[10] = "พฤศจิกายน";
									namex[11] = "ธันวาคม";
									
									datax[0] = total1;
									datax[1] = total2;
									getGraph( namex,datax );
									var url="ShowTable10.php";
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
													type: 'column'
												},
												title: {
													text: 'เปรียบเทียบยอดขาย เซเว่น เดือน ' + mthai
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
													categories: xName
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
												}],
												plotOptions: {
													series: {
														borderWidth: 0,
														dataLabels: {
															enabled: true,
															format: '{point.y:#,###.0f}'
														}
													}
												}
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
