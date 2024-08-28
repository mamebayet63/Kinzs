<?php
session_start();
require_once "../../includes/database.php";
require_once "../../includes/function.php";

$success = $_SESSION['success'] ?? [];
$error = $_SESSION["errors"] ?? [];
unset($_SESSION["errors"], $_SESSION["success"]);

$chambreId = $_GET["id"] ?? null;
$chambre = getChambreWithId($pdo, $chambreId);
$residences = searchParamsToGetResidences($pdo, $_GET);


?>
<!doctype html>
<html lang="en">

<head>
    <title>Details chambre - Kinz</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="../../../public/css/home.css">
    <style>
        @media (min-width: 992px) {
            .menu {
                margin-left: 150px;
            }
        }

        @media (max-width: 768px) {
            .menu {
                margin-left: 0px;
            }
        }

        @media (max-width: 576px) {
            .menu {
                margin-left: 0px;
            }
        }
    </style>
    <link
        href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    "
        rel="stylesheet" />
    <link href="
https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.min.css
" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
</head>

<body>
    <header style="position: fixed;top:0;z-index:1000;background-color:white;width:100%">
        <nav class="navbar navbar-expand-lg bg-body-white shadow">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="../../../public/images/logo.svg" alt="" style="height: 70px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 menu p-1 d-flex gap-3">
                        <li class="nav-item sidebar-link">
                            <a class="nav-link" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="./chambre.php">Nos Chambres</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="./propos.php">Qui sommes-nous ?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="./contact.php">Contact</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn bg-success d-flex align-items-center gap-4">
                            <span class="text-white fs-5">Telephone</span>
                            <i class="ri-phone-fill fes-5 text-white"></i>
                        </button>
                        <div style="width: 48px;height:48px" class="bg-success rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-whatsapp-line text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main style="margin-top: 120px;">
        <div class="container">
            <div class="row">
                <div class="col-12" style="height: 60vh;">
                    <div class="rounded position-relative" style="background-image: url(../../uploads/imgChambre/<?= $chambre["image"] ?>);background-size:cover;background-position:center;background-repeat:no-repeat;height:100%">
                        <p class="p-1 fs-5 rounded bg-warning text-white position-absolute top-0 end-0"><?= $chambre["nom"] ?></p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 col-sm-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold fs-5">Caracteristique : </span>
                        <div class="d-flex gap-3">
                            <?php if ($chambre["television"] == "oui"): ?>
                                <div class="d-flex flex-column fs-5 justify-content-center align-items-center bg-light rounded gap-3 p-3">
                                    <i class="ri-tv-line"></i>
                                    <span>Television</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($chambre["comodite"] == "ventiller"): ?>
                                <div class="d-flex flex-column fs-5 justify-content-center align-items-center bg-light rounded gap-3 p-3">
                                    <i class="ri-water-flash-line"></i>
                                    <span>Ventilateur</span>
                                </div>
                            <?php else: ?>
                                <div class="d-flex flex-column fs-5 justify-content-center align-items-center bg-light rounded gap-3 p-3">
                                    <i class=" ri-fridge-line"></i>
                                    <span>Climatiser</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4">
                            <button <?php if ($chambre["status"] == 2) {
                                        echo "disabled";
                                    } ?> class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $chambre["ID_Chambre"] ?>">Reserver</button>
                        </div>
                        <div class="modal fade" id="staticBackdrop<?= $chambre["ID_Chambre"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?= $chambre["ID_Chambre"] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="text-center">Entrez les dates pour la reservations</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="">
                                            <form action="../../controllers/users/dateReservationController.php" method="post">
                                                <input type="hidden" name="id" value="<?= $chambre["ID_Chambre"] ?>" />
                                                <div class="form-group col-12">
                                                    <label for="debut" class="fw-bold border-bottom">Date debut</label>
                                                    <input type="date" name="debut" class="form-control p-3 shadow-sm mt-3">
                                                    <?php if (!empty($error['debut'])) : ?>
                                                        <li class="text-danger"><?= $error['debut'] ?></li>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-group mt-4 col-12">
                                                    <label for="fin" class="fw-bold border-bottom">Date fin</label>
                                                    <input type="date" name="fin" class="form-control p-3 shadow-sm mt-3">
                                                    <?php if (!empty($error['fin'])) : ?>
                                                        <li class="text-danger"><?= $error['fin'] ?></li>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn bg-primary d-flex align-items-center gap-3 text-white mt-3">
                                                        <span>Continuer</span>
                                                        <i class="ri-arrow-right-circle-line"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div>
                        <span class="fw-bold fs-5">Description : </span>
                        <p><?= $chambre["description"] ?></p>
                    </div>
                    <div>
                        <span class="fw-bold fs-5">Prix: </span>
                        <span><?= $chambre["prix"] ?> FCFA</span>
                    </div>
                    <div>
                        <span class="fw-bold fs-5">Residence: </span>
                        <?php foreach ($residences as $residence): ?>
                            <?php if ($residence["ID_Residence"] == $chambre["ID_Residence"]): ?>
                                <span><?= $residence["nom"] ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php if (!empty($errors)) : ?>
        <?= notification("ri-alert-fill", "danger", $error); ?>
    <?php endif; ?>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="../../../public/javascript/chambre.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>