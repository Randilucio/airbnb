<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=airbnb;charset=utf8','root','');
} catch(PDOException $e) {
    die($e->getMessage());
}

$parPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$depart = ($page - 1) * $parPage;

$sth = $dbh->prepare("SELECT * FROM `listing` LIMIT :start, :limit");
$sth->bindValue(':start', $depart, PDO::PARAM_INT);
$sth->bindValue(':limit', $parPage, PDO::PARAM_INT);
$sth->execute();

$resultats = $sth->fetchAll(PDO::FETCH_ASSOC);

$total = $dbh->query("SELECT COUNT(*) FROM `100`")->fetchColumn();
$nbPages = ceil($total / $parPage);

?>