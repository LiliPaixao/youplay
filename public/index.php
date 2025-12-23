<?php

declare(strict_types=1);

use Alura\Mvc\Controller;
use Alura\Mvc\Controller\Error404Controller;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once __DIR__ . '/../vendor/autoload.php';

// 1. Configuração do Objeto de Requisição PSR-7
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

// Cria a requisição a partir das superglobais ($_SERVER, $_POST, $_GET, etc.)
$request = $creator->fromGlobals();

session_start();
session_regenerate_id(); //altera o valor de cookie de sessão

$dbPath = __DIR__ . '/../banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");
$videoRepository = new VideoRepository($pdo);

$routes = require_once __DIR__ . '/../config/routes.php';
/**
 * @var \Psr\Container\ContainerInterface $diContainer
 */
$diContainer = require_once __DIR__ . '/../config/dependencies.php';

// 2. Pegar Path e Método do objeto $request (PSR-7)
$pathInfo = $request->getUri()->getPath();
$httpMethod = $request->getMethod();

// 3. Lógica de Autenticação com redirecionamento PSR-7
$isLoginRoute = $pathInfo === '/login';
if (!array_key_exists('logado', $_SESSION) && !$isLoginRoute) {
   http_response_code(302);
   header('Location: /login');
   exit();
}

$key = "$httpMethod|$pathInfo";
if (array_key_exists($key, $routes)) {
        $controllerClass = $routes["$httpMethod|$pathInfo"];
         
        $controller = $diContainer->get($controllerClass);
} else {
                 
         $controller = new Error404Controller();
}




// 4. EXECUTAR O CONTROLLER E RECEBER A RESPOSTA
/** @var \Psr\Http\Server\RequestHandlerInterface $controller */
$response = $controller->handle($request);

// 5. EMITIR A RESPOSTA (Enviar para o navegador)
// Enviar o código de status (200, 302, 404, etc.)
http_response_code($response->getStatusCode());

// Enviar os cabeçalhos (Location, Content-Type, etc.)
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

// Enviar o corpo (HTML ou JSON)
echo $response->getBody();