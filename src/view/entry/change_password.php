<?php

use Mini\controller\EntryController;
use Mini\controller\HomeController;
use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Alterar Senha</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>

    <?php require_once APP . 'view/_templates/components/js-header-script.php' ?>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="entrar" class="h1"><b><?= APP_NAME ?></b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Você está a um passo de obter uma nova senha, recupere sua senha.</p>
                <form action="<?= EntryController::ROUTE . "handleChangePassword" ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Código do E-mail *" name="temp_password" autocomplete="off" value="<?= $_GET['code'] ?? '' ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope-open"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Senha nova *" name="password" autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirme a senha nova *" name="confirm_password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" id="alterar">Alterar Senha</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="<?= EntryController::ROUTE ?>">Entrar</a>
                </p>
            </div>
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>
    <?php require_once APP . 'view/_templates/components/toast-fire.php' ?>
</body>

</html>