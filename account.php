<?php
 
include ('connexion.php');
 
if (!empty($_POST)){
    $db = connectToDB();
    $title = $_POST['title'];
    $content = $_POST['content'];
 
$requete = $db->prepare("INSERT INTO tasks (title,content,urgent,important) VALUES (:title, :content, :urgent, :important)");
 
$requete->execute([
    ':title' => $title,
    ':content' => $content,
    ':urgent' => 0,
    ':important' => 0
    ]);
}
else {
    $requête = $db->prepare("SELECT * FROM tasks");
    $tasks = $requete->fetchAll(PDO::FETCH_ASSOC);
}
include ('account.phtml');