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
	$Calc = new Calculator();
	// Раскомментировать когда в БД появятся записи
	
	/* $query = 'SELECT * FROM `table` WHERE 1';
	$DBPrice =  $DB->command($query,[])->Resulting; */
	
	// Сбор параметров от пользователя
	$latFrom = $_REQUEST['from_lat']; // Откуда высота
	$lonFrom = $_REQUEST['from_long']; // Откуда долгота
	$latTo   = $_REQUEST['to_lat']; // Куда высота
	$lonTo   = $_REQUEST['to_long']; // Куда долгота
	$Car     = $_REQUEST['car']; // Какая машина
	$Mass    = $_REQUEST['mass']; // Вес
	$Vol     = $_REQUEST['vol']; // объем
	$Dops = json_decode($_REQUEST['dops'],true); // JSON массив всяких допов
	// Сбор параметров от пользователя
	$dops = [];
	foreach ($Dops as $el){ // Получение информации по допам в соответствии с запросом
		// Условие наполнения массива допов
	}
	
	
	$Distanse  = Calculator::Distanse($latFrom,$lonFrom,$latTo,$lonTo); // Количество км пробега
	
	$price  = 	Calculator::GetPriceByCar($DBPrice,$Car); // Стоимость 1 км пробега в зависимости от АМ
	
	
	$response = new Response(['result'=>$Calc->calculate($Distanse,$price)],200);
	
	//
	class Calculator
	{
		public static function GetPriceByCar($DBprice,$Car)
		{
			// Условия поиска нужных значений из БД
			return [];
		}
		
		
		public static function Distanse($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
			{
				  // convert from degrees to radians
				  $earthRadius = 6371000;
				  $latFrom = deg2rad($latitudeFrom);
				  $lonFrom = deg2rad($longitudeFrom);
				  $latTo = deg2rad($latitudeTo);
				  $lonTo = deg2rad($longitudeTo);
				
				  $lonDelta = $lonTo - $lonFrom;
				  $a = pow(cos($latTo) * sin($lonDelta), 2) +
			      pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
				  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
				
				  $angle = atan2(sqrt($a), $b);
				  return (($angle * $earthRadius)/1000)*1.8;
			}
		
		public function calculate( int $distance, int $price, array $dops = null):int
		{
			$nominal = $distance*$price; // Чистая стоимость пробега автомобиля
			$nominalClear = $distance*$price; // Эталонное значение
			foreach ($dops as $key) // Бежим по массиву пришедших допов
			{
				if($key['mode']=='static'){ // Доп статичный
					$nominal = $nominal+ $key['summ']; // Прибавляем к цене доп
				}
				elseif($key['mode']=='dinamic'){ // Доп процентный
					$nominal = ($nominalClear*$key['summ'])/100 + $nominal; // Прибавляем процент наценки к эталонной стоимости всего маршрута
				}
			}
			return round( $nominal,0);
			
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
	
