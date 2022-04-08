<?php

ini_set('display_errors', 'on');

require 'functions.php';

$db = getConnection();

$query = $db->prepare('
    SELECT customerName, contactFirstName, contactLastName, addressLine1, city
    FROM customers
    INNER JOIN orders ON customers.customerNumber = orders.customerNumber
    WHERE orderNumber = ?
');

$query->execute([
    $_GET['order']
]);

// $customer = $query->fetchAll();

// Récupération de la première ligne
$customer = $query->fetch();

// Etape 2 : récupérer la liste des détails de la commande
$query = $db->prepare('
    SELECT productName, quantityOrdered, priceEach, (quantityOrdered * priceEach) AS total
    FROM orderdetails
    INNER JOIN products ON products.productCode = orderdetails.productCode
    WHERE orderNumber = ?
');

$query->execute([
    $_GET['order']
]);

$orderdetails = $query->fetchAll();

// Etape 3 : récupérer le total de la commande
$query = $db->prepare('
    SELECT SUM(quantityOrdered * priceEach) AS totalHT, SUM(quantityOrdered * priceEach) * 0.2 AS totalTVA, SUM(quantityOrdered * priceEach) * 1.2 as totalTTC
    FROM orderdetails
    WHERE orderNumber = ?
');

$query->execute([
    $_GET['order']    
]);

// Une seule ligne => fetch
$results = $query->fetch();


$template = 'detail';
require 'layout.phtml';