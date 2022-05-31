<?php
	/**
	 * Класс для работы с БД
	 * ВСЕ Методы CRUD
	 * При создании возвращает общее число строк в таблице
	 * Работает через подготовленные запросы
	 */
	
	
	
	
	final class CRUD
	{
		
		public $db;
		private $table;
		public  $TotalRows;
		public  $CurentRows;
		public  $Resulting;
		
		public function __construct($config,$table=null,$add=null) // Принимает имя таблицы с которой будет работать модель
		{
			require 'Db.php';
			$this->db = Db::init($config); // Инициализиркет подключение к БД
			if($this->db == 'Error'){
				return false;
			}
			if(!is_null($table)) {
				try {
					$this->table = $table; // Записываем в свойство имя таблицы
					$statement = $this->db->query("SELECT id FROM {$this->table} "); // Готовим запрос И узнаем сколько всего строк в таблице
					$this->TotalRows = $statement->rowCount(); // Считаем строки
				} catch (\Exception $e) {
					return 'Таблица не существует';
				}
			}
		}
		// Добавить множество строк за одну итерацию
		public function AddMany($str, $needle)
		{
			$statement=$this->db->prepare($str);
			foreach ($needle as $el){
				$statement->execute($el);
			}
			return $this;
		}
		// Работа с BD
		public function command($query, $NeedleArray){
			$statement=$this->db->prepare($query);
			$statement->execute($NeedleArray);
			$this->Resulting=$statement->fetchAll();
			$this->CurentRows=$statement->rowCount();
			return $this;
		}
		public function lastID($table){
			$statement = $this->db->query("SELECT id FROM {$table} WHERE 1 ORDER BY id DESC LIMIT 1");
			$res = $statement->fetchAll();
			return (isset($res[0]['id']))? $res[0]['id'] : false;
		}
		
		
		
		
	}
