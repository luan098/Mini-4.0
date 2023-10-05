<?php

use Mini\controller\EntryController;
use Mini\controller\HomeController;
use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Change Password</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>

    <?php require_once APP . "view/_templates/js_header_script.php" ?>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="entrar" class="h1 notranslate"><b><?= APP_NAME ?></b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
                <form action="" method="post" id="change-password">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email code *" name="temp_password" autocomplete="off" value="<?= $_GET['code'] ?? '' ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope-open"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="New Password *" name="password" autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm Password *" name="confirm_password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" id="alterar">Change Password</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="<?= EntryController::ROUTE ?>">Login</a>
                </p>
            </div>
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>

    <script>
        $('#change-password').on('submit', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const formValues = $(e.currentTarget).serialize();

            $.ajax({
                url: 'entry/handleChangePassword',
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