/*Vamos a implementar tres funciones principales:
openModal(id) que cuando hagamos click en una carta se veran los datos del video juego
closeModal() que cerrara la ventana modal
buyGame() 
*/


function closeModal() {
    document.getElementById("modal").classList.add("hidden");
    //conseguimos los elementos del modal para ocultar su contenido
}
let currentGameId = null;  // guardamos el id del juego abierto
function openModal(id) {
    currentGameId = id;  // guardamos el id del juego abierto
    document.getElementById("modal").classList.remove("hidden");
    //pedimos los elementos del modal al servidor
    //bsucamos la api, convierte la respuesta a JSON
    fetch("api/get_game.php?id=" + id)
       .then(function(response) {
           return response.json();
    })
    //conseguimos los datos del juego y trabajamos con ellos
    .then(function(game) {            
            document.getElementById("modal-title").textContent = game.title;
            document.getElementById("modal-description").textContent = game.description;
            document.getElementById("modal-price").textContent = "$" + game.price;
            document.getElementById("modal-platform").textContent = game.platform;
            document.getElementById("modal-cover").src = "img/" + game.cover_image;
            document.getElementById("modal-online").textContent = game.is_online ? "Online" : "Offline";
        }); 
}

function buyGame() {
    // Implementar la lógica de compra del juego
    fetch("api/buy.php?id=" + currentGameId)
    //lo hemos tenido que poner de esta manera ya que no tenuamos ningun id en el HTML
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            if (result.success) {
                alert("Game purchased successfully!");
            } else {
                alert("Error while purchasing the game.");
            }
        });
}

