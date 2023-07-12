<?php
?>
<div class="flex flex-col ">
    <div class="grid grid-cols-4 h-full w-full overflow-auto text-center" id="pokemon_center_display">
    </div>
    <div class="w-full h-2 bg-black rounded-full"></div>
    <div class="flex flex-row justify-around items-center">
        <div>+</div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $.ajax({
            url: 'pokemon_lab_action.php',
            type: 'GET',
            data: {
                data: "get_user_pokedex",
            },
            success: function(response) {
                var data = JSON.parse(response);
                data.map((value, index) => {
                    var pokemon = $(`<div class="fex flex-col h-[200px] w-[150px] text-center transition-opacity duration-[3000ms]" style="opacity:0;"><div class=" h-[150px] w-[150px] bg-contain bg-no-repeat bg-center cursor-pointer" style="background-image: url(${value.data});" id=''></div><span>${value.name}</span></div>`)
                    pokemon.css('opacity', '1');
                    
                    $('#pokemon_center_display').append(pokemon);
                })
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

    });
</script>