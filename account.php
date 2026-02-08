<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

//Connexion à la base de données
function connectToDB() {
    $dsn = "mysql:host=db.3wa.io;port=3306;dbname=elietouboul_gestionaire;charset=utf8";
    $db = new PDO($dsn,"elietouboul", "35b0c636b367ef40b68ce5c495547588");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    return $db;
}

// Initialisation des variables par défaut (évite les erreurs undefined)
$user = [
    'name' => 'Utilisateur',
    'email' => '',
    'created_at' => date('Y-m-d')
];
$completed = 0;
$badgeIcon = '🥉';
$badgeClass = 'bronze';
$tasks = [];
$error = null;

try {
    $db = connectToDB();
    $userId = $_SESSION['user_id'];
    
    // Récupérer infos utilisateur
    $userStmt = $db->prepare("SELECT name, email, created_at FROM user WHERE id = ?");
    $userStmt->execute([$userId]);
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData) {
        $user = $userData;
    } else {
        session_destroy();
        header('Location: login.php');
        exit();
    }
    
    // Compter tâches accomplies pour le badge
    $badgeStmt = $db->prepare("SELECT COUNT(*) as completed FROM task WHERE user_id = ? AND is_done = 1");
    $badgeStmt->execute([$userId]);
    $result = $badgeStmt->fetch();
    $completed = $result ? $result['completed'] : 0;
    
    // Déterminer le badge
    if ($completed >= 20) {
        $badgeIcon = '🥇';
        $badgeClass = 'gold';
    } elseif ($completed >= 10) {
        $badgeIcon = '🥈';
        $badgeClass = 'silver';
    }
    
    // Récupérer tâches triées par matrice d'Eisenhower
    $tasksStmt = $db->prepare("
        SELECT *, 
            (is_urgent + is_important * 2) as priority_score
        FROM task 
        WHERE user_id = ? 
        ORDER BY 
            is_done ASC,
            priority_score DESC,
            created_at DESC
    ");
    $tasksStmt->execute([$userId]);
    $tasks = $tasksStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Traitement formulaire ajout de tâche
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $isUrgent = isset($_POST['is_urgent']) ? 1 : 0;
        $isImportant = isset($_POST['is_important']) ? 1 : 0;
        
        if (!empty($title)) {
            $insertStmt = $db->prepare("
                INSERT INTO task (user_id, title, content, is_urgent, is_important, is_done) 
                VALUES (?, ?, ?, ?, ?, 0)
            ");
            $insertStmt->execute([$userId, $title, $content, $isUrgent, $isImportant]);
            header('Location: user_account.php');
            exit();
        }
    }
    
    // Marquer tâche comme accomplie
    if (isset($_GET['complete'])) {
        $taskId = (int)$_GET['complete'];
        $checkStmt = $db->prepare("SELECT user_id FROM task WHERE id = ?");
        $checkStmt->execute([$taskId]);
        $taskOwner = $checkStmt->fetch();
        
        if ($taskOwner && $taskOwner['user_id'] == $userId) {
            $db->prepare("UPDATE task SET is_done = 1 WHERE id = ?")->execute([$taskId]);
        }
        header('Location: user_account.php');
        exit();
    }
    
    // Supprimer tâche
    if (isset($_GET['delete'])) {
        $taskId = (int)$_GET['delete'];
        $checkStmt = $db->prepare("SELECT user_id FROM task WHERE id = ?");
        $checkStmt->execute([$taskId]);
        $taskOwner = $checkStmt->fetch();
        
        if ($taskOwner && $taskOwner['user_id'] == $userId) {
            $db->prepare("DELETE FROM task WHERE id = ?")->execute([$taskId]);
        }
        header('Location: user_account.php');
        exit();
    }
    
} catch (PDOException $e) {
    $error = "Erreur base de données : " . $e->getMessage();
}

include('user_account.phtml');