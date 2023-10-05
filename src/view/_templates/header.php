<?php

use Mini\controller\EntryController;
use Mini\controller\HomeController;
use Mini\controller\ProfileController;
use Mini\model\UserTypes;
use Mini\core\FrontController;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?></title>
    <link rel="shortcut icon" href="./images/config/favicon.png" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>
    <?php require_once  APP . "view/_templates/js_header_script.php" ?>
</head>

<body class="layout-top-nav sidebar-collapse">

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light bg-app-primary">
            <div class="container">
                <a href="<?= HomeController::ROUTE ?>" class="navbar-brand">
                    <img src="./images/config/logo.png" alt="FastImport logo" class="brand-image">
                    <span class="brand-text font-weight-light notranslate" style="color: rgba(0,0,0,.5);"><?= APP_NAME ?></span>
                </a>
                <?php if ($_SESSION['user']->id_user_type != UserTypes::CUSTOMER and $_SESSION['user']->id_user_type != UserTypes::MEDIATOR_TRANSPORTER) : ?>
                    <button class="navbar-toggler orders-1" type="button" data-widget="pushmenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse orders-2" id="navbarCollapse">
                        <ul class="navbar-nav">
                            <li class="nav-item mt-2">
                                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif ?>

                <ul class="orders-1 orders-md-4 navbar-nav navbar-no-expand ml-auto mr-auto mr-sm-0">

                    <li class="nav-item dropdown user user-menu">
                        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
                            <img src="<?= $_SESSION['user']->url_cover ?>" class="user-image" style="object-fit: contain;padding: 3px;margin-top: 1px;">
                            <span class="d-none d-lg-inline-block mr-1 ellipsis" style="max-width: 116px;"><?= $_SESSION['user']->name ?></span>
                            <i class="fas fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="<?= $_SESSION['user']->url_cover ?>" class="img-circle" alt="<?= $_SESSION['user']->name ?>">
                                <p>
                                    <?= $_SESSION['user']->name ?>
                                    <small><?= UserTypes::TYPES[$_SESSION['user']->id_user_type] ?></small>
                                </p>
                            </li>
                            <li>
                                <div class="col-12">
                                    <?php if ($_SESSION['user']->id_user_type == UserTypes::CUSTOMER) : ?>
                                        <a class="btn btn-sm btn-default" href="<?= ProfileController::ROUTE . "/subscriptions" ?>">Subscriptions</a>
                                        <a class="btn btn-sm btn-default" href="<?= ProfileController::ROUTE . "/orders" ?>">Orders</a>
                                    <?php endif ?>
                                    <?php if ($_SESSION['user']->id_user_type == UserTypes::SELLER) : ?>
                                        <a class="btn btn-sm btn-default" href="<?= ProfileController::ROUTE . "/products" ?>">Products</a>
                                    <?php endif ?>
                                </div>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li class="user-footer">
                                <div class="float-left">
                                    <a href="<?= ProfileController::ROUTE ?>" class="btn btn-default">Profile</a>
                                </div>
                                <div class="float-right">
                                    <a href="<?= EntryController::ROUTE . "/handleLogout" ?>" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Exit</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <?php require_once APP . 'view/_templates/menu.php'; ?>

        <div class="content-wrapper">