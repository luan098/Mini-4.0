<?php

use Mini\controller\EntryController;
use Mini\core\FrontController;
use Mini\model\UserTypes;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate"><?= APP_NAME ?> - Register</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>
    <?php require_once APP . "view/_templates/js_header_script.php" ?>
</head>

<body class="hold-transition login-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="" class="h1 notranslate"><b><?= APP_NAME ?></b></a>
                
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new account, after registration wait until approval.</p>

                <form action="" method="post" id="register">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Full name *" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="cpf_cnpj" id="cpfcnpj" placeholder="CPF or CNPJ *" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="far fa-id-card"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="E-mail *" autocomplete="new-email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password *" autocomplete="new-password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password *" autocomplete="new-password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="date_birth" id="datemask" placeholder="Born Date *" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-calendar-alt"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-control" name="id_user_type">
                            <?php foreach (UserTypes::TYPES as $key => $name) : ?>
                                <option value="<?= $key ?>"><?= $name ?></option>
                            <?php endforeach ?>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-list-ul"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="1" required>
                                <label for="agreeTerms">
                                    I agree to the <a href="" data-toggle="modal" data-target="#modal-lg">terms</a> *
                                </label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                            <button type="submit" class="btn btn-primary btn-block" id="solicitar-acesso">Register</button>
                        </div>
                    </div>
                </form>
                <a href="entrar" class="text-center">I already have a membership</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?= $term->name ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $terms->description ?>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>

    <script>
        $("input[id*='cpfcnpj']").inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99'],
            keepStatic: true
        });

        $("input[id*='datemask']").inputmask({
            mask: ['99/99/9999'],
            keepStatic: true
        });


        $('#register').on('submit', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const formValues = $(e.currentTarget).serialize();

            $.ajax({
                url: 'entry/handleRegister',
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
                        location.href = '<?= EntryController::ROUTE ?>'
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