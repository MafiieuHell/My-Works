<?php

function getConnection(): PDO
{
    $host = 'db.3wa.io';
    $dbname = 'cedricleclinche_classicmodels';
    $username = 'cedricleclinche';
    $password = 'eb094434df8b9e10f67b5c650f7bed6c';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $username, $password, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    return $db;
}