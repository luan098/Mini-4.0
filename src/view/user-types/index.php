<?php

use Mini\controller\HomeController;

?>

<?php require APP . 'view/_templates/header.php' ?>

<div class="container-xxl">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Tipos de Usuário </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= HomeController::ROUTE ?>">Home</a></li>
                        <li class="breadcrumb-item active">Tipos de Usuário</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header vertical-middle">
                            <h3 class="card-title">Listar</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= $this->route ?>" method="GET">
                                <div class="card <?= isset($_GET['filtered']) && $_GET['filtered'] ? '' : 'collapsed-card' ?>" style="cursor:pointer;">
                                    <div class="card-header" data-card-widget="collapse">
                                        <h3 class="card-title">Filtrar</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="filtered" value="s">
                                        <div class="row p-2 m-0 YP-datatable-filters">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" name="status">
                                                        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] == '1' ? "selected" : ''); ?>>Ativo</option>
                                                        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] == '0' ? "selected" : ''); ?>>Inativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <a href="<?= $this->route ?>" class="btn btn-warning"><i class="fa fa-eraser mr-1"></i> Remover</a>
                                        <button type="submit" class="btn pull-right btn-primary"><i class="fa fa-filter mr-1"></i> Aplicar</button>
                                    </div>
                                </div>
                            </form>
                            <table id="user_types_table" class="table table-striped table-bordered col-xs-12"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    var datatable = $("#user_types_table").YPDatatable('UserTypes', {
        buttons: ['colvis', 'csv', 'pdf', 'print'],
        order: [
            [0, 'desc'],
        ],
        columns: [{
                title: "ID",
                className: "text-center",
                data: "id"
            },
            {
                title: "Nome",
                data: "name"
            },
            {
                title: "Status",
                data: "status",
                className: "text-center",
                render: (field, display, row, settings) => {
                    return !!+field ? `<span class="badge badge-success">Ativo</span>` : `<span class="badge badge-danger">Inativo</span>`;
                },
            },
            {
                title: "Ações",
                searchable: false,
                orderable: false,
                className: "text-center",
                data: "id",
                render: (data, display, row, settings) => {
                    return `
                        <a class="btn btn-primary btn-md" title="Editar" href="<?= "$this->route/edit/" ?>${data}"><i class="fa fa-edit"></i></a>
                    `;
                },
            },
        ],
    });
</script>

<?php require APP . 'view/_templates/footer.php' ?>