<?php
 
//Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=db.3wa.io;port=3306;dbname=lukatrehout_gestionnaire_taches;charset=utf8";
    $db = new PDO($dsn,"lukatrehout", "872c9df53a4d151ccc68bc096323ab7b");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    return $db;
}