<?php
session_start();

include ('connexion.php');

$db = connectToDB();

if (!empty($_POST)){
    if (isset($_POST['name'], $_POST['password'])){

        $name = trim(strip_tags($_POST['name']));
        $password = trim(strip_tags($_POST['password']));

        $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->execute([$name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user']['name'] = $user['name'];
            
            header("Location: account.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}  
include ('login.phtml');

