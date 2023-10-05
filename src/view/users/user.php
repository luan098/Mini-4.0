<?php

use Mini\controller\HomeController;
use Mini\controller\UsersController;
use Mini\model\UserTypes;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 ellipsis">
                    <h1 class="d-inline-block"> User </h1>
                    <?php if (isset($user) && $user) : ?>
                        <span class="ml-2 ellipsis"><?= "$user->id - $user->name" ?></span>
                    <?php endif ?>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= UsersController::ROUTE ?>">Users</a></li>
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
                                    <a href="<?= UsersController::ROUTE . "/" .  ($idUser ? "edit/$idUser" : "add") ?>" class="nav-link <?= $_GET['pg2'] == "add" || $_GET['pg2'] == "edit" ? "active" : '' ?>" role="tab" aria-controls="data">Data</a>
                                </li>
                                <?php if ($idUser) : ?>
                                    <li class="nav-item">
                                        <a href="<?= UsersController::ROUTE . "/cover/$idUser" ?>" class="nav-link <?= $_GET['pg2'] == "cover" ? "active" : '' ?>" role="tab" aria-controls="cover">Cover</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= UsersController::ROUTE . "/subscriptions/$idUser" ?>" class="nav-link <?= $_GET['pg2'] == "subscriptions" ? "active" : '' ?>" role="tab" aria-controls="subscriptions">Subscriptions</a>
                                    </li>
                                    <?php if (in_array($_SESSION['user']->id_user_type, [UserTypes::ADMINISTRATOR, UserTypes::SUPER_ADMINISTRATOR])) : ?>
                                        <li class="nav-item">
                                            <a href="<?= UsersController::ROUTE . "/resume/$idUser" ?>" class="nav-link <?= $_GET['pg2'] == "resume" ? "active" : '' ?>" role="tab" aria-controls="resume">Resume</a>
                                        </li>
                                    <?php endif ?>
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