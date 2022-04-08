<?php

ini_set('display_errors', 'on');

require 'functions.php';

$db = getConnection();

$resultsPerPage = 15;

$query = $db->prepare('
    SELECT COUNT(orderNumber) AS totalOrders
    FROM orders
');

$query->execute();

$results = $query->fetch();

// Nombre total de pages = nombre commandes / nombre de résultats par page
$totalPages = ceil($results['totalOrders'] / $resultsPerPage);

// Page actuelle
if (isset($_GET['page'])) {
    $currentPage = (int) $_GET['page'];
} else {
    $currentPage = 1;
}

$offset = $resultsPerPage * ($currentPage - 1);

$query = $db->prepare("
    SELECT orderNumber, orderDate, status, customerName
    FROM orders
    INNER JOIN customers ON customers.customerNumber = orders.customerNumber
    ORDER BY orderDate
    LIMIT $resultsPerPage OFFSET $offset
");

$query->execute();

$orders = $query->fetchAll();

$template = 'pagination';
require 'layout.phtml';