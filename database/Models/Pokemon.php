<?php

class Pokemon implements JsonSerializable
{
    public $id;
    public $battle_team;
    public $name;
    public $type1;
    public $type2;
    public $total;
    public $hp;
    public $atk;
    public $def;
    public $spatk;
    public $spdef;
    public $speed;
    public $data;
    public $legendary;
    public $level;

    public function __construct()
    {
    }
    // SETTER
    function setId($id){
        $this->id = $id;
    }
    function setBattleTeam($team){
        $this->battle_team = $team;
    }
    function setName($name)
    {
        $this->name = $name;
    }
    function setType1($type1)
    {
        $this->type1 = $type1;
    }
    function setType2($type2)
    {
        $this->type2 = $type2;
    }
    function setTotal($total)
    {
        $this->total = $total;
    }
    function setHp($hp)
    {
        $this->hp = $hp;
    }
    function setAtk($atk)
    {
        $this->atk = $atk;
    }
    function setDef($def)
    {
        $this->def = $def;
    }
    function setSpatk($spatk)
    {
        $this->spatk = $spatk;
    }
    function setSpdef($spdef)
    {
        $this->spdef = $spdef;
    }
    function setSpeed($speed)
    {
        $this->speed = $speed;
    }
    function setData($data)
    {
        $this->data = $data;
    }
    function setLevel($level)
    {
        $this->level = $level;
    }
    function setLegendary($legendary)
    {
        $this->legendary = $legendary;
    }

    // GETTER
    function getBattleTeam($team){
        $this->battle_team;
    }
    function getId(){
        return $this->id;
    }
    function getName()
    {
        return $this->name;
    }
    function getType1()
    {
        return $this->type1;
    }
    function getType2()
    {
        return $this->type2;
    }
    function getTotal()
    {
        return $this->total;
    }
    function getHp()
    {
        return $this->hp;
    }
    function getAtk()
    {
        return $this->atk;
    }
    function getDef()
    {
        return $this->def;
    }
    function getSpatk()
    {
        return $this->spatk;
    }
    function getSpdef()
    {
        return $this->spdef;
    }
    function getSpeed()
    {
        return $this->speed;
    }
    function getData()
    {
       return $this->data;
    }
    function getLevel()
    {
        return $this->level;
    }
    function getLegendary()
    {
       return $this->legendary;
    }
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'battle_team' => $this->battle_team,
            'name' => $this->name,
            'type1' => $this->type1,
            'type2' => $this->type2,
            'total' => $this->total,
            'hp' => $this->hp,
            'atk' => $this->atk,
            'def' => $this->def,
            'spatk' => $this->spatk,
            'spdef' => $this->spdef,
            'speed' => $this->speed,
            'data' => $this->data,
            'legendary' => $this->legendary,
            'level' => $this->level,
        ];
    }
}
