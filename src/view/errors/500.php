<?php

use Mini\core\FrontController;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <base href="<?= URL ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="notranslate">Fast Import - Error</title>
    <link rel="shortcut icon" href="<?= './images/config/favicon.png' ?>" type="image/x-icon" />
    <?= $this->renderStyle() ?>
    <?= $this->renderScript(FrontController::RENDER_CONFIG_HEADER_SCRIPT) ?>
    <?php require_once APP . "view/_templates/js_header_script.php" ?>


    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20vh;
        }

        .error-container {
            max-width: 800px;
            margin: auto;
            margin-top: 50px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
        }

        .error-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .error-header h1 {
            font-size: 72px;
            color: #dc3545;
        }

        .error-message {
            margin-top: 30px;
            font-size: 18px;
            color: #6c757d;
        }

        .stack-trace {
            margin-top: 30px;
            white-space: pre-wrap;
            font-family: "Courier New", Courier, monospace;
        }

        .home-button {
            margin-top: 20px;
            text-align: center;
        }

        .home-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <section class="content">
            <div class="error-container">
                <div class="error-header">
                    <h1>500</h1>
                    <h2>Oops! Ocorreu algo errado.</h2>
                </div>

                <div class="error-message">
                    <p>
                        Lamentamos, mas algo deu errado. Fomos notificados sobre este problema e trabalharemos para resolvê-lo o mais rápido possível.
                    </p>
                </div>

                <?php if ($_SESSION['user_type']->is_admin ?? false) : ?>
                    <div class="error-message">
                        <p>
                            <?= htmlspecialchars($error->getMessage()) ?>
                        </p>
                    </div>
                    <div class="stack-trace">
                        <p>Stack trace:</p>
                        <pre>
                            <?= htmlspecialchars($error->getTraceAsString()) ?>
                        </pre>
                    </div>
                <?php endif ?>

                <div class="home-button">
                    <a href="#">Retornar Para a Home</a>
                </div>
            </div>
        </section>
    </div>
  </body>

</html>