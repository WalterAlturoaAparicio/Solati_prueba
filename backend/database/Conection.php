<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class Database
{
    // Variable privada estática para almacenar la única instancia de la conexión
    private static $instance = null;
    private $connection;
    private $host;
    private $port;
    private $db_name;
    private $db_user;
    private $db_password;

    // Datos de conexión a la base de datos

    // Constructor privado para evitar que se cree una instancia directamente
    private function __construct()
    {
        $this->host = $_ENV['HOST'];
        $this->port = $_ENV['PORT'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->db_user = $_ENV['DB_USER'];
        $this->db_password = $_ENV['DB_PASSWORD'];
    }

    // Método público para obtener la única instancia de la conexión
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método público para obtener la conexión
    public function getConnection()
    {
        // Lógica para crear la base de datos si no existe
        try {
            // Crear la conexión sin especificar la base de datos
            $this->connection = new PDO("pgsql:host={$this->host};port={$this->port};user={$this->db_user};password={$this->db_password}");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Crear la base de datos si no existe
            $this->createDatabaseIfNotExists();

            // cambio de conexion con la base de datos creada
            $this->connection = new PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};user={$this->db_user};password={$this->db_password}");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error de conexión o creación de base de datos: ' . $e->getMessage();
            exit;
        }   

        return $this->connection;
    }

    function createDatabaseIfNotExists()
    {
        // Verificar si la base de datos existe, y si no, crearla
        $existsQuery = "SELECT 1 FROM pg_database WHERE datname = :dbName";
        $stmt = $this->connection->prepare($existsQuery);
        $stmt->execute(['dbName' => $this->db_name]);

        if ($stmt->rowCount() === 0) {
            // La base de datos no existe, así que la creamos
            $createDbQuery = "CREATE DATABASE $this->db_name";
            $result = $this->connection->query($createDbQuery);
        }
    }

    public function createTableIfNotExists()
    {
        try {
            $pdo = $this->getConnection();
            // Nombre de la tabla a crear
            $tableName = 'pelicula';

            // Consulta SQL para crear la tabla si no existe
            $createQuery = "CREATE TABLE IF NOT EXISTS $tableName (
                id SERIAL PRIMARY KEY,
                nombre VARCHAR(255) NOT NULL,
                genero VARCHAR(100) NOT NULL,
                clasificacion VARCHAR(50) NOT NULL,
                anio_lanzamiento INT NOT NULL,
                imagen VARCHAR(255) NOT NULL
            )";
            $pdo->exec($createQuery);

            // Consulta SQL para contar el número de registros en la tabla
            $countQuery = "SELECT COUNT(*) FROM $tableName";
            $stmt = $pdo->query($countQuery);
            $rowCount = $stmt->fetchColumn();
            
            // Llenar la tabla con 10 registros aleatorios con rutas de imágenes reales si esta vacia
            if ($rowCount === 0) {
                for ($i = 1; $i <= 10; $i++) {
                    $nombre = 'Película ' . $i;
                    $genero = 'Género ' . $i;
                    $clasificacion = 'Clasificación ' . $i;
                    $anioLanzamiento = rand(2000, 2023);
                    $imagen = "pelicula_$i.jpg"; // Ruta de la imagen real de la película

                    // Consulta SQL para insertar un registro en la tabla
                    $insertQuery = "INSERT INTO $tableName (nombre, genero, clasificacion, anio_lanzamiento, imagen)
                                    VALUES ('$nombre', '$genero', '$clasificacion', $anioLanzamiento, '$imagen')";
                    $pdo->exec($insertQuery);
                }
            }
            
            return true;
        } catch (PDOException $e) {
            // Manejar cualquier error aquí si es necesario
            return false;
        }
    }

    // Evitar que la conexión se clone
    private function __clone()
    {
    }
}
?>