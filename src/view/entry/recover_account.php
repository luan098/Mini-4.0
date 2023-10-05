<?php

use Mini\controller\EntryController;
use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Recover Account</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>

    <?php require_once APP . "view/_templates/js_header_script.php" ?>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= $this->route ?>" class="h1 notranslate"><b><?= APP_NAME ?></b></a>
                
            </div>
            <div class="card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve your account.</p>
                <form action="" id="recover-account" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email *" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" id="nova-password">Require a new password</button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="<?= $this->route ?>">Login</a>
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
                        title: 'Oops, an error occurred, try again later.'
                    });
                }
            });
        });
    </script>
</body>

</html>