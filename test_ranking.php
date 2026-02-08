<?php
echo "PHP fonctionne !<br>";
echo "Connexion à la base de données...<br>";

include('connexion.php');

try {
    $db = connectToDB();
    echo "✅ Connexion réussie !<br>";
    
    $stmt = $db->prepare("SELECT name FROM users ORDER BY id ASC LIMIT 10");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Nombre d'utilisateurs trouvés : " . count($users) . "<br><br>";
    
    if (!empty($users)) {
        echo "<h3>Liste des utilisateurs :</h3>";
        foreach ($users as $index => $user) {
            echo ($index + 1) . ". " . htmlspecialchars($user['name']) . "<br>";
        }
    } else {
        echo "❌ Aucun utilisateur trouvé dans la base de données.";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
