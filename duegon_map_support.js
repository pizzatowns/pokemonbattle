export function random_spc(){
  const randomNumber = Math.random();
  const number = Math.floor(randomNumber * 5) + 1;
  return number;
}
export function team_coordinate(team_roll) {
  switch (team_roll) {
    case "player":
      return [
        { bottom: 50, left: 250 },
        { bottom: 150, left: 100 },
        { bottom: 300, left: 50 },
        { bottom: 215, left: 235 },
        { bottom: 135, left: 405 },
      ];
    case "enemy":
      return [
        { top: 50, right: 250 },
        { top: 150, right: 100 },
        { top: 300, right: 50 },
        { top: 215, right: 235 },
        { top: 135, right: 405 },
      ];
  }
}
export function random_enemy_on_map(x, y, value, index) {
  return $(`
    <div id="map${index}" class="absolute left-[${x}px] top-[${y}px] cursor-pointer">
        <div class="flex flex-col h-full w-full items-center justify-center space-y-2">
            <div class="h-[100px] w-[100px] bg-cover bg-no-repeat bg-center"
                style="background-image: url(${
                  value.state_pokemons[0].data
                })"></div>
            <div class="font-bold text-black bg-white/60 rounded-full px-3">State 1 - ${
              index + 1
            }</div>
            <div class="flex flex-row items-center justify-center">
                <div class="h-[30px] w-[30px] bg-contain bg-no-repeat bg-center rounded-full cursor-pointer ml-3 bg-yellow-400"
                    style="background-image: url(images/homeImg/power.svg);">
                </div>: 
                <span class="text-yellow-500 text-lg font-bold tracking-wider bg-green-600/80 px-2 rounded-full">${
                  value.state_power
                }</span>
            </div>
        </div>
    </div>`);
}

export function player_html(index, bottom, left, img_url) {
  return `<div id="player_poke_${index}"  class="absolute h-[120px] w-[100px] bottom-[${bottom}px] left-[${left}px] cursor-pointer">
    <div class="flex flex-col h-full w-full justify-between items-center">  
        <div class="h-[100px] w-[100px] bg-contain bg-no-repeat bg-center -scale-x-100" style="background-image: url(${img_url})"></div>
        <div class="relative h-[18px] w-[100px] rounded-full">
              <div class="absolute top-0 left-0 h-full w-full bg-red-500 rounded-full"></div>
              <div class="absolute top-0 left-0 h-full w-[100px] z-10 bg-green-500 rounded-full" ></div>
              <div class="absolute -top-[3px] left-0 h-full w-full z-20 rounded-full text-small font-bold">HP:100</div>
        </div>
  </div>
</div>`;
}

export function enemy_html(index, top, right, img_url) {
  return `<div id="enemy_poke_${index}"  class="absolute h-[120px] w-[100px] top-[${top}px] right-[${right}px] cursor-pointer">
    <div class="flex flex-col h-full w-full justify-between items-center">     
        <div class="h-[100px] w-[100px] bg-contain bg-no-repeat bg-center"
            style="background-image: url(${img_url})"></div>
            <div class="relative h-[18px] w-[100px] rounded-full">
                <div class="absolute top-0 lef-0 h-full w-full bg-red-500 rounded-full"></div>
                <div class="absolute top-0 lef-0 h-full w-[100px] z-10 bg-green-500 rounded-full" ></div>
                <div class="absolute -top-[3px] left-0 h-full w-full z-20 rounded-full text-small font-bold">HP:100</div>
            </div>
    </div>
</div>`;
}

export function fetch_user_dex(callback) {
  $.ajax({
    url: "pokemon_lab_action.php",
    type: "GET",
    data: {
      data: "get_user_pokedex",
    },
    success: function (response) {
      var data = JSON.parse(response);
      data = data.filter((item) => item.battle_team == "TRUE");
      callback(data);
    },
    error: function (xhr, status, error) {
      console.error(error);
      callback(null, error);
    },
  });
}
export function fetch_duegon_map(callback) {
  $.ajax({
    url: "pokemon_lab_action.php",
    type: "GET",
    data: {
      data: "get_duegon_map",
    },
    success: function (response) {
      var data = JSON.parse(response);
      callback(data);
    },
    error: function (xhr, status, error) {
      console.error(error);
      callback(null, error);
    },
  });
}
