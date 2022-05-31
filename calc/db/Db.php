<?php
	
	class Db
	{
		
		private $_connection;
		private static $_instance;
		private static $_con;
		
		public static function init($config)
		{
			if (!self::$_instance) { // Ели у нас нет ни одного подключения к БД тогда создаем его
				self::$_instance = new self($config);  // Вызываем приватный метод конструктора
				self::$_con = self::$_instance->_connection; // Передаем в приватное свойство идеинтификатор подключения к БД
			}
			return self::$_con; // Возвращаем Идеинтификатор
		}
		
		
		private function __construct($config) // приватный метод конструктора
		{
			// require_once 'config/config.php'; // Запрашиваем config с данными для подключения к БД
			$dsn = "mysql:host=" . $config['DB']['host'] . ";dbname=" . $config['DB']['dbname'] . ";charset=" . $config['DB']['charset'];
			$opt = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // Принцип обработки ошибок
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,       // Ассоциативный массив при сборе результатов
				\PDO::ATTR_EMULATE_PREPARES => false,                  // Не имулировать подготовку
			];
			try {
				$this->_connection = new \PDO($dsn, $config['DB']['dbuser'], $config['DB']['password'], $opt); // Создаем подключение
			} catch (\Exception $e) {
				
				$this->_connection = $e->getMessage();
			}
		}
		
	}

