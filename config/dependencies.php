<?php

use League\Plates\Engine;

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions([
    //Como factory
    PDO::class => function () {
        $dbPath = __DIR__ . '/../banco.sqlite';
        return new PDO("sqlite:$dbPath");
    },

    // como função
    // PDO::class => \DI\create(PDO::class)
    //     ->constructor('sqlite:' . __DIR__ . '/../banco.sqlite'), 
    
    League\Plates\Engine::class => function () {
        $templatePath = __DIR__ . '/../views';
        return new Engine($templatePath, 'php');
    },
]);


/** @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();     

return $container;