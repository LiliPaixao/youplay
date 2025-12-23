<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Alura\Mvc\Entity\Video;
use Psr\Http\Message\ResponseInterface;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EditVideoController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Pegar ID da Query String
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'] ?? null, FILTER_VALIDATE_INT);
        
        if ($id === false || $id === null) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        //Pegar dados do formulário(Corpo da requisição )
        $postParams = $request->getParsedBody();
        $url = filter_var($postParams['url'] ?? '', FILTER_VALIDATE_URL);
        $titulo = filter_var($postParams['titulo'] ?? '', FILTER_DEFAULT);
        if ($url === false || $titulo === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        $video = new Video($url, $titulo);
        $video->setId($id);


        //Lidar com upload de arquivos via PSR-7
        $files = $request->getUploadedFiles();
        /**@var ?UploadedFileInterface $uploadedFile */
        $uploadedFile = $files['image'] ?? null;

        if ($uploadedFile !== null && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $tmpPath = $uploadedFile->getStream()->getMetadata('uri');
            $mimeType = $finfo->file($tmpPath);

            //validar se é imagem
            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedFile->getClientFilename(), PATHINFO_BASENAME);
                $uploadedFile->moveTo(__DIR__ . '/../../public/img/uploads/' . $safeFileName);
                $video->setFilePath($safeFileName);
            }
        }

        //salvar e retornar resposta
        $success = $this->videoRepository->update($video);
       
        if ($success === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        return new Response(302, ['Location' => '/?sucesso=1']);
    }
}
