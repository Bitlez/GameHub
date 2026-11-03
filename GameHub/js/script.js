const buscador = document.getElementById("buscarJuego");
const juegos = document.querySelectorAll(".tarjeta-juego");

buscador.addEventListener("input", function() {

    const texto = buscador.value.toLowerCase();

    juegos.forEach(function(juego) {

        const nombre = juego.querySelector("h3").textContent.toLowerCase();

        if (nombre.includes(texto)) {
            juego.style.display = "block";
        } else {
            juego.style.display = "none";
        }

    });

});