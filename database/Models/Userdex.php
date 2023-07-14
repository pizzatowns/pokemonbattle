<?php

class Userdex
{
    private $user_id;
    private $power = 0;
    private $newPokemons;
    private $battle_team;
    private $pokedex;
    public $connect;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        require_once('database/Database_connection.php');
        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }
    function setPower($power)
    {
        $this->power += $power;
    }
    function resetBattleTeam()
    {
        $this->battle_team = [];
    }
    function getBattleTeam()
    {
        return $this->battle_team;
    }
    function getPower()
    {
        return $this->power;
    }
    function setNewPokemons()
    {
        $this->newPokemons = array();
    }
    function getUserId()
    {
        return $this->user_id;
    }
    function getNewpokemons()
    {
        return $this->newPokemons;
    }

    function getPokemons_from_id()
    {
        $stmt = $this->connect->prepare("SELECT * FROM pokemon.user_dex_table WHERE user_id = ?");
        $stmt->bind_param("s", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        if ($user_data) {
            echo "work";
        }
        echo "no work";
        return false;
    }
    function generatePokemon()
    {
        require_once('database/Models/Pokemon.php');
        $query = "SELECT * FROM pokemon.pokedex_table ORDER BY RAND() LIMIT 5";

        $result = $this->connect->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pokemonObj = new Pokemon;
                $pokemonObj->setName($row['pokemon_name']);
                $pokemonObj->setType1($row['pokemon_type1']);
                $pokemonObj->setType2($row['pokemon_type2']);
                $pokemonObj->setHp($row['pokemon_hp']);
                $pokemonObj->setAtk($row['pokemon_atk']);
                $pokemonObj->setDef($row['pokemon_def']);
                $pokemonObj->setTotal($row['pokemon_total']);
                $pokemonObj->setSpatk($row['pokemon_spatk']);
                $pokemonObj->setSpdef($row['pokemon_spdef']);
                $pokemonObj->setSpeed($row['pokemon_speed']);
                $url = 'data:image/png;base64,' . base64_encode($row['pokemon_data']);
                $pokemonObj->setData($url);
                $pokemonObj->setLevel(0);
                $pokemonObj->setLegendary($row['legendary']);
                $this->newPokemons[] = $pokemonObj;
            }
        }
    }
    function add_pokemon_to_user_dex()
    {
        $query = "INSERT INTO pokemon.user_dex_table (user_id, pokemon_name, pokemon_type1, pokemon_type2, pokemon_total, pokemon_hp, 
        pokemon_atk, pokemon_def, pokemon_spatk, pokemon_spdef, pokemon_speed, pokemon_level, battle_team, pokemon_data, legendary) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($query);
        for ($i = 0; $i < count($this->newPokemons); $i++) {
            $pokemon_name = $this->newPokemons[$i]->getName();
            $pokemon_type1 = $this->newPokemons[$i]->getType1();
            $pokemon_type2 = $this->newPokemons[$i]->getType2();
            $pokemon_total = $this->newPokemons[$i]->getTotal();
            $pokemon_hp = $this->newPokemons[$i]->getHp();
            $pokemon_atk = $this->newPokemons[$i]->getAtk();
            $pokemon_def = $this->newPokemons[$i]->getDef();
            $pokemon_spatk = $this->newPokemons[$i]->getSpatk();
            $pokemon_spdef = $this->newPokemons[$i]->getSpdef();
            $pokemon_speed = $this->newPokemons[$i]->getSpeed();
            $battle_team = "FALSE";
            $getImgData = $this->newPokemons[$i]->getData();
            $legendary = $this->newPokemons[$i]->getLegendary();
            $pokemon_level = 0;
            $stmt->bind_param("sssssssssssssss", $this->user_id, $pokemon_name, $pokemon_type1, $pokemon_type2, $pokemon_total, $pokemon_hp, $pokemon_atk, $pokemon_def, $pokemon_spatk, $pokemon_spdef, $pokemon_speed, $pokemon_level, $battle_team, $getImgData, $legendary);
            // Execute the prepared statement
            $stmt->execute();
        }
    }
    function get_user_pokedex_by_id()
    {
        require_once('database/Models/Pokemon.php');
        $this->resetBattleTeam();
        $stmt = $this->connect->prepare("SELECT * FROM pokemon.user_dex_table WHERE user_id = ?");
        $stmt->bind_param("s", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->resetBattleTeam();
                $pokemonObj = new Pokemon;
                $pokemonObj->setBattleTeam($row['battle_team']);
                $pokemonObj->setId($row['pokemon_id']);
                $pokemonObj->setName($row['pokemon_name']);
                $pokemonObj->setType1($row['pokemon_type1']);
                $pokemonObj->setType2($row['pokemon_type2']);
                $pokemonObj->setHp($row['pokemon_hp']);
                $pokemonObj->setAtk($row['pokemon_atk']);
                $pokemonObj->setDef($row['pokemon_def']);
                $pokemonObj->setTotal($row['pokemon_total']);
                $pokemonObj->setSpatk($row['pokemon_spatk']);
                $pokemonObj->setSpdef($row['pokemon_spdef']);
                $pokemonObj->setSpeed($row['pokemon_speed']);
                $url = $row['pokemon_data'];
                $pokemonObj->setData($url);
                $pokemonObj->setLevel(0);
                $pokemonObj->setLegendary($row['legendary']);
                if ($row['battle_team'] == 'TRUE') {
                    $this->setPower($row['total']);
                    $this->battle_team[] = $pokemonObj;
                }
                $this->pokedex[] = $pokemonObj;
            }
        }
        return $this->pokedex;
    }
    function update_battle_team($new_battle_team)
    {
        $new_battle_team = array_map(function ($item) {
            return $item['id'];
        }, $new_battle_team);

        $queryOff = "UPDATE pokemon.user_dex_table SET battle_team = 'FALSE' WHERE user_id = ?";
        $stmtOff = $this->connect->prepare($queryOff);
        $stmtOff->bind_param("s", $this->user_id);
        $stmtOff->execute();

        $queryOn = "UPDATE pokemon.user_dex_table SET battle_team = 'TRUE' WHERE user_id = ? AND pokemon_id IN (" . implode(",", $new_battle_team) . ")";
        $stmtOn = $this->connect->prepare($queryOn);
        $stmtOn->bind_param("s", $this->user_id);
        $stmtOn->execute();
    }
}
