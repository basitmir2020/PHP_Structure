<?php
	class dbContext{
		private $con;
		function __construct(){
			$this->con = self::connect();
		}
		private static function connect(){
			$db = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
			if(!$db->connect_errno)
				return $db;
			return false;
		}
		//Create
		public function insert($sql){
			//$sql = mysqli_real_escape_string($this->con,$sql);
			if($this->con->query($sql))
				return true;
			return false;
		}
		//Retrieve
		public function select($sql){
			//$sql = mysqli_real_escape_string($this->con,$sql);
			if($result = $this->con->query($sql)){
					return $result;
			}else{
				
				return NULL;
			}
					
		}
		//Update
		public function update($sql){
			//$sql = mysqli_real_escape_string($this->con,$sql);
			if($this->con->query($sql))
				return true;
			return false;
		}
		//Delete
		public function delete($sql){
			//$sql = mysqli_real_escape_string($this->con,$sql);
			if($this->con->query($sql))
				return true;
			return false;
		}
	}
?>