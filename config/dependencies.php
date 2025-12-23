<?php

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
]);


/** @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();     

return $container;