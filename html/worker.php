<?php
/*****************************
 * MdeMoUcH
 * mdemouch@gmail.com
 * http://www.lagranm.com/
 *****************************/

include_once('lib.php');



$bbdd = new Bbdd();


$s_sql = 'SELECT DATE(fecha) as fecha FROM em_data WHERE DATE(fecha) NOT IN (SELECT fecha FROM em_daily_temp) GROUP BY DATE(fecha) ORDER BY fecha ASC;';

$bbdd->consulta($s_sql);

$a_fechas = $bbdd->resultado;

foreach($a_fechas as $fecha){
	if($fecha['fecha'] != date('Y-m-d')){
		$s_sql = 'SELECT SUM(temperatura)/COUNT(id) FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" GROUP BY DATE(fecha);';
		$bbdd->consulta($s_sql);
		$media = $bbdd->resultado[0][0];
		$s_sql = 'SELECT temperatura FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" ORDER BY temperatura DESC LIMIT 1;';
		$bbdd->consulta($s_sql);
		$max = $bbdd->resultado[0][0];
		$s_sql = 'SELECT temperatura FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" ORDER BY temperatura ASC LIMIT 1;';
		$bbdd->consulta($s_sql);
		$min = $bbdd->resultado[0][0];
		
		$a_insert = array('fecha'=>$fecha['fecha'],
							'min'=>$min,
							'max'=>$max,
							'media'=>$media,
							'lugar'=>'test');
		
		if(!$bbdd->insert($a_insert,'em_daily_temp')){
			die('oh shit...');
		}
		
		print_r('<p>Fecha: '.$fecha['fecha'].' Media: '.$media.' Max: '.$max.' Min: '.$min.'</p>'.PHP_EOL);
	}
}


$s_sql = 'SELECT fecha FROM em_monthly_temp GROUP BY fecha;';

$bbdd->consulta($s_sql);

$a_meses = array();
foreach($bbdd->resultado as $dato){
	$a_meses[$dato['fecha']] = $dato['fecha'];
}

$s_sql = 'SELECT DATE_FORMAT(fecha, "%Y-%m") as fecha FROM em_data GROUP BY YEAR(fecha), MONTH(fecha) ORDER BY fecha ASC;';

$bbdd->consulta($s_sql);

$a_fechas = $bbdd->resultado;

foreach($a_fechas as $fecha){
	if($fecha['fecha'] != date('Y-m') && !isset($a_meses[$fecha['fecha']])){
		$s_sql = 'SELECT SUM(temperatura)/COUNT(id) FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" GROUP BY MONTH(fecha);';
		$bbdd->consulta($s_sql);
		$media = $bbdd->resultado[0][0];
		$s_sql = 'SELECT temperatura FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" ORDER BY temperatura DESC LIMIT 1;';
		$bbdd->consulta($s_sql);
		$max = $bbdd->resultado[0][0];
		$s_sql = 'SELECT temperatura FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" ORDER BY temperatura ASC LIMIT 1;';
		$bbdd->consulta($s_sql);
		$min = $bbdd->resultado[0][0];
		
		$a_insert = array('fecha'=>$fecha['fecha'],
							'min'=>$min,
							'max'=>$max,
							'media'=>$media,
							'lugar'=>'test');
		
		if(!$bbdd->insert($a_insert,'em_monthly_temp')){
			die('oh shit...');
		}
		
		print_r('<p>Fecha: '.$fecha['fecha'].' Media: '.$media.' Max: '.$max.' Min: '.$min.'</p>'.PHP_EOL);
	}
}

die('end'.PHP_EOL);
