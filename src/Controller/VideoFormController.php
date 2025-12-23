<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use PDO;
use League\Plates\Engine;
use Nyholm\Psr7\Response;

use Psr\Http\Message\ResponseInterface;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VideoFormController implements RequestHandlerInterface
{
    
    public function __construct(
        private VideoRepository $repository,
        private Engine $templates
        ){
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

               
        //3. Retornar uma resposta PSR-7 contendo o HTML no corpo
        return new Response(
            200,
            [],
            $this->templates->render(
                'video-form',
                ['video' => $video]
            )
        );
    }
}

