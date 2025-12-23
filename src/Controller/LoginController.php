<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Alura\Mvc\Helper\FlashMessageTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController implements Controller
{
    use FlashMessageTrait;

    private \PDO $pdo;
    public function __construct()
    {
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new \PDO("sqlite:$dbPath");
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();

        //Buscar usuário no banco de dados usando e-mail
        $email = filter_var($postData['email'] ?? '',FILTER_VALIDATE_EMAIL, FILTER_VALIDATE_EMAIL);
        $password = $postData['password'] ?? '';

        $sql = 'SELECT * FROM users WHERE email = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1,$email);
        $statement->execute();

        $userData = $statement->fetch(\PDO::FETCH_ASSOC);
        $correctPassword = password_verify($password, $userData['password'] ?? '');


        //Rehash da senha se necessário
        if ($userData && password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) {
            $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2ID));
            $statement->bindValue(2, $userData['id']);
            $statement->execute();
        }

        if ($correctPassword) {
            $_SESSION['logado'] = true;
            return new Response(302, [
                'Location' => '/'
            ]);
        } else {
            $this->addErrorMessage('Usuário ou senha inválidos');
            return new Response(302, [
                'Location' => '/login'
            ]);
        }

    }
}