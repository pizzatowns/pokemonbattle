<?php

class User
{
    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_status;
    private $user_created_on;
    private $user_login_status;
    private $user_gold;
    public $connect;

    public function __construct()
    {
        require_once('database/Database_connection.php');
        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }
    function setGold($user_gold)
    {
        $this->user_gold = $user_gold;
    }
    function getGold($user_gold)
    {
        $this->user_gold;
    }
    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    function getUserId()
    {
        return $this->user_id;
    }
    function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }
    function getUserName()
    {
        $this->user_name;
    }
    function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }
    function getUserEmail()
    {
        return $this->user_email;
    }
    function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }
    function getUserPassword()
    {
        return $this->user_password;
    }
    function setUserStatus($user_status)
    {
        $this->user_status = $user_status;
    }
    function getUserStatus()
    {
        return $this->user_status;
    }
    function setUserCreatedOn($user_created_on)
    {
        $this->user_created_on = $user_created_on;
    }
    function getUserCreatedOn()
    {
        return $this->user_created_on;
    }
    function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }
    function getUserLoginStatus()
    {
        return $this->user_login_status;
    }

    function get_user_data_by_email()
    {
        $stmt = $this->connect->prepare("SELECT * FROM pokemon.user_table WHERE user_email = ?");
        $stmt->bind_param("s", $this->user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        if ($user_data) {
            return $user_data;
        }
        return false;
    }
    function get_user_data_by_id(){
        $stmt = $this->connect->prepare("SELECT * FROM pokemon.user_table WHERE user_id = ?");
        $stmt->bind_param("s", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        if ($user_data) {
            return $user_data;
        }
        return false;
    }
    function update_user_login_status(){
        $query = "
		UPDATE pokemon.user_table 
		SET user_login_status = ?
		WHERE user_id = ?
		";

		$stmt = $this->connect->prepare($query);

		$stmt->bind_param("ss", $this->user_login_status, $this->user_id);

		if($stmt->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    function update_user_gold(){
        $query = "
		UPDATE pokemon.user_table 
		SET user_gold = ?
		WHERE user_id = ?
		";

		$stmt = $this->connect->prepare($query);

		$stmt->bind_param("ss", $this->user_gold, $this->user_id);

		if($stmt->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    function save_data()
    {
        $gold = 2000;
        $stmt = $this->connect->prepare("INSERT INTO pokemon.user_table (user_id, user_name, user_email, user_password, user_gold,  user_status, user_login_status, user_created_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss",$this->user_id, $this->user_name, $this->user_email,$this->user_password,$gold, $this->user_status, $this->user_login_status,$this->user_created_on);
        $stmt->execute();
    }
}
