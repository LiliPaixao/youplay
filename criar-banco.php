<?php

declare(strict_types=1);

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");
$pdo->exec('CREATE TABLE videos (
    id INTEGER PRIMARY KEY,
     url TEXT,
     title TEXT,
     image_path TEXT
     );
    ');

