<?php

use Mini\controller\HomeController;

 require APP . 'view/_templates/header.php' ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Erro 404</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Erro 404</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Pagina não encontrada.</h3>
            <p>
                Nós não encontramos a pagina que você está procurando.
                Em todo caso, você pode <a href="<?= HomeController::ROUTE ?>">retornar para a Home</a>.
            </p>
        </div>
    </div>
</section>

<?php require APP . 'view/_templates/footer.php' ?>