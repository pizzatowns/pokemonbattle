<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Create_database
{
    public $connect;
    public function __construct()
    {
        require_once('database/Database_connection.php');

        $database_object = new Database_connection;

        $this->connect = $database_object->connect();

        $this->createPokemonDataBase();
    }

    function createPokemonDataBase()
    {
        $databaseName = "pokemon";
        $database_exist = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$databaseName'";
        $createDataBase = "CREATE DATABASE $databaseName";
        $result = $this->connect->query($database_exist);
        if (!$result->num_rows > 0) {
            $this->connect->query($createDataBase);
            // Create User Table
            $this->createUserTable();
            $this->createPokedexTable();
            $this->createUserDexTable();
        }
    }
    function createUserTable()
    {
        $databaseName = "pokemon";
        $tableName = "user_table";
        $query = "CREATE TABLE $databaseName.$tableName (
            user_id VARCHAR(250) NOT NULL,
            user_name VARCHAR(250) NOT NULL,
            user_email VARCHAR(250) NOT NULL,
            user_password VARCHAR(100) NOT NULL,
            user_gold INT NOT NULL,
            user_status ENUM('Disabled','Enable') NOT NULL,
            user_created_on DATETIME NOT NULL,
            user_login_status ENUM('Logout','Login') NOT NULL
                    )";
        ($this->connect->query($query) === TRUE) ? "Table Created" : "";
    }
    
    // START POKEMON_DEX SECTION
    function createPokedexTable()
    {

        // CREATE POKEDEX_TABLE
        $databaseName = "pokemon";
        $tableName = "pokedex_table";
        $query = "CREATE TABLE $databaseName.$tableName (
            pokemon_id INT AUTO_INCREMENT PRIMARY KEY,
            pokemon_name VARCHAR(50) NOT NULL,
            pokemon_type1 VARCHAR(50),
            pokemon_type2 VARCHAR(50),
            pokemon_total INT NOT NULL,
            pokemon_hp INT NOT NULL,
            pokemon_atk INT NOT NULL,
            pokemon_def INT NOT NULL,
            pokemon_spatk INT NOT NULL,
            pokemon_spdef INT NOT NULL,
            pokemon_speed INT NOT NULL,
            pokemon_data LONGBLOB,
            legendary VARCHAR(50) NOT NULL
                    )";
        ($this->connect->query($query) === TRUE) ? "Table Created" : "";

        // SCAN GET ALL FILE NAME IN DIRECTORY, AMOUNT OF DATA BASE ON THE AMOUNT OF IMAGES WE HAVE
        $directory = 'images/pokemon_imgs';
        $files = scandir($directory);
        $name_arr = [];
        $file_data = [];
        // GET NAME OF ALL FILE SUB FIRST THREE NUMBER AND EXTENSION, AND STORE TO ARRAY.
        // STORE ALL FILE DATA INTO AN ARRAY.
        foreach ($files as $file) {
            $extension = ".png";
            $nameWithoutDigits = preg_replace('/^\d+/', '', $file);
            $name = str_replace($extension, '', $nameWithoutDigits);
            $name_arr[] = $name;
            $file_data[] = file_get_contents("images/pokemon_imgs/$file");
            $data = file_get_contents("images/$file");
        }
        // START READ XLXS, AND COMPARE IF NAME OF IMAGES MATCH NAME ON XLXS BEFORE TRANSFER DATA
        $databaseName = "pokemon";
        $tableName = "pokedex_table";
        $filePath = 'database/file/pokemon_data.xlsx';
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        $query = "INSERT INTO $databaseName.$tableName (pokemon_name, pokemon_type1, pokemon_type2, pokemon_total, pokemon_hp, 
        pokemon_atk, pokemon_def, pokemon_spatk, pokemon_spdef, pokemon_speed, pokemon_data, legendary) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($query);
        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];
            $pokemon_name = $row[1];
            $pokemon_type1 = $row[2];
            $pokemon_type2 = $row[3];
            $pokemon_total = $row[4];
            $pokemon_hp = $row[5];
            $pokemon_atk = $row[6];
            $pokemon_def = $row[7];
            $pokemon_spatk = $row[8];
            $pokemon_spdef = $row[9];
            $pokemon_speed = $row[10];
            $legendary = $row[12];

            if (in_array($pokemon_name, $name_arr)) {
                $index = array_search($pokemon_name, $name_arr);
                $getImgData = $file_data[$index];
                $stmt->bind_param("ssssssssssss", $pokemon_name, $pokemon_type1, $pokemon_type2, $pokemon_total, $pokemon_hp, $pokemon_atk, $pokemon_def, $pokemon_spatk, $pokemon_spdef, $pokemon_speed, $getImgData, $legendary);
                $stmt->execute();
            }
        }
        $stmt->close();
    }
    // END POKE_DEX SECTION

    // START USER_DEX SECTION
    function createUserDexTable(){
        $databaseName = "pokemon";
        $tableName = "user_dex_table";
        $query = "CREATE TABLE $databaseName.$tableName (
            user_id VARCHAR(50) NOT NULL,
            pokemon_name VARCHAR(50) NOT NULL,
            pokemon_type1 VARCHAR(50),
            pokemon_type2 VARCHAR(50),
            pokemon_total INT NOT NULL,
            pokemon_hp INT NOT NULL,
            pokemon_atk INT NOT NULL,
            pokemon_def INT NOT NULL,
            pokemon_spatk INT NOT NULL,
            pokemon_spdef INT NOT NULL,
            pokemon_speed INT NOT NULL,
            pokemon_level INT NOT NULL,
            evolution_to VARCHAR(50),
            pokemon_data LONGBLOB,
            legendary VARCHAR(50) NOT NULL
                    )";
        ($this->connect->query($query) === TRUE) ? "Table Created" : "";
    }
    // END USER_DEX SECTION


}
