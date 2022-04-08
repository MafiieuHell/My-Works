# Liste des commandes

## Instructions

### Afficher la liste des commandes

1. Créer un fichier index.php pour le traitement et un fichier index.phtml pour l'affichage.

2. Dans le fichier index.php : 
  * utiliser PDO pour se connecter à votre base de données
  * récupérer la liste des commandes (numéro, date, statut, nom du client) triées par date de commande
  * stocker le résultat dans une variable

3. Dans le fichier index.phtml : 
  * parcourir la liste des commandes (stockée dans la variable précédemment créée)
  * afficher le tout dans un tableau html

### Afficher le détail d'une commande

#### Récupérer le numéro de la commande sélectionnée

Créer un fichier detail.php pour le traitement et un fichier detail.phtml.
Pour afficher le détail d'une commande, il est nécessaire d'avoir le numéro de la commande. Il faut donc faire en sorte de transmettre l'information entre les 2 pages.

Dans le fichier index.phtml, modifier le tableau affichant la liste des commandes pour ajouter un lien vers la page detail.php :

```php
<td><a href="detail.php"><?= $order['orderNumber'] ?></a></td>
```

Pour transmettre le numéro de la commande vers la page detail.php, on va le stocker dans la **chaîne de requête** du lien *detail.php*.

```php
<td><a href="detail.php?order=<?= $order['orderNumber'] ?>"><?= $order['orderNumber'] ?></a></td>
```

Cette ligne va générer les liens vers la page *detail.php* de manière dynamique. Les lien ressembleront à ceci : 

```php
<tr>
    <td><a href="detail.php?order=10100">10100</a></td>
    ...
</tr>
<tr>
    <td><a href="detail.php?order=10101">10101</a></td>
    ...
</tr>
...
<tr>
    <td><a href="detail.php?order=10110">10110</a></td>
    ...
</tr>
```

Lorsque vous cliquez sur le lien, vous devriez arriver sur la page detail.php. Dans l'url il devrait être indiqué *detail.php?order={le numéro de la commande sélectionnée}*.
Dans le fichier *detail.php* vous pouvez maintenant récupérer le numéro de la commande sur laquelle vous avez cliqué en utilisant *$_GET*

```php
var_dump($_GET['order']);
```

La clé *order* correspond à ce qu'il y a dans la chaîne de requête : *detail.php?__order__=...*.

#### Récupérer les informations du client de la commande correspondante

Pour utiliser le numéro de la commande dans l'url :

```php
$db = new PDO(...);

$query = $db->prepare('SELECT ... FROM orders WHERE orderNumber = ?');
$query->execute([
    $_GET['order']
]);
```

Dans les requêtes préparées, pour des raisons de sécurité, vous devez toujours mettre des *?* dans la clause WHERE lorsqu'il s'agit de données que l'utilisateur peut modifier (tout ce qu'il y dans *$_GET* et *$_POST*).
La valeur de ces *?* sera ensuite renseignée dans le tableau de la fonction *execute*.

#### Récupérer les détails de la commande correspondante

## Rappels

Utiliser la boucle *foreach* pour parcourir un tableau.

```php
<ul>
    <?php foreach ($customers as $customer): ?>
        <li><?= $customer['customerName'] ?></li>
    <?php endforeach ?>
</ul>
```

## BONUS

### Factoriser le code

Sur les 2 pages créées on réécrit à chaque fois la connexion à la base de données avec les identifiants.
Idéalement on utilisera plutôt une fonction pour récupérer la connexion à la base de données pour ne pas avoir à tout réécrire à chaque fois.

Dans un fichier *functions.php*, créer la fonction *getConnection* qui renvoie la connexion à la base de données.

```php
function getConnection(): PDO
{
    $db = new PDO(...);
    
    return $db;
}
```

Puis utiliser la fonction dans les fichiers *index.php* et *detail.php*.

```php
require 'functions.php';

$db = getConnection();
```

### Pagination

Afin de soulager le navigateur, on n'affichera plus toutes les commandes d'un seul coup mais seulement 15 résultats par page.
Ce système de pagination se fait en 3 étapes :
1. Récupérer le nombre de pages et créer les liens de pagination
2. Récupérer la page actuelle
3. Récupérer les résultats de la page actuelle

#### Récupérer le nombre de pages

Pour récupérer le nombre de pages total, il faut dans un premier temps compter le nombre de résultats total à l'aide de la fonction *COUNT* puis diviser ce résultat par le nombre de lignes par page voulu.
Par exemple : s'il y a 305 commandes et que l'on veut 15 résultats par page, il doit y avoir un total de 21 pages (305 / 15, arrondi au supérieur).

Maintenant dans la page html on peut afficher les liens de notre pagination : 

```php
<ul class="pagination">
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <li><a href="?page=<?= $p ?>"><?= $p ?></a></li>
    <?php endfor ?>
</ul>
```

#### Récupérer la page actuelle

Le numéro de la page se situe dans l'url : *?page=2*
Si jamais le numéro ne se trouve pas dans l'url (ce qui est le cas quand on arrive sur le site pour la première fois), on considère par défaut qu'on est sur la page n°1.

```php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
```

#### Récupérer les résultats de la page actuelle

Il faut utiliser les mots-clés *limit* et *offset* pour récupérer les résultats correspondant à une page bien précise.

```sql
# Récupère les 15 premiers résultats (page 1)
SELECT *
FROM orders
LIMIT 15 OFFSET 0

# Récupère les 15 résultats suivants (page 2)
SELECT *
FROM orders
LIMIT 15 OFFSET 15

# Récupère les 15 résultats suivants (page 3)
SELECT *
FROM orders
LIMIT 15 OFFSET 30
```

A partir du numéro de la page, il est possible de retrouver, à l'aide d'un calcul simple, l'offset à utiliser.

offset = nombre de résultats par page * (numéro de la page - 1)