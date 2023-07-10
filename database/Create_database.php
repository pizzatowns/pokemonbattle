<?php 

class Create_database {
    public $connect;
    public function __construct()
	{
		require_once('database/Database_connection.php');

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();

        $this->createPokemonDataBase();
	}

    function createPokemonDataBase(){
        $databaseName = "pokemon";
        $database_exist = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$databaseName'";
        $createDataBase = "CREATE DATABASE $databaseName";
        $result = $this->connect->query($database_exist);
        if (!$result->num_rows > 0){
            $this->connect->query($createDataBase);
            // Create User Table
            $this->createUserTable();
        }
    }
    function createUserTable(){
        $databaseName = "pokemon";
        $tableName = "user_table";
        $query = "CREATE TABLE $databaseName.$tableName (
            user_id VARCHAR(250) NOT NULL,
            user_name VARCHAR(250) NOT NULL,
            user_email VARCHAR(250) NOT NULL,
            user_password VARCHAR(100) NOT NULL,
            user_status ENUM('Disabled','Enable') NOT NULL,
            user_created_on DATETIME NOT NULL,
            user_login_status ENUM('Logout','Login') NOT NULL
                    )";
        ($this->connect->query($query) === TRUE) ? "Table Created" : "";
    }
}
