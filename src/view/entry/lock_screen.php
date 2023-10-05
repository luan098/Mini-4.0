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
    <title class="notranslate"><?= APP_NAME ?> - Lock Screen</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>

    <?php require_once APP . "view/_templates/js_header_script.php" ?>
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
            <form class="lockscreen-credentials" method="_POST" id="lock-screen">
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" autofocus>
                    <div class="input-group-append">
                        <button type="submit" class="btn" id="btn-entrar">
                            <i class="fas fa-arrow-right text-muted"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="help-block text-center">
            Enter your password to retrieve your session
        </div>
        <div class="text-center">
            <a href="<?= "$this->route/handleLogout" ?>">Or sign in as a different user</a>
        </div>
        <div class="lockscreen-footer text-center">
            <strong>Copyright &copy; 2023-<?= date('Y'); ?><br><a href="https://www.ydealtecnologia.com.br/" target="_blank">Ydeal Tecnologia</a></strong>
            <br>All rights reserved.
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>

    <script>
        $('#lock-screen').on('submit', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const formValues = $(e.currentTarget).serialize();

            $.ajax({
                url: 'entry/handleLoginLockScreen',
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