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
    <title class="notranslate"><?= APP_NAME ?> - Recuperar Senha</title>
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
                <p class="login-box-msg">Você esqueceu sua senha? Aqui você consegue recuperar sua conta.</p>
                <form action="" id="recover-account" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="E-mail *" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" id="recover-account">Solicitar Uma Nova Senha</button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="<?= $this->route ?>">Entrar</a>
                </p>
            </div>
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>

    <script>
        $('#recover-account').on('submit', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const formValues = $(e.currentTarget).serialize();

            $.ajax({
                url: 'entry/handleRecoverAccount',
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
                        location.href = '<?= EntryController::ROUTE . "/change-password" ?>'
                    };
                },
                error: function() {
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