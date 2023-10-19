<?php

use Mini\controller\HomeController;

require APP . 'view/_templates/header.php' ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Erro 401</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Erro 401</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="error-page">
        <h2 class="headline text-danger"> 401</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Você não tem permissão.</h3>
            <p>
                Seu usuário não tem permissão para acessar esta pagina. Contacte seu administrador se você precisa acessar este caminho.
                Em todo caso, você pode <a href="<?= HomeController::ROUTE ?>">retornar para a Home</a>.
            </p>
        </div>
    </div>
</section>

<?php require APP . 'view/_templates/footer.php' ?>