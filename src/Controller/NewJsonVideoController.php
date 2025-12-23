<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Alura\Mvc\Entity\Video;
use Psr\Http\Message\ResponseInterface;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NewJsonVideoController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
        
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //Inserção via API
        // 1. Em vez de file_get_contents('php://input'), pegamos o corpo pelo PSR-7
        // O corpo é um Stream, então convertemos para string
        $request = $request->getBody()->getContents();

        // 2. Decodificamos o JSON
        $videoData = json_decode($request, true);

        // 3. Criamos e salvamos a entidade
        // Verifique se os nomes das chaves no JSON batem com seu código (ex: 'url' e 'title')
        $video = new Video($videoData['url'], $videoData['title']); 
        $this->videoRepository->add($video);

        // 4. Retornamos uma Response PSR-7 com status 201 (Created)
       return new Response(201);
    }

}