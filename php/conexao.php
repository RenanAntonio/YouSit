<?php
class Database {   

    private $host = "localhost";
	private $db_name = "yousit";
	private $username = "root";
	private $password = "root";
    public $conn;
     
    public function dbConnection()
	{
	    $this->conn = null;    
        try
		{

    	 $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	    
	    $this->conn->exec("SET NAMES 'utf8';");
            
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}


class USER {	

	private $conn;
	
	public function __construct() {
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql) {
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
}
?>