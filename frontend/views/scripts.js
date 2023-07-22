// Función para obtener y mostrar las películas en la tabla
function mostrarPeliculas() {
  fetch(
    "http://localhost/solati/backend/src/controllers/pelicula.controller.php",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const peliculas = data.data
        const tablaPeliculas = document.getElementById("tabla-peliculas")

        // Limpiar la tabla antes de agregar los nuevos registros
        tablaPeliculas.innerHTML = ""

        // Agregar cada película a la tabla
        peliculas.forEach((pelicula) => {
          const fila = document.createElement("tr")
          fila.innerHTML = `
              <td>${pelicula.id}</td>
              <td>${pelicula.nombre}</td>
              <td>${pelicula.genero}</td>
              <td>${pelicula.clasificacion}</td>
              <td>${pelicula.anio_lanzamiento}</td>
              <td><img src="../images/${pelicula.imagen}" alt="${pelicula.nombre}" width="100"></td>
              <td><button onclick="eliminarPelicula(${pelicula.id})">Eliminar</button></td>
            `
          tablaPeliculas.appendChild(fila)
        })
      } else {
        console.error("Error al obtener las películas: ", data.message)
      }
    })
    .catch((error) => {
      console.error("Error de conexión con el servidor: ", error)
    })
}
// Función para eliminar una película
function eliminarPelicula(id) {
  fetch(
    "http://localhost/solati/backend/src/controllers/pelicula.controller.php",
    {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: id }),
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Película eliminada exitosamente")
        mostrarPeliculas()
      } else {
        alert("Error al eliminar la película")
      }
    })
    .catch((error) => {
      console.log("Error de conexión con el backend " + error)
    })
}

document.addEventListener("DOMContentLoaded", function () {
  mostrarPeliculas()
})
