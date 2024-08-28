<?php
session_start();
require_once "../../../includes/database.php";
require_once "../../../includes/function.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../auth/connexion.php');
    exit();
}

$success = $_SESSION['success'] ?? [];
$error = $_SESSION["error"] ?? [];
unset($_SESSION['success'], $_SESSION['error']);

$residence_id = $_GET["residence"] ?? null;
$residences = searchParamsToGetResidences($pdo, $_GET);
$chambres = getChambres($pdo, $residence_id);
$nbReservation = getNombreReservations($pdo);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Nos Chambres</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="../../../../public/css/admin.css">
    <link href="
    https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.min.css
    " rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body class="">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-2 sidebar d-none d-lg-flex flex-column justify-content-between col-lg-2 bg-white p-0" style="height: 100vh; position: fixed; left: 0; bottom: 0">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <img src="../../../../public/images/logo.svg" class="img-fluid d-none d-lg-block" style="height: 110px" alt="logo" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <ul class="d-flex flex-column list-unstyled">
                                <li>
                                    <a href="../../../../admin.php" class="sidebar-link text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-home-wifi-line fs-5"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="m-0">
                                    <a href="./chambre.php" class="sidebar-link text-decoration-none active text-black d-none d-lg-flex">
                                        <i class="ri-book-open-line fs-5"></i>
                                        <span>Mes Chambres</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./reservation.php" class="sidebar-link position-relative text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-reserved-line fs-5"></i>
                                        <span>Reservations</span>
                                        <?php if ($nbReservation > 0) : ?>
                                            <span class="badge bg-danger"><?= $nbReservation ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./residence.php" class="sidebar-link text-decoration-none text-black d-none d-lg-flex">
                                        <i class="ri-building-line fs-5"></i>
                                        <span>Residences</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="list-unstyled">
                            <li>
                                <a href="../../../controllers/admin/auth/deconnexionController.php" class="sidebar-link text-decoration-none text-black d-none d-lg-block">
                                    <i class="ri-logout-box-line fs-4"></i>
                                    <span>Deconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-10 contente position-relative" style="height: 100vh">
                <div class="container d-flex flex-column justify-content-between" style="min-height: 100vh">
                    <div>
                        <div class="row">
                            <div class="col-12 bg-white shadow-sm d-flex align-items-center justify-content-between" style="height: 12vh">
                                <div class="">
                                    <div class="fs-4 d-flex justify-content-center align-items-center rounded-circle menu" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">
                                        <i class="ri-menu-2-fill"></i>
                                    </div>
                                </div>
                                <div class="d-none d-sm-block">
                                    <p class="m-0 fw-bold">Listes des Chambres</p>
                                </div>
                                <div class="d-none d-sm-block">
                                    <p class="m-0 fw-bold">Administrateur</p>
                                </div>
                                <div class="d-block d-sm-none mt-4">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a href="../../../controllers/admin/auth/deconnexionController.php" class="sidebar-link text-decoration-none text-black">
                                                <i class="ri-logout-box-line fs-4"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-12 bg-white shadow-sm d-flex flex-column justify-content-between" style="min-height: 88vh;">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <form method="get" action="#" class="mb-4 d-flex gap-3">
                                            <select name="residence" class="form-control">
                                                <option value="">Toutes les catégories</option>
                                                <?php foreach ($residences as $residence) : ?>
                                                    <option value="<?= $residence['ID_Residence'] ?>">
                                                        <?= htmlspecialchars($residence['nom']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="">
                                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12 col-sm-4 col-lg-3 mt-3 mt-sm-0">
                                        <a href="../forms/formAdd/addChambre.php" class="btn btn-info d-flex align-items-center gap-2 text-white">
                                            <span>Nouveaux Chambres</span>
                                            <i class="ri-apps-2-add-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php foreach ($chambres as $k => $chambre): ?>
                                        <div class="col-12 col-sm-6 col-lg-4 shadow-sm position-relative p-2 mt-5">
                                            <div class="rounded" style="background-image: url(../../../uploads/imgChambre/<?= $chambre["image"] ?>);background-size:cover;background-position:center;background-repeat:no-repeat;height:25vh"></div>
                                            <p class="m-0 text-center py-3"><?= $chambre["nom"] ?></p>
                                            <span class="fw-bold">Caracteristique</span>
                                            <div class="d-flex gap-2">
                                                <?php if ($chambre["television"] == "oui"): ?>
                                                    <div style="width: 45px;height:45px;" class="d-flex justify-content-center align-items-center bg-light rounded-circle">
                                                        <i class="ri-tv-line"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($chambre["comodite"] == "ventiller"): ?>
                                                    <div style="width: 45px;height:45px;" class="d-flex justify-content-center align-items-center bg-light rounded-circle">
                                                        <i class="ri-water-flash-line"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div style="width: 45px;height:45px;" class="d-flex justify-content-center align-items-center bg-light rounded-circle">
                                                        <i class="ri-fridge-line"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Prix:</span>
                                                <span><?= $chambre["prix"] ?>FCFA</span>
                                            </div>
                                            <p class="position-absolute top-0 end-0 text-white p-1 bg-<?php if ($chambre["status"] == 1) {
                                                                                                            echo "success";
                                                                                                        } else {
                                                                                                            echo "danger";
                                                                                                        } ?>">
                                                <?php if ($chambre["status"] == 1): ?>
                                                    Disponible
                                                <?php else: ?>
                                                    Rerserver
                                                <?php endif; ?>
                                            </p>
                                            <div>
                                                <span class="fw-bold">Dimension:</span>
                                                <span><?= $chambre["dimension"] ?>(m)</span>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Residence :</span>
                                                <p>
                                                    <?php foreach ($residences as $k => $residence): ?>
                                                        <?php if ($residence["ID_Residence"] == $chambre["ID_Residence"]): ?>
                                                            <?= $residence["nom"] ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </p>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <a href="../forms/formUpdate/editChambre.php?id=<?= $chambre["ID_Chambre"] ?>" class="btn bg-info text-white">Modifier</a>
                                                <a data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $k ?>" class="btn bg-danger text-white">Supprimer</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="staticBackdrop<?= $k ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?= $k ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3 class="text-center">Confirmer la supression</h3>
                                                        <div class="d-flex gap-3 justify-content-center">
                                                            <a href="../../../controllers/admin/chambre/deleteChambreController.php?id=<?= $chambre["ID_Chambre"] ?>"><button class="btn bg-danger text-white">Supprimer</button></a>
                                                            <button class="btn bg-primary text-white" data-bs-dismiss="modal">Annuler</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="row border-top mt-5">
                                    <div class="col-12 py-2">
                                        <p class="m-0 text-center"> Tous droits réservés. | © 2024 Kinz.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($success)) : ?>
                <?= notification("ri-checkbox-circle-fill", "success", $success); ?>
            <?php endif; ?>
        </div>
    </div>
    </div>
    </div>
    <div class="offcanvas offcanvas-start d-block d-lg-none p-0" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <div class="d-flex flex-column justify-content-between">
                <div class="row">
                    <div class="col-12 px-4 py-3">
                        <img src="../../../../public/images/logo.svg" class="img-fluid d-block d-lg-none" style="height: 60px" alt="" />
                    </div>
                </div>
                <div class="row" style="margin-top: 200px;">
                    <div class="col-12">
                        <nav>
                            <ul class="d-flex flex-column gap-2 list-unstyled">
                                <li>
                                    <a href="../../../../admin.php" class="sidebar-link text-decoration-none text-black ">
                                        <i class="ri-home-wifi-line fs-4"></i>
                                        <span>Accueil</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./chambre.php" class="sidebar-link active text-decoration-none text-black">
                                        <i class="ri-book-open-line fs-4"></i>
                                        <span>Mes Chambres</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./reservation.php" class="sidebar-link text-decoration-none text-black">
                                        <i class="ri-reserved-line fs-4"></i>
                                        <span>Reservations</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="./residence.php" class="sidebar-link text-decoration-none text-black ">
                                        <i class="ri-building-line fs-4"></i>
                                        <span>Residences</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row" style="margin-top: 270px;">
                    <div class="col-12">
                        <ul class="list-unstyled">
                            <li>
                                <a href="../../../controllers/admin/auth/deconnexionController.php" class="sidebar-link text-decoration-none text-black">
                                    <i class="ri-logout-box-line fs-4"></i>
                                    <span>Deconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="../../../../public/javascript/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>