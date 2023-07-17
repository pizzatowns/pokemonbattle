import * as dms from "./duegon_map_support.js";

let result_attacker_stat;
let result_defender_stat;

let battle_start = false;
let battle_turn = 1;

let player_atk;
let player_def;

const battle = (player_atk, player_def) => {
  let attacker = player_atk;
  let defender = player_def;
  let spAtkNum = dms.random_spc();
  let spDefNum = dms.random_spc();
  let atk = attacker.atk;
  let def = defender.def;
  let damge;
  let percentageHp;
  let hp = defender.hp;

  atk = spAtkNum == 2 ? (Math.ceil(atk + attacker.spatk * 0.2)) : atk;
  def = spDefNum == 2 ? (Math.ceil(def + defender.spdef * 0.2)) : def;
  console.log(atk);
  console.log(def);
  damge = atk - def
  if (damge > 0){
    hp -= damge;
    if (hp <= 0) {
      hp = 0;
    }
  }else{
    damge = 0;
  }
  defender.hp = hp;
  return {id: defender.id, hp: defender.hp}
};




$(document).ready(function () {
  $('#normal_atk').click(()=>{
    let result = battle(player_atk, player_def);
    if (battle_turn % 2 != 0){
      let player_origin_hp = result_defender_stat.filter((item) => item.id == result.id)[0].hp;
      let hp_after_damage = result.hp;
      let percentageHpLoss = (hp_after_damage/player_origin_hp) * 100;
      let hpLeft = 100 - (100 - percentageHpLoss);
      
      $("#enemy_poke_" +result.id+"_hp_bar").css("width", hpLeft+"px");
      $("#enemy_poke_" +result.id+"_hp_number").text("HP: " + hp_after_damage);
    }
  })

  function processPlayerData(user_data) {
    function processDuegonMap(duegon_data) {
      var screenWidth = $(window).width() - 100;
      var screenHeight = $(window).height() - 300;
      var eachWidth = Math.floor(screenWidth / 10);

      let y = 50;
      let x = 50;
      //   PLAYER SECTION
      let players_coordination = dms.team_coordinate("player");

      // ENEMY SECTION
      let enemy_coordination = dms.team_coordinate("enemy");
      // SET POKE FOR PLAYER AND ENEMY
      let player_poke = user_data;
      result_attacker_stat = JSON.parse(JSON.stringify(user_data));
      let enemy_poke;

      duegon_data.map((value, index) => {
        var map_enemy = dms.random_enemy_on_map(x, y, value, index);
        y = Math.floor(Math.random() * (screenHeight + 1)) + 50;
        x += eachWidth;
        $("#duegon_map").append(map_enemy);
        // --------------------- CLICK EACH STATE IN MAP ---------------------
        $("#map" + index).click(() => {
          enemy_poke = value.state_pokemons;
          if (battle_start == false) {
            result_defender_stat =JSON.parse(JSON.stringify(value.state_pokemons));
          }
          $("#player").empty();
          $("#enemy").empty();
          $("#battle_field").removeClass("hidden");

          players_coordination.map((coordination, index) => {
            let id = player_poke[index].id;
            let hp = player_poke[index].hp;
            let img_url = player_poke[index].data;
            let player_html = dms.player_html(
              id,
              coordination.bottom,
              coordination.left,
              img_url,
              hp
            );
            $("#player").append(player_html);
            // PLAYER ACTION START HERE //
            $("#player_poke_" + id).click(() => {
              let poke = player_poke[index];
              if (battle_start) {
                battle_turn % 2 == 0 ? (player_def = poke) : (player_atk = poke);
                $('[id^="player_poke_"]').removeClass("drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]");
                $("#player_poke_" + id).addClass('drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]');
              }
            });
          });

          enemy_coordination.map((coordination, index) => {
            let id = enemy_poke[index].id;
            let hp = enemy_poke[index].hp;
            let img_url = enemy_poke[index].data;
            let enemy_html = dms.enemy_html(
              id,
              coordination.top,
              coordination.right,
              img_url,
              hp
            );
            $("#enemy").append(enemy_html);

            // ENEMY ACTION HERE START HERE //
            $("#enemy_poke_" + id).click(() => {
              let poke = enemy_poke[index];
              if (battle_start) {
                battle_turn % 2 != 0 ? (player_def = poke) : (player_atk = poke);
                $('[id^="enemy_poke_"]').removeClass("drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]");
                $("#enemy_poke_" + id).addClass('drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]');
              }
            });
          });
        });
      });
      $("#start_battle").click(() => {
        battle_start = true;
        result_attacker_stat = user_data;
        $("#start_battle").addClass("hidden");
      });
      $("#close_battle").click(() => {
        $("#battle_field").addClass("hidden");
        $("#start_battle").removeClass("hidden");
        battle_start = false;
        battle_turn = 1;
      });
    }
    dms.fetch_duegon_map(processDuegonMap);
  }
  dms.fetch_user_dex(processPlayerData);
});
