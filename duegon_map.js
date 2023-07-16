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
  let atk;
  let def;
  let damge;
  let percentageHp;
  let hp = player_def.hp;

  atk = spAtkNum == 2 ? player_atk.spatk : player_atk.atk;
  def = spDefNum == 2 ? player_def.spdef : player_def.def;

  damge = atk - def;
  hp = hp <= damge ? 0 : hp - damge;
  percentageHp = (damge * 100) / hp;
  defender.hp = hp;
};

// const start_battle = () => {
//   console.log(player_atk);
//   console.log(player_def);
//   // battle(player_atk, player_def);
// };


$(document).ready(function () {
  $('#normal_atk').click(()=>{
    console.log('====================================');
    console.log(player_atk);
    console.log('====================================');
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
            result_defender_stat = value.state_pokemons;
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
              $('[id^="player_poke_"]').removeClass("drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]");
              $("#player_poke_" + id).addClass('drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]');
              let poke = player_poke[index];
              if (battle_start) {
                battle_turn % 2 == 0 ? (player_def = poke) : (player_atk = poke);
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
              $('[id^="enemy_poke_"]').removeClass("drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]");
              $("#enemy_poke_" + id).addClass('drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]');
              let poke = enemy_poke[index];
              if (battle_start) {
                battle_turn % 2 != 0 ? (player_def = poke) : (player_atk = poke);
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
