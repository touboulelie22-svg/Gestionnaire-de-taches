<?php
session_start();

include('connexion.php');

$error = null;

if (!empty($_POST)) {
    if (isset($_POST['name'], $_POST['password'])) {
        try {
            $db = connectToDB();
            
            $name = trim(strip_tags($_POST['name']));
            $password = trim(strip_tags($_POST['password']));

            // Utiliser la table 'user' (sans 's')
            $stmt = $db->prepare("SELECT * FROM user WHERE name = ?");
            $stmt->execute([$name]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Stocker l'ID utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                header("Location: account.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

include('login.phtml');
