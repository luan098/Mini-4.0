<?php

use Mini\controller\EntryController;
use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Tela de Bloqueio</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>

    <?php require_once APP . 'view/_templates/components/js-header-script.php' ?>
</head>

<body class="hold-transition lockscreen">

    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="" class="notranslate"><b><?= APP_NAME ?></b></a>
        </div>
        <div class="lockscreen-name">
            <?= $user->name ?>
        </div>
        <div class="lockscreen-item">
            <div class="lockscreen-image">
                <img src="<?= $user->image ?>" alt="User profile image">
            </div>
            <form action="<?= EntryController::ROUTE . "/handleLoginLockScreen" ?>" class="lockscreen-credentials" method="post">
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Senha" name="password" autofocus>
                    <div class="input-group-append">
                        <button type="submit" class="btn" id="btn-entrar">
                            <i class="fas fa-arrow-right text-muted"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="help-block text-center">
            Digite sua senha para recuperar sua seção
        </div>
        <div class="text-center">
            <a href="<?= "$this->route/handleLogout" ?>">Ou entre com um usuário diferente</a>
        </div>
        <div class="lockscreen-footer text-center">
            <strong>Copyright &copy; 2023-<?= date('Y'); ?><br><a href="https://www.ydealtecnologia.com.br/" target="_blank">Ydeal Tecnologia</a></strong>
            <br>All rights reserved.
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>
    <?php require_once APP . 'view/_templates/components/toast-fire.php' ?>
</body>

</html>