<?php

ini_set('display_errors', 'on');

require 'functions.php';

$db = getConnection();

$query = $db->prepare('
    SELECT orderNumber, orderDate, status, customerName
    FROM orders
    INNER JOIN customers ON customers.customerNumber = orders.customerNumber
    ORDER BY orderDate
');

$query->execute();

$orders = $query->fetchAll();

// var_dump($orders);

$template = 'index';
$title = 'Toutes les commandes';
require 'layout.phtml';