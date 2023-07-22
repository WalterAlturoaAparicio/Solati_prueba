<?php
require_once '../../database/Conection.php';
$usuarioLogeado = false;
// Verificar si se recibió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario de inicio de sesión (usuario y contraseña)
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];

    // Verificar el usuario y contraseña (puedes utilizar tus valores preestablecidos aquí)
    if ($username === 'pruebas' && $password === 'prueba123') {
        // Inicio de sesión exitoso
        $usuarioLogeado = true;
        $response = array('success' => true,'loggedIn' => $usuarioLogeado, 'message' => 'Conexion establecida, base de datos establecida (si no existia), tabla peliculas creada y llenada (si no existia)');

        // se llama a la conexion que valida si la db existe o no
        $db = Database::getInstance();
        $db->getConnection();

        // Crear la tabla en la base de datos
        $db->createTableIfNotExists();

    } else {
        // Inicio de sesión fallido
        $response = array('success' => false, 'loggedIn' => $usuarioLogeado, 'message' => 'Usuario o contraseña incorrectos');
    }

    // Devolver la respuesta al frontend
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Credentials: true');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>