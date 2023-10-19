<?php

use Mini\controller\EntryController;
use Mini\controller\HomeController;
use Mini\controller\ProfileController;
use Mini\model\UserTypes;
use Mini\core\FrontController;


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?></title>
    <link rel="shortcut icon" href="./images/config/favicon.png" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>
    <?php require_once APP . 'view/_templates/components/js-header-script.php' ?>
</head>

<body class="layout-fixed control-sidebar-slide-open">
    <div class="wrapper">

        <?php require_once 'components/navbar.php'; ?>

        <?php require_once 'components/sidebar.php'; ?>

        <div class="content-wrapper">