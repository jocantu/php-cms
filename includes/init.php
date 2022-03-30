<?php
    ob_start();
    session_start();
    //  *************** For PostgreSQL
        $dsn = "pgsql:host=localhost;dbname=login;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', 'mu2j3jy5', $opt);

    $from_email = "admin@mail.com";
    $reply_email = "admin@mail.com";
    $root_directory = "login";

    include "php_functions.php"
?>