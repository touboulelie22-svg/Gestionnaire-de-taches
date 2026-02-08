<?php
 
//Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=db.3wa.io;port=3306;dbname=elietouboul_gestionaire;charset=utf8";
    $db = new PDO($dsn,"elietouboul", "35b0c636b367ef40b68ce5c495547588");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    return $db;
}