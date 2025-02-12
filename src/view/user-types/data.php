<?php

?>

<div class="tab-pane show active" id="data" role="tabpanel">
    <form method="post" action="<?= "$this->route/" . (($idUserType ?? false) ? "handle-edit/$idUserType" : 'handle-add') ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nome <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Nome" name="name" value="<?= $userType->name ?? '' ?>" required>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <?php if (isset($userType)) : ?>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" value="1" name="status" <?= $userType->status ? "checked" : '' ?>>
                            <label class="custom-control-label" for="customSwitch1">Status</label>
                        </div>
                    <?php endif ?>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" value="1" name="is_admin" <?= $userType->is_admin ?? false ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch2">É Admin</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" name="is_customer" <?= $userType->is_customer ?? false ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch3">É Cliente</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-3">
                <a href="<?= "$this->route" ?>" class="btn btn-default">Voltar</a>
                <div class='float-right'>
                    <a href="<?= "$this->route/add" ?>" class="btn btn-success">Novo</a>
                    <button type="submit" class="btn btn-primary"><?= $userType->id ?? '' ? "Atualizar" : "Registrar" ?></button>
                </div>
            </div>
        </div>
    </form>
</div>