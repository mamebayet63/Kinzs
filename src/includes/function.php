<?php

function notification($icon, $type, $message)
{
    echo "
    <div class='col-5 p-3 d-flex gap-3 align-items-center shadow-sm bg-white z-3 position-absolute top-0 start-50 translate-middle-x notif'>
        <i class='$icon text-$type fs-4'></i>
        <p class='m-0'>$message</p>
        <div class='position-absolute top-0 end-0 p-1'>
            <i class='ri-close-circle-fill text-$type fs-4 close'></i>
        </div>
    </div>
    ";
}

function authentificationAdmin($email, $password)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE identifiant = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            return ['authenticated' => true, 'admin' => $user];
        } else {
            return ['authenticated' => false, 'admin' => $user];
        }
    } else {
        return ['authenticated' => false, 'admin' => null];
    }
}

function getStatus($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM status");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchParamsToGetResidences($pdo, $filters)
{
    $searchTerm = $filters['search'] ?? '';

    $query = "SELECT * FROM residence WHERE 1";
    $params = [];

    if (!empty($searchTerm)) {
        $query .= " AND nom LIKE :searchTerm";
        $params[':searchTerm'] = '%' . $searchTerm . '%';
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchParamsToGetReservations($pdo, $filters)
{
    $searchTerm = $filters['search'] ?? '';

    $query = "SELECT * FROM reservation WHERE 1";
    $params = [];

    if (!empty($searchTerm)) {
        $query .= " AND email LIKE :searchTerm";
        $params[':searchTerm'] = '%' . $searchTerm . '%';
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getResidenceWithId($pdo, $residenceId)
{
    $stmt = $pdo->prepare("SELECT * FROM residence WHERE ID_Residence = :id");
    $stmt->execute([':id' => $residenceId]);
    return $stmt->fetch();
}

function getResidence($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM residence");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReservation($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM reservation");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getChambres($pdo, $residence_id = null)
{
    if ($residence_id) {
        $stmt = $pdo->prepare("SELECT * FROM chambre WHERE ID_Residence = :residence_id");
        $stmt->execute([':residence_id' => $residence_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM chambre");
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getChambreWithId($pdo, $chambreId)
{
    $stmt = $pdo->prepare("SELECT * FROM chambre WHERE ID_Chambre = :id");
    $stmt->execute([':id' => $chambreId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getThreeFirstchambres($pdo)
{
    $stmt = $pdo->query("SELECT * FROM chambre ORDER BY ID_Chambre LIMIT 6");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNombreReservations($pdo)
{
    $query = $pdo->prepare("SELECT COUNT(*) as total FROM reservation");
    $query->execute();
    $result = $query->fetch();
    return $result['total'];
}

function getChambresByResidence($pdo, $residenceId, $excludeChambreId) {
    $stmt = $pdo->prepare("SELECT * FROM chambre WHERE ID_Residence = :residenceId AND ID_Chambre != :excludeChambreId");
    $stmt->execute(['residenceId' => $residenceId, 'excludeChambreId' => $excludeChambreId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
