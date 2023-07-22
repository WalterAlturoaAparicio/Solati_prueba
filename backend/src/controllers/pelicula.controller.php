<?php
require_once '../models/pelicula.model.php';

$method = $_SERVER['REQUEST_METHOD'];
$dao = new PeliculaDAO();

switch ($method) {
    case 'GET':
        // Obtener todas las películas
        try {
            $peliculas = $dao->obtenerTodasPeliculas();
            if ($peliculas !== false) {
                $response = array('success' => true, 'data' => $peliculas);
            } else {
                http_response_code(500); 
                $response = array('success' => false, 'message' => 'Error al obtener las películas');
            }
        } catch (PDOException $e) {
            http_response_code(500);
            $response = array('success' => false, 'message' => 'Error de base de datos al obtener las películas');
        }
        break;
    case 'POST':
        // Recuperar los datos del formulario de creación de película
        $data = json_decode(file_get_contents('php://input'), true);

        // Validar que se proporcionen todos los campos necesarios
        if (empty($data['nombre']) || empty($data['genero']) || empty($data['clasificacion']) || empty($data['anio_lanzamiento']) || empty($data['imagen'])) {
            $response = array('success' => false, 'message' => 'Todos los campos son requeridos');
        } else {
            // Crear una nueva película
            $result = $dao->agregarPelicula($data['nombre'], $data['genero'], $data['clasificacion'], $data['anio_lanzamiento'], $data['imagen']);
            if ($result) {
                $response = array('success' => true, 'message' => 'Película creada exitosamente');
            } else {
                $response = array('success' => false, 'message' => 'Error al crear la película');
            }
        }
        break;
    case 'DELETE':
        // Recuperar el ID de la película a eliminar
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];

        // Eliminar la película
        $result = $dao->eliminarPeliculaPorId($id);
        if ($result) {
            $response = array('success' => true, 'message' => 'Película eliminada exitosamente');
        } else {
            $response = array('success' => false, 'message' => 'Error al eliminar la película');
        }
        break;
    default:
        $response = array('success' => false, 'message' => 'Método no permitido');
        break;
}


// Devolver la respuesta al frontend
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
echo json_encode($response);
?>