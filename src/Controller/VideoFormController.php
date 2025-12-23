<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use PDO;
use Nyholm\Psr7\Response;
use Alura\Mvc\Helper\HtmlRendererTrait;
use Psr\Http\Message\ResponseInterface;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VideoFormController implements RequestHandlerInterface
{
    use HtmlRendererTrait;
    public function __construct(private VideoRepository $repository)
    {
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $id = filter_var($queryParams['id'] ??  '', 
        FILTER_VALIDATE_INT);
        
        /**@var ?Video $video */
        $video = null;
        if ($id !== false && $id !== null) {
            $video = $this->repository->find($id);
        }

        // 2. Renderizar o HTML usando a Trait
        // O renderTemplate deve retornar uma string com o HTML
        $html = $this->renderTemplate(
            'video-form',
            ['video' => $video]
        );
        
        //3. Retornar uma resposta PSR-7 contendo o HTML no corpo
        return new Response(200, [], $html);
        
    }
}

