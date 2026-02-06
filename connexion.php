<?php

//Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=db.3wa.io;port=3306;dbname=lukatrehout_gestionnaire_taches;charset=utf8";
    $db = new PDO($dsn,"root", "");
    return $db;
}