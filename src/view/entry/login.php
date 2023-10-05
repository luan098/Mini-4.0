<?php

use Mini\controller\HomeController;
use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Login</title>
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
                <p class="login-box-msg">Log in to start your session</p>
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
                        <input type="password" class="form-control" name="password" placeholder="Password *">
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
                                    Remember me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="<?= "$this->route/recover-account" ?>">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="<?= "$this->route/register" ?>" class="text-center">I want to be a member</a>
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
                        title: 'Oops, an error occurred, try again later.'
                    });
                }
            });


        });
    </script>
</body>

</html>