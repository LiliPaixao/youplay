<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;


use Alura\Mvc\Helper\HtmlRendererTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VideoListController implements RequestHandlerInterface
{
    use HtmlRendererTrait;
    
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        
        // 1. Busca os dados no repositório
        $videoList = $this->videoRepository->all();

        // 2. Armazena o HTML gerado pela Trait em uma variável (em vez de dar echo)
        $html = $this->renderTemplate(
            'video-list',
            ['videoList' => $videoList]
        );

        // 3. Retorna uma nova instância de Response com o HTML no corpo
        return new Response(200, [], $html);
    }
}