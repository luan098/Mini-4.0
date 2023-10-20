<?php

use Mini\controller\HomeController;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Perfil</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item active">Perfil</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="<?= $_SESSION['user']->url_cover ? $_SESSION['user']->url_cover : "./images/user-no-cover.jpg" ?>" alt="Imagem de perfil do usuário.">
                            </div>
                            <h3 class="profile-username text-center"><?= $_SESSION['user']->name ?></h3>
                            <p class="text-muted text-center"><?= $_SESSION['user_type']->name ?></p>
                            <ul class="list-group list-group-unbordered mb-3">
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link <?= !($_GET['pg2'] ?? false) || ($_GET['pg2'] ?? false) == 'edit' ? 'active' : '' ?>" href="<?= "$this->route/edit" ?>">Perfil</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <?php if (!($_GET['pg2'] ?? false)) : ?>
                                        <?php require_once 'edit.php'; ?>
                                    <?php else : ?>
                                        <?php require_once "{$_GET['pg2']}.php"; ?>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require APP . 'view/_templates/footer.php' ?>