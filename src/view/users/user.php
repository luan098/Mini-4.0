<?php

use Mini\controller\HomeController;
use Mini\controller\UsersController;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 ellipsis">
                    <h1 class="d-inline-block"> Usuário </h1>
                    <?php if (isset($user) && $user) : ?>
                        <span class="ml-2 ellipsis"><?= "$user->id - $user->name" ?></span>
                    <?php endif ?>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= UsersController::ROUTE ?>">Usuário</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a href="<?= UsersController::ROUTE . "/" .  (($idUser ?? false) ? "edit/$idUser" : "add") ?>" class="nav-link <?= $_GET['pg2'] == "add" || $_GET['pg2'] == "edit" ? "active" : '' ?>" role="tab" aria-controls="data">Dados</a>
                                </li>
                                <?php if (($idUser ?? false)) : ?>
                                    <li class="nav-item">
                                        <a href="<?= UsersController::ROUTE . "/cover/$idUser" ?>" class="nav-link <?= $_GET['pg2'] == "cover" ? "active" : '' ?>" role="tab" aria-controls="cover">Capa</a>
                                    </li>
                                <?php endif ?>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <?php if ($_GET['pg2'] == 'add' || $_GET['pg2'] == 'edit') : ?>
                                    <?php require_once "data.php" ?>
                                <?php else : ?>
                                    <?php require_once "{$_GET['pg2']}.php" ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require APP . 'view/_templates/footer.php' ?>