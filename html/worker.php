<?php
include_once('lib.php');



$s_sql = 'SELECT DATE(fecha) as fecha FROM em_data WHERE DATE(fecha) NOT IN (SELECT fecha FROM em_daily_temp) GROUP BY DATE(fecha) ORDER BY fecha ASC;';



$bbdd = new Bbdd();
$bbdd->consulta($s_sql);

$a_fechas = $bbdd->resultado;



foreach($a_fechas as $fecha){
	if($fecha['fecha'] != date('Y-m-d')){
		$s_sql = 'SELECT SUM(temperatura)/COUNT(id) FROM em_data WHERE fecha like "%'.$fecha['fecha'].'%" GROUP BY DATE(fecha);';
		//die($s_sql);
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

die('end'.PHP_EOL);
