<?php
 
include ('connexion.php');
 
if (!empty($_POST)){
    $db = connectToDB();

    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $errors = array();
    $uppercase = preg_match("/[A-Z]/", $password);
    $lowercase = preg_match("/[a-z]/", $password);
    $number = preg_match("/[0-9]/", $password);
    if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
        $errors["password"] = "Le mot de passe est invalide, il faut au minimum 8 caractères, la présence d'une lettre en majuscule, minuscule et un chiffre dans votre mot de passe.";
    } else {
        $secure_password = password_hash($password, PASSWORD_DEFAULT);
        $requete = $db->prepare("INSERT INTO users (name,password,email) VALUES (:name, :password, :email)");
        $requete->bindParam(':name', $name, PDO::PARAM_STR);
        $requete->bindParam(':password', $secure_password, PDO::PARAM_STR);
        $requete->bindParam(':email', $email, PDO::PARAM_STR);
      $requete->execute();
    }
}
 
<<<<<<< HEAD
include('register.phtml');
=======
include('register.phtml');
>>>>>>> a124a42acd2799f83c39f9906b34937535146a09
