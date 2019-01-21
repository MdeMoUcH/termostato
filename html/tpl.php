<html>
	<head>
		<script src="Chart.js" type="text/javascript"></script>
		<link rel="stylesheet" href="bootstrap.min.css">
		<title>Ter-MoUcH-tato</title>

		<style>
			body {background-color: #333;color: #111;}
			.col-sm-3, .col-sm-6 {margin-top:10px;margin-bottom:-12px;}
			.align_center {text-align:center;}
			.align_right {text-align:right;}
			.btn {padding-top:12px;padding-bottom:12px;}
			.btn-danger {width:60%;}
			.btn-success {width:60%;}
			.btn-secondary {min-width:15%;margin-bottom:10px;}
			.container-fluid {width:95%;}
			.row {background-color:#6C757D;margin:10px;border-radius:12px;width:100%;}
			.botonera {margin-top:10px;width:100%;}
			.chart-container {position:relative;height:70vh;width:100%;margin-top:30px;}
		</style>

		<? if($b_refresh){echo '<meta http-equiv="refresh" content="60">';}?>
		<link rel="icon" type="image/png" href="termostato-<?=$cale_color?>.png" />
	</head>
	
<body style="font-family: Verdana;">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3">
				<img src="termostato-<?=$cale_color?>.png" height="64px" />
				<!--<div style="position:relative 5px 5px;width:64px;height:64px;background-color:<?=$temp_color?>;border-style:solid;clear:both;float:left;border-color:#000;">
					<div style="padding:0px;margin:0px;height:<?=$temp_height?>;background-color:#E5E5E5;">&nbsp;</div>
				</div>-->
			</div>
			<div class="col-sm-6 align_center titulo">
				<h3>Ter-MoUcH-tato</h3>
				<p><?=date('Y-m-d H:i:s')?></p>
			</div>
			<div class="col-sm-3 align_right">
				<h1><?=$temp?>&ordm;C &nbsp;</h1>
			</div>
		</div>
		
		
		<div class="chart-container">
			<canvas id="myChart"></canvas>
			
			<div class="botonera">
				<p class="align_center">
					<a class="btn btn-secondary" href='/'><b>Última hora</b></b></a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn btn-secondary" href='/?md=hourly'><b>Últimas horas</b></b></a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn btn-secondary" href='/?md=today'><b>Hoy</b></a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn btn-secondary" href='/?md=yesterday'><b>Ayer</b></a>
					&nbsp;&nbsp;&nbsp;
					<a class="btn btn-secondary" href='/daily.php'><b>Resumen diario</b></a>
				</p>
				<p class="align_center">
					<a class="btn btn-<?=$cale_class?>" href='/?cale=<?=$cale?>'><b><?=$cale_name?></b></b></a>
				</p>
			</div>
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
