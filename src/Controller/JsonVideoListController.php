<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Alura\Mvc\Entity\Video;
use Alura\Mvc\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonVideoListController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
        //
    }

    public function handle(ServerRequestInterface $request): ResponseInterface  
    {
        $videoList = array_map(function(Video $video): array {
            return [
                'url'=> $video->url,
                'title'=> $video->title,
                'file_path' => 
                    $video->getFilePath() === null? null :'/img/uploads/' . $video->getFilePath(),
            ];
        }, $this->videoRepository->all());
        return new Response(
            200, [
                'Content-Type' => 'application/json'
            ], 
            json_encode($videoList, 
            JSON_UNESCAPED_SLASHES));
    }
}