<?php
include_once('lib.php');

$s_sql = 'SELECT * FROM em_daily_temp ORDER BY fecha DESC LIMIT 60;';


$bbdd = new Bbdd();

if(!$bbdd->consulta('SELECT * FROM em_daily_temp ORDER BY fecha DESC LIMIT 1;')){
	die('<h1>(x_x)</h1>');
}else{
	$bbdd->consulta('SELECT * FROM em_data ORDER BY fecha DESC LIMIT 1;');
	$temp = $bbdd->resultado[0]['temperatura'];
	$temp_color = temp_color($temp);
	$temp_height = temp_height($temp);
	$bbdd->consulta($s_sql);
	$s_label = '';
	$s_data = '';
	$s_max = '';
	$s_min = '';
	asort($bbdd->resultado);
	foreach($bbdd->resultado as $dato){
		$fecha = format_fecha($dato['fecha']);
		if($s_max == ''){
			$s_max = $dato['max'];
		}else{
			$s_max .= ', '.$dato['max'];
		}
		if($s_min == ''){
			$s_min = $dato['min'];
		}else{
			$s_min .= ', '.$dato['min'];
		}
	
		if($s_label == ''){
			$s_label = '"'.$fecha.'"';
		}else{
			$s_label .= ',"'.$fecha.'"';
		}
		
		if($s_data == ''){
			$s_data = $dato['media'];
		}else{
			$s_data .= ', '.$dato['media'];
		}
	}
}
?>
<html>
	<head>
		<!--<script src="jquery-1.7.1.min.js" type="text/javascript"></script>-->
		<script src="Chart.js" type="text/javascript"></script>
	</head>
<body style="font-family: Verdana;">
	<div style="position:relative 5px 5px;width:34px;height:34px;margin-right:10px;background-color:<?=$temp_color?>;border-style:solid;clear:both;float:left;">
		<div style="padding:0px;margin:0px;height:<?=$temp_height?>;background-color:#E5E5E5;">&nbsp;</div>
	</div>
	
	<div>
		<h3 style="text-align:left;margin-bottom:-49px;">MdeWeather</h3>
		<h1 style="text-align:right;margin-bottom:-38px;"><?=$temp?>&ordm;C &nbsp;</h1>
		<h4 style="text-align:left;align:right;margin-bottom:-24px;margin-left:25px;"><?=date('Y-m-d H:i:s')?></h4>
	</div>
	
	<div class="chart-container" style="position: relative; height:70vh; width:95vw;margin-top:30px;">
		<canvas id="myChart"></canvas>
		
		<div style="margin-top:10px;">
			<p style="text-align:center;text-decoration:none;font-size:3vh;">
				<a style="text-decoration:none;" href='/'><b>Últimas horas</b></b></a>
				&nbsp;&nbsp;&nbsp;
				<a  style="text-decoration:none;" href='/daily.php'><b>Resumen diario</b></a>
				&nbsp;&nbsp;&nbsp;
				<a style="text-decoration:none;" href='/?md=today'><b>Hoy</b></a>
				&nbsp;&nbsp;&nbsp;
				<a style="text-decoration:none;" href='/?md=yesterday'><b>Ayer</b></a>
			</p>
		</div>
	</div>
	
	
	<script>
		var ctx = document.getElementById("myChart").getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: [<?=$s_label ?>],
				
				datasets: [{
					label: "Temp", data: [<?=$s_data ?>], borderColor: ['rgba(99,200,99,1)'], backgroundColor: 'rgb(0,0,0,0)', borderWidth: 2}
				<? if($s_max != ''){
					echo ',{label: "Max", data: ['.$s_max.'], borderColor: ["rgba(255,99,99,1)"], backgroundColor: "rgb(0,0,0,0)", borderWidth: 2}';
				}?>
				<? if($s_min != ''){
					echo ',{label: "Min", data: ['.$s_min.'], borderColor: ["rgba(99,99,255,1)"], backgroundColor: "rgb(0,0,0,0)", borderWidth: 2}';
				}?>
				]
			},
			options: {
				legend: {display: false},
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: false
						}
					}]
				}
			}
		});
		Chart.defaults.global.responsive = true;
	</script>
</body>
</html>
