<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Entity\Video;
use Nyholm\Psr7\Response;
use Alura\Mvc\Helper\FlashMessageTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NewVideoController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //Take data with post
        $postData = $request->getParsedBody();

        $url = filter_var($postData['url'] ?? '', FILTER_VALIDATE_URL); 
        if ($url === false) {
            $this->addErrorMessage('URL inválida');
            return new Response(302,
            [
                'Location' => '/novo-video'
            ]);
        }

        $titulo = filter_var($postData['titulo'] ?? '', FILTER_DEFAULT);
        if (empty($titulo)) {
            $this->addErrorMessage('Título não informado');
            return new Response(302,
            [
                'Location' => '/novo-video'
            ]   );
        }

        // Incluir slug
        $video = new Video($url, $titulo);

        $uploadedFiles = $request->getUploadedFiles();

        /**@var ?UploadedFileInterface $uploadedFile */
        $uploadedFile = $uploadedFiles['image'] ?? null;

        if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $tmpPath = $uploadedFile->getStream()->getMetadata('uri');
            $mimeType = $finfo->file($tmpPath);
            

            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_'. pathinfo($uploadedFile->getClientFilename(), PATHINFO_BASENAME);
                
                // Move uploaded file to uploads directory
                $uploadedFile->moveTo(__DIR__ . '/../../public/img/uploads/' .  $safeFileName);
                $video->setFilePath( $safeFileName);
            }
        }


        $sucess = $this->videoRepository->add($video);
        if ($sucess === false) {
            $this->addErrorMessage('Erro ao cadastrar vídeo');
            return new Response(302, [
                'Location' => '/novo-video'
            ]);
        } else {
            return new Response(302, [
                'Location' => '/?sucesso=1'
            ]);
        }
    }
}