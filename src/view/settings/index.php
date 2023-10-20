<?php

use Mini\controller\HomeController;
use Mini\controller\SettingsController;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Configurações </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= SettingsController::ROUTE ?>">Configurações</a></li>
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
                                    <a href="<?= SettingsController::ROUTE ?>" class="nav-link <?= !($_GET['pg2'] ?? false) ? "active" : '' ?>" role="tab" aria-controls="general">Geral</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= SettingsController::ROUTE . "/email" ?>" class="nav-link <?= ($_GET['pg2'] ?? false) == 'email' ? "active" : '' ?>" role="tab" aria-controls="email">E-mail</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <?php if (!isset($_GET['pg2']) || !$_GET['pg2']) : ?>
                                    <?php require_once "general.php" ?>
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