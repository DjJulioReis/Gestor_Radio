<?php
// init.php - Arquivo de inicialização central

/**
 * Carrega variáveis de ambiente de um arquivo .env para o ambiente.
 *
 * @param string $path O caminho para o arquivo .env.
 */
function loadEnv($path)
{
    if (!file_exists($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignora comentários
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Define a variável de ambiente
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Carrega as variáveis de ambiente do arquivo .env localizado no mesmo diretório
loadEnv(__DIR__ . '/.env');

// Habilita a exibição de erros com base na variável de ambiente APP_ENV
if (getenv('APP_ENV') !== 'production') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    // Considere configurar um caminho para o log de erros em produção
    // error_log("Log de erros da aplicação");
}

// Carrega as configurações da aplicação
require_once 'config/app.php';

// O arquivo config/db.php não é mais necessário. As credenciais vêm do .env.

// Inicia a sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtém as credenciais do banco de dados a partir das variáveis de ambiente
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');

// Valida se as variáveis foram carregadas
if (!$db_host || !$db_user || !$db_name) {
    error_log("As variáveis de ambiente do banco de dados não foram carregadas corretamente.");
    die("Ocorreu um problema na configuração do sistema. Contacte o suporte.");
}

// Estabelece a conexão com o banco de dados
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica a conexão
if ($conn->connect_error) {
    error_log("Erro de conexão com o banco de dados: " . $conn->connect_error);
    die("Ocorreu um problema ao conectar com o sistema. Tente novamente mais tarde.");
}

// Define o charset para UTF-8 para evitar problemas com caracteres especiais
$conn->set_charset("utf8mb4");
?>
