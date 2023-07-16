<?php
class Duegon
{
    public $connect;
    private $state_map;
    public function __construct()
    {
        require_once('database/Database_connection.php');
        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }
    function generateDuegon()
    {
        $this->generateStateMap();
        try {
            $state = 1;
            $jsStateMap = json_encode($this->state_map);
            $query = "INSERT INTO pokemon.duegon_map_table (state_num, state_map) VALUES (?, ?)";
            $stmt = $this->connect->prepare($query);
            $stmt->bind_param("ss", $state, $jsStateMap);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    function generateStateMap()
    {
        require_once('database/Models/Pokemon.php');
        require_once('database/Models/State.php');
        $this->state_map = array();
        for ($i = 1; $i <= 10; $i++) {
            $query = "SELECT * FROM pokemon.pokedex_table ORDER BY RAND() LIMIT 5";
            $result = $this->connect->query($query);
            $state_pokemon = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rate = 0.1;
                    $pokemonObj = new Pokemon;
                    $pokemonObj->setId($row['pokemon_id']);
                    $pokemonObj->setBattleTeam("FALSE");
                    $pokemonObj->setName($row['pokemon_name']);
                    $pokemonObj->setType1($row['pokemon_type1']);
                    $pokemonObj->setType2($row['pokemon_type2']);
                    $pokemonObj->setHp($row['pokemon_hp'] + round($row['pokemon_hp'] * $rate));
                    $pokemonObj->setAtk($row['pokemon_atk'] + round($row['pokemon_atk'] * $rate));
                    $pokemonObj->setDef($row['pokemon_def'] + round($row['pokemon_def'] * $rate));
                    $pokemonObj->setTotal($row['pokemon_total'] + round($row['pokemon_total'] * $rate));
                    $pokemonObj->setSpatk($row['pokemon_spatk'] + round($row['pokemon_spatk'] * $rate));
                    $pokemonObj->setSpdef($row['pokemon_spdef'] + round($row['pokemon_spdef'] * $rate));
                    $pokemonObj->setSpeed($row['pokemon_speed']);
                    $pokemonObj->setLevel($i);
                    $pokemonObj->setLegendary($row['legendary']);
                    $state_pokemon[] = $pokemonObj;
                }
            }
            $stateObj = new State($i, $state_pokemon, "NOT");
            $this->state_map[] = $stateObj;
        }
    }
    function getDuegonStateMap()
    {
        $mapId = 1; // ID of the map you want to retrieve

        $query = "SELECT * FROM pokemon.duegon_map_table WHERE id = ?";
        $stmt = $this->connect->prepare($query);
        $stmt->bind_param("i", $mapId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $map = $result->fetch_assoc();
            return $map;
        } else {
            echo "Map not found.";
            return false;
        }
    }
    function getDuegonMap()
    {
        $data = $this->getDuegonStateMap();
        $state_map = json_decode($data['state_map'], true);
        $update_state = [];
        foreach ($state_map as &$map) {
            $statePokemons = &$map['state_pokemons'];
            foreach ($statePokemons as &$pokemon) {
                $stmt = $this->connect->prepare("SELECT pokemon_data FROM pokemon.pokedex_table WHERE pokemon_id = ?");
                $stmt->bind_param("s", $pokemon['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $pokemon_data = $result->fetch_assoc();
                $url = 'data:image/png;base64,' . base64_encode($pokemon_data['pokemon_data']);
                // $pokemon->setData($url);
                $pokemon['data'] = $url;
                
            }
         $update_state[] = $map;
        } 
        return $update_state;
    }
}
