<?php
if (
    !isset($_POST['titre'], $_POST['description'], $_POST['type'], 
    $_POST['adresse'], $_POST['prix'], $_POST['capacite'], $_POST['image_url'])
){
    die("Formulaire incomplet.");
}

$titre = htmlspecialchars($_POST['titre']);
$description = htmlspecialchars($_POST['description']);
$type = htmlspecialchars($_POST['type']);
$adresse = htmlspecialchars($_POST['adresse']);
$prix = (int) $_POST['prix'];
$capacite = (int) $_POST['capacite'];
$equipements = htmlspecialchars($_POST['equipements'] ?? '');
$image_url = htmlspecialchars($_POST['image_url']);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=airbnb;charset=utf8','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$sql = "INSERT INTO user_listings 
        (titre, description, type, adresse, prix, capacite, equipements, image_url)
        VALUES 
        (:titre, :description, :type, :adresse, :prix, :capacite, :equipements, :image_url)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':titre' => $titre,
    ':description' => $description,
    ':type' => $type,
    ':adresse' => $adresse,
    ':prix' => $prix,
    ':capacite' => $capacite,
    ':equipements' => $equipements,
    ':image_url' => $image_url
]);

echo "<h3>Annonce ajoutée avec succès !</h3>";
echo '<a href="formulaire.html">Revenir au formulaire</a>';
?>
