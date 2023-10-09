<?php

use Mini\controller\HomeController;

require APP . 'view/_templates/header.php' ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>401 Error Page</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">401 Error Page</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="error-page">
        <h2 class="headline text-danger"> 401</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! You does not have permission.</h3>
            <p>
                Your user type does not have permission to access this page. Please contact your administrator if you would like access.
                Meanwhile, you may <a href="<?= HomeController::ROUTE ?>">return to home</a>.
            </p>
        </div>
    </div>
</section>

<?php require APP . 'view/_templates/footer.php' ?>