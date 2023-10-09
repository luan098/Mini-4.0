<?php

use Mini\controller\HomeController;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 ellipsis">
                    <h1 class="d-inline-block"> User Type </h1>
                    <?php if (isset($userType) && $userType) : ?>
                        <span class="ml-2 ellipsis"><?= "$userType->id - $userType->name" ?></span>
                    <?php endif ?>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $this->route ?>">User Type</a></li>
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
                                    <a href="<?= "$this->route/" .  ($idUserType ? "edit/$idUserType" : "add") ?>" class="nav-link <?= $_GET['pg2'] == "add" || $_GET['pg2'] == "edit" ? "active" : '' ?>" role="tab" aria-controls="data">Data</a>
                                </li>
                                <?php if (isset($idUserType) && $idUserType) : ?>
                                    <li class="nav-item">
                                        <a href="<?= "$this->route/access/$idUserType" ?>" class="nav-link <?= $_GET['pg2'] == "access" ? "active" : '' ?>" role="tab" aria-controls="access">Access</a>
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