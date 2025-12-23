<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;


use League\Plates\Engine;
use Nyholm\Psr7\Response;

use Psr\Http\Message\ResponseInterface;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class VideoListController implements RequestHandlerInterface
{
    
    
    public function __construct(
        private VideoRepository $videoRepository,
        private Engine $templates,
        )
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        
        // 1. Busca os dados no repositório
        $videoList = $this->videoRepository->all();

         

        // 3. Retorna uma nova instância de Response com o HTML no corpo
        return new Response(
            200, 
            [],
            $this->templates->render(
                'video-list',
                ['videoList' => $videoList]
                )
            ); 
    }
}