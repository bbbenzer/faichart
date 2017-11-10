<?php 
require 'include/connect.php';
$result = mysql_query( "UPDATE chart_menu SET Chk = 2" );
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
						getSomething();
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
								url: "getGraph02.php",
								type: "POST",
								data: arr,
								dataType: 'json',
								async: false,
								success: function(data) {
								    var total = [];
								    $.each(data, function(key, val) {
										total.push({name:val.name,y:parseFloat(val.y),drilldown:val.drilldown});
								    });
								    getGraph( total );
									var url="ShowTable02.php";
									$.post(url,arr,function(data){
										$("#div1").html(data)
									});
								}
							}
						);
					}

					function getSomething(){
							$.getJSON('getGraph02.php', function(data) {
								  var total = [];
								  $.each(data, function(key, val) {
										total.push({name:val.name,y:parseFloat(val.y),drilldown:val.drilldown});
								  });

								  getGraph( total );
								  var url="ShowTable02.php";
									$.post(url,arr,function(data){
										$("#div1").html(data)
									});
							});
						
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

					function getGraph( Data ){
					// Create the chart

								var mm = $("#xMonth").val();
								var yy = $("#xYear").val();

								var mthai = getMonthThai( mm );
								//alert(  );
									  Highcharts.chart('container', {
												chart: {
													type: 'column'
												},
												title: {
													text: 'เปรียบเทียบยอดขายรวมทุกสาขา ประจำเดือน '+mthai
												},
												subtitle: {
													text: ''
												},
												xAxis: {
													type: 'category'
												},
												yAxis: {
													title: {
														text: ''
													}
												},
												
												plotOptions: {
													series: {
														borderWidth: 0,
														dataLabels: {
															enabled: true,
															format: '{point.y:#,###.0f}'
														}
													}
												},

												tooltip: {
													headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
													pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
												},

												series: [{
													name: 'Faibakery',
													colorByPoint: true,
													data: Data
												}],


											});
					// End Graph
					}
		</script>
	</head>
	<body>
		<script src="js/highcharts.js"></script>
		<script src="js/data.js"></script>
		<script src="js/drilldown.js"></script>
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
