<?php

use Mini\controller\EntryController;
use Mini\controller\ProfileController;
use Mini\model\UserTypes;

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Home</a>
        </li>
    </ul>

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
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>