<?php
declare(strict_types=1);

namespace Alura\Mvc\Helper;

trait HtmlRendererTrait
{
    //traits não poderiam ter constantes, mas agora podem
    private const TEMPLATE_PATH = __DIR__ . '/../../views/';
    protected function renderTemplate(string $templateName, array $context = []): string
    {
        $templatePath = __DIR__ .'/../../views/';
        extract($context);

        //Inicializa um buffer de saída
        ob_start();
        //self porque não é uma constante global e sim constante da classe
        require self::TEMPLATE_PATH . $templateName . '.php';

        //me dá o conteúdo $ob_get_contents() desse buffer + limpa ob_clean o buffer = obg_get_clean()
        
        return ob_get_clean();
    }
}
