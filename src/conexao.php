<?php

function conectar() {
    return new PDO(
        'mysql:dbname=acme;host=localhost;charset=utf8',
        'root',
        '',
        [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
    );
}
?>