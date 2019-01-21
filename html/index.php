<?php
include_once('lib.php');



$bbdd = new Bbdd();


if(@$_GET['md'] == 'daily'){
	$s_sql = 'SELECT fecha,SUM(temperatura)/COUNT(fecha) as temperatura FROM em_data GROUP BY DATE(fecha) ORDER BY fecha DESC LIMIT 7;';
}elseif(@$_GET['md'] == 'yesterday'){
	$s_sql = 'SELECT fecha,SUM(temperatura)/COUNT(fecha) as temperatura FROM em_data WHERE fecha like "'.date('Y-m-d',strtotime('-1 day')).'%" AND (fecha like "%5:00" OR fecha like "%0:00") GROUP BY HOUR(fecha) ORDER BY fecha DESC;';
}elseif(@$_GET['md'] == 'today'){
	$s_sql = 'SELECT fecha,SUM(temperatura)/COUNT(fecha) as temperatura FROM em_data WHERE fecha like "'.date('Y-m-d').'%" GROUP BY HOUR(fecha) ORDER BY fecha DESC LIMIT 100;';
}else{
	$s_select = 'SELECT * FROM em_data ';
	/***************************/
	$s_where = 'WHERE fecha like "%5:00" OR fecha like "%0:00" ';
	//$s_where = ' WHERE fecha like "%0:00" ';
	//$s_where = '';
	/***************************/
	$s_orderlimit = ' ORDER BY fecha DESC LIMIT 100;';
	$s_sql = $s_select.$s_where.$s_orderlimit;
	$cale = '1';
	$cale_name = 'Activar';
	if(@$_GET['cale'] != ''){
		$cale = $_GET['cale'];
		if($bbdd->insert(array('status' => $cale),'em_status')){
			$cale_name = 'Desactivar';
		}
	}
}



if(!$bbdd->consulta('SELECT * FROM em_data ORDER BY fecha DESC LIMIT 1;')){
	die('<h1>(x_x)</h1>');
}else{
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
		if(@$_GET['md'] == 'daily'){
			$fecha = format_fecha($dato['fecha']);
			$bbdd->consulta('SELECT temperatura FROM em_data WHERE fecha like "'.$fecha.'%" ORDER BY temperatura DESC LIMIT 1;');
			if($s_max == ''){
				$s_max = $bbdd->resultado[0]['temperatura'];
			}else{
				$s_max .= ', '.$bbdd->resultado[0]['temperatura'];
			}
			$bbdd->consulta('SELECT temperatura FROM em_data WHERE fecha like "'.$fecha.'%" ORDER BY temperatura ASC LIMIT 1;');
			if($s_min == ''){
				$s_min = $bbdd->resultado[0]['temperatura'];
			}else{
				$s_min .= ', '.$bbdd->resultado[0]['temperatura'];
			}
			$fecha = format_fecha($dato['fecha']);
		}else{
			$fecha = format_hora($dato['fecha']);
		}
		if($s_label == ''){
			$s_label = '"'.$fecha.'"';
		}else{
			$s_label .= ',"'.$fecha.'"';
		}
		
		if($s_data == ''){
			$s_data = $dato['temperatura'];
		}else{
			$s_data .= ', '.$dato['temperatura'];
		}
	}

	if($bbdd->consulta('SELECT * FROM em_status ORDER BY fecha DESC LIMIT 1;')){
		$cale = '1';
		$cale_name = 'Activar';
		if($bbdd->resultado[0]['status'] == '1'){
			$cale = '0';
			$cale_name = 'Desactivar';
		}
	}
}
?>
<html>
	<head>
		<!--<script src="jquery-1.7.1.min.js" type="text/javascript"></script>-->
		<script src="Chart.js" type="text/javascript"></script>
		<!--<meta http-equiv="refresh" content="150">-->
		<title>MdeWeather</title>
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
			<p style="text-align:center;text-decoration:none;font-size:3vh;">
				<a style="text-decoration:none;" href='/?cale=<?=$cale?>'><b><?=$cale_name?></b></b></a>
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
