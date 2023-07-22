<?php
require_once '../../database/Conection.php';

class PeliculaDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para agregar una película a la base de datos
    public function agregarPelicula($nombre, $genero, $clasificacion, $anioLanzamiento, $imagen)
    {
        $sql = "INSERT INTO pelicula (nombre, genero, clasificacion, anio_lanzamiento, imagen)
                VALUES (:nombre, :genero, :clasificacion, :anioLanzamiento, :imagen)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':clasificacion', $clasificacion);
        $stmt->bindParam(':anioLanzamiento', $anioLanzamiento);
        $stmt->bindParam(':imagen', $imagen);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Manejo de error en caso de fallo
            // Devolver mensaje de error o lanzar excepción según sea necesario
            return false;
        }
    }

    // Método para obtener todas las películas de la base de datos
    public function obtenerTodasPeliculas()
    {
        $sql = "SELECT * FROM pelicula";
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $peliculas;
    }

    // Método para obtener una película por su ID
    public function obtenerPeliculaPorId($id)
    {
        $sql = "SELECT * FROM pelicula WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pelicula;
    }

    // Método para eliminar una película por su ID
    public function eliminarPeliculaPorId($id)
    {
        $sql = "DELETE FROM pelicula WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Manejo de error en caso de fallo
            // Devolver mensaje de error o lanzar excepción según sea necesario
            return false;
        }
    }
}
?>