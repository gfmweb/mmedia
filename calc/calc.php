<?php
	/**
	 * Ожидаемые данные
	 * 1. Пункт А + Пункт Б (координаты в виде широты и долготы)
	 * 2. Тип автомобиля
	 * 3. Допы
	 */
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	
	$config  =  require 'config.php'; // Конфигурационный файл
	require 'db/CRUD.php'; // PDO БД класс
	$DB  = new CRUD($config);  // Подключились к БД
	// Раскомментировать когда в БД появятся записи
	
	/* $query = 'SELECT * FROM `table` WHERE 1';
	$DB->command($query,[])->Resulting; */
	$Calc = new Calculator();
	
	$response = new Response(['result'=>$Calc->calculate(123,10)],200);
	
	//
	class Calculator
	{
		public function calculate( int $distance, int $price, array $dops = null):int
		{
			return (is_null($dops)) ? $distance*$price : 0;
			
		}
	}
	
	class Response
	{
		public function __construct($data,$code)
		{
			if($code == 404){header("HTTP/1.0 404 Not Found");}
			if($code == 403){header("HTTP/1.0 403 Forbidden");}
			if($code == 403){header("HTTP/1.0 400 Bad Request");}
			if($code == 200) {
				header("HTTP/1.0 200 Ok");
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($data, 256);
			}
		}
	}
	
