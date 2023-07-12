<?php 
require 'database/Models/User.php';
require 'database/Models/Userdex.php';
require_once('database/Database_connection.php');

session_start();


if (!isset($_SESSION['user_data'])){
    header('location:index.php');
}
$user_data = $_SESSION['user_data'];
$user_id = $user_data['id'];

$user_dex = new Userdex($user_id);
$user = new User();

$user->setUserId($user_id);



if (isset($_GET['data'])){
    switch ($_GET['data']) {
        case "generate_pokemon":
            $user->setGold($_GET['user_gold']);
            $user->update_user_gold();
            $user_dex->setNewPokemons();
            $user_dex->generatePokemon();
            $user_dex->add_pokemon_to_user_dex();
            $pokemons = $user_dex->getNewpokemons();
            $jsPokemons = json_encode($pokemons); 
            echo $jsPokemons;
            break;
        case "user_info":
            $user_info = json_encode($user->get_user_data_by_id());
            echo $user_info;
            break;
        case "get_user_pokedex":
            $user_pokemon_dex = $user_dex->get_user_pokedex_by_id();
            $jsuser_pokemon_dex = json_encode($user_pokemon_dex); 
            echo $jsuser_pokemon_dex;
            break;
        default:
        echo json_encode(['status'=>-1]);
        break;
    }
}
