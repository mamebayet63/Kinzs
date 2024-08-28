<?php
session_start();

require_once '../../includes/database.php';
require_once '../../includes/function.php';

// Tableau pour stocker les erreurs de formulaire
$errors = [
    'nom' => '',
    'prenom' => '',
    'email' => '',
    'numero' => '',
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $date_debut = $_POST['debut'];
    $date_fin = $_POST['fin'];
    $id_chambre = $_POST["id_chambre"];
    $id_residence = $_POST["id_residence"];

    // Validation des champs
    if (empty($nom)) {
        $errors['nom'] = "Le nom utilisateur est requise.";
    }
    if (empty($prenom)) {
        $errors['prenom'] = "Le prenom utilisateur est requise.";
    }
    if (empty($numero)) {
        $errors['numero'] = "Le numero utilisateur est requise.";
    }
    if (empty($email)) {
        $errors['email'] = "L'email utilisateur est requise.";
    }

    if (empty(array_filter($errors))) {
        $query = "INSERT INTO reservation (nom, prenom, email, numero, date_debut, date_fin,ID_Chambre,ID_Residence) VALUES (:nom, :prenom,:email, :numero, :date_debut, :date_fin,:chambre,:residence)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':numero' => $numero,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
            ':email' => $email,
            ':chambre' => $id_chambre,
            ':residence' => $id_residence,
        ]);
        $updateQuery = "UPDATE chambre SET status = 2 WHERE ID_Chambre = :id_chambre";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([':id_chambre' => $id_chambre]);
        $_SESSION["success"] = "Reservation éffectuer avec success";
        header('Location: ../../views/users/chambre.php');
        exit();
    }

    $_SESSION['errors'] = $errors;
    header("Location: ../../views/users/reservation.php?debut=$date_debut&fin=$date_fin&id=$id_chambre");
    exit();
}
