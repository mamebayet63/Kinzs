<?php
session_start();

$id_chambre = $_POST["id"] ?? null;
$currentDate = date('Y-m-d');

// Tableau pour stocker les erreurs de formulaire
$errors = [
    'debut' => '',
    'fin' => '',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $debut = trim($_POST['debut']);
    $fin = trim($_POST['fin']);

    // Vérification des champs vides
    if (empty($debut)) {
        $errors["debut"] = "Une date de début est requise.";
    }
    if (empty($fin)) {
        $errors["fin"] = "Une date de fin est requise.";
    }

    // Vérifications supplémentaires si les dates ne sont pas vides
    if (!empty($debut) && !empty($fin)) {
        if (strtotime($fin) < strtotime($debut)) {
            $errors["fin_debut"] = "La date de fin ne peut pas être antérieure à la date de début.";
        }
        if (strtotime($debut) < strtotime($currentDate)) {
            $errors["debut_passee"] = "La date de début ne peut pas être antérieure à la date actuelle.";
        }
        if (strtotime($fin) < strtotime($currentDate)) {
            $errors["fin_passee"] = "La date de fin ne peut pas être antérieure à la date actuelle.";
        }
    }

    // Redirection selon les erreurs
    if (empty(array_filter($errors))) {
        $_SESSION["success"] = "Les dates ont été enregistrées avec succès.";
        header("Location: ../../views/users/reservation.php?debut=$debut&fin=$fin&id=$id_chambre");
        exit(); // Assurez-vous de quitter le script après la redirection
    } else {
        $_SESSION["errors"] = $errors;
        header("Location: ../../views/users/detailsChambre.php?id=$id_chambre");

        exit(); // Assurez-vous de quitter le script après la redirection
    }
}
