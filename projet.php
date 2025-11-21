<?php
$pdo = new PDO("mysql:host=localhost;dbname=airbnb;charset=utf8","root","");

$allowedSort = [
    'name' => 'name',
    'city' => 'neighbourhood_group_cleansed',
    'price' => 'price',
    'host' => 'host_name'
];

$sort = $_GET['sort'] ?? 'name';
$orderBy = $allowedSort[$sort] ?? 'name';

$lpage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$start = ($page - 1) * $perPage;

$totalAirbnb = $pdo->query("SELECT COUNT(*) FROM listings")->fetchColumn();
$totalUser = $pdo->query("SELECT COUNT(*) FROM user_listings")->fetchColumn();
$total = $totalAirbnb + $totalUser;

$totalPages = ceil($total / $lpage);

$sqlAirbnb = "SELECT * FROM listings 
              ORDER BY $orderBy 
              LIMIT :start, :limit";

$stmt = $pdo->prepare($sqlAirbnb);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $lpage, PDO::PARAM_INT);
$stmt->execute();
$airbnbListings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userListings = $pdo->query("SELECT * FROM user_listings")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Liste des annonces</title>
    </head>
    <body>

        <h2>Liste des logements</h2>

        <form method="GET">
            <label>Trier par : </label>
            <select name="sort" onchange="this.form.submit()">
                <option value="name" <?= $sort=='name'?'selected':'' ?>>Nom</option>
                <option value="city" <?= $sort=='city'?'selected':'' ?>>Ville</option>
                <option value="price" <?= $sort=='price'?'selected':'' ?>>Prix</option>
                <option value="host" <?= $sort=='host'?'selected':'' ?>>Propriétaire</option>
            </select>
        </form>

        <hr>

        <h3>Logements Airbnb</h3>

        <?php foreach ($airbnbListings as $l): ?>
        <div class="listing">
            <img src="<?= $l['picture_url'] ?>" alt="Image">

            <div>
                <h3><?= $l['name'] ?></h3>
                <p>Hôte : <?= $l['host_name'] ?></p>
                <p>Ville : <?= $l['neighbourhood_group_cleansed'] ?></p>
                <p>Prix : <?= $l['price'] ?> €</p>
                <p>Note : <?= $l['review_scores_value'] ?>/5</p>
            </div>
        </div>
        <?php endforeach; ?>

        <hr>

        <h3>Vos annonces</h3>

        <?php foreach ($userListings as $u): ?>
        <div class="listing">
            <img src="<?= $u['image_url'] ?>" alt="Image">

            <div>
                <h3><?= $u['titre'] ?></h3>
                <p><?= $u['description'] ?></p>
                <p><strong>Type : <?= $u['type'] ?></p>
                <p><strong>Adresse : <?= $u['adresse'] ?></p>
                <p><strong>Prix : <?= $u['prix'] ?> €</p>
                <p><strong>Capacité : <?= $u['capacite'] ?> pers</p>
                <p><strong>Équipements : <?= $u['equipements'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>

        <div></div>
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&sort=<?= $sort ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="current-page"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>&sort=<?= $sort ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>&sort=<?= $sort ?>">Suivant</a>
            <?php endif; ?>
        </div>

    </body>
</html>
