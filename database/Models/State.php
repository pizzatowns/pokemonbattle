<?php

class State implements JsonSerializable
{
    private $id;
    private $state_pokemons;
    private $state_power;
    private $clear;

    public function __construct($id, $state_pokemons, $clear)
    {
        $this->id = $id;
        $this->state_pokemons = $state_pokemons;
        foreach($state_pokemons as $pokemon){
            $this->state_power += $pokemon->getTotal();
        }
        $this->clear = $clear;
    }
    function getPokemon(){
        return $this->state_power;
    }
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'state_pokemons' => $this->state_pokemons,
            'state_power' => $this->state_power,
            'clear' => $this->clear
        ];
    }

}


