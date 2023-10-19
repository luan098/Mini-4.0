<?php

use Mini\controller\HomeController;
use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Entrar</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>
    <?php require_once APP . 'view/_templates/components/js-header-script.php' ?>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= $this->route ?>" class="h1"><b><?= APP_NAME ?></b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Entre para iniciar sua sessão</p>
                <form action="" method="post" id="form-entry">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="E-mail *">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Senha *">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" value="1">
                                <label for="remember">
                                    Lembra-me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="<?= "$this->route/recover-account" ?>">Esqueci minha senha</a>
                </p>
                <p class="mb-0">
                    <a href="<?= "$this->route/register" ?>" class="text-center">Quero ser um membro</a>
                </p>
            </div>
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>

    <script>
        $('#form-entry').on('submit', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const formValues = $(e.currentTarget).serialize();

            $.ajax({
                url: 'entry/handleLogin',
                dataType: 'json',
                type: "post",
                data: formValues,
                cache: false,
                success: function(response) {
                    if (response.error) {
                        Toast.fire({
                            icon: response.error ? 'error' : 'success',
                            title: response.message
                        });
                    } else {
                        location.href = '<?= HomeController::ROUTE ?>'
                    };
                },
                error: function(e) {
                    debugger;
                    Toast.fire({
                        icon: 'question',
                        title: 'Oops, ocorreu um erro, tente mais tarde.'
                    });
                }
            });
        });
    </script>
</body>

</html>