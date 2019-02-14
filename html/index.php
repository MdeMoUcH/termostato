<?php
/*****************************
 * MdeMoUcH
 * mdemouch@gmail.com
 * http://www.lagranm.com/
 *****************************/


include_once('lib.php');



$bbdd = new Bbdd();


$b_refresh = false;
if(@$_GET['md'] == 'hourly'){
	$s_sql = 'SELECT * FROM em_data WHERE fecha like "%5:00" OR fecha like "%0:00" ORDER BY fecha DESC LIMIT 100;';
}elseif(@$_GET['md'] == 'yesterday'){
	$s_sql = 'SELECT fecha,SUM(temperatura)/COUNT(fecha) as temperatura FROM em_data WHERE fecha like "'.date('Y-m-d',strtotime('-1 day')).'%" AND (fecha like "%5:00" OR fecha like "%0:00") GROUP BY HOUR(fecha) ORDER BY fecha DESC;';
}elseif(@$_GET['md'] == 'today'){
	$s_sql = 'SELECT fecha,SUM(temperatura)/COUNT(fecha) as temperatura FROM em_data WHERE fecha like "'.date('Y-m-d').'%" GROUP BY HOUR(fecha) ORDER BY fecha DESC LIMIT 100;';
}else{
	$s_sql = 'SELECT fecha,temperatura FROM em_data ORDER BY fecha DESC LIMIT 60;';
	$b_refresh = true;
}


$cale = '1';
if(@$_GET['cale'] != ''){
	$cale = $_GET['cale'];
	if(!$bbdd->insert(array('status' => $cale),'em_status')){
		die('<h1>(x_x)</h1>');
	}else{
		print_r('ok');
		header('Location: index.php');
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
	$a_data = $bbdd->resultado;
	asort($a_data);
	
	foreach($a_data as $dato){
		$fecha = format_hora($dato['fecha']);
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
		$cale_name = 'Activar calefacción';
		$cale_color = 'green';
		$cale_class = 'success';
		if($bbdd->resultado[0]['status'] == '1'){
			$cale = '0';
			$cale_name = 'Desactivar calefacción';
			$cale_color = 'red';
			$cale_class = 'danger';
		}
	}
}

include("tpl.php");

