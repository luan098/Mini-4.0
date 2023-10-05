<?php

use Mini\controller\HomeController;
use Mini\model\UserTypes;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
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
                                <img class="profile-user-img img-fluid img-circle" src="<?= $_SESSION['user']->url_cover ? $_SESSION['user']->url_cover : "./images/user-no-cover.jpg" ?>" alt="Cover user profile image.">
                            </div>
                            <h3 class="profile-username text-center"><?= $_SESSION['user']->name ?></h3>
                            <p class="text-muted text-center"><?= UserTypes::TYPES[$_SESSION['user']->id_user_type] ?></p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Pending Orders</b> <a class="float-right"><?= $totalOrders->pending ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Delivered Orders</b> <a class="float-right"><?= $totalOrders->delivered ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Canceled Orders</b> <a class="float-right"><?= $totalOrders->canceled ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link <?= ($_GET['pg2'] ?? false) == 'orders' ? 'active' : '' ?>" href="<?= "$this->route/orders" ?>">Orders</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <?php if (!($_GET['pg2'] ?? false)) : ?>
                                        <?php require_once 'orders.php'; ?>
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