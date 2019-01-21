<?php
include_once('lib.php');

$s_sql = 'SELECT * FROM em_daily_temp ORDER BY fecha DESC LIMIT 60;';


$bbdd = new Bbdd();


$b_refresh = false;
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

	if($bbdd->consulta('SELECT * FROM em_status ORDER BY fecha DESC LIMIT 1;')){
		$cale = '1';
		$cale_name = 'Activar';
			$cale_color = 'green';
		$cale_class = 'success';
		if($bbdd->resultado[0]['status'] == '1'){
			$cale = '0';
			$cale_name = 'Desactivar';
			$cale_color = 'red';
			$cale_class = 'danger';
		}
	}
}

include("tpl.php");
