<?php

use Mini\controller\UsersController;

?>
<div class="tab-pane show active" id="data" role="tabpanel">
    <form method="post" action="<?= UsersController::ROUTE . "/" . (($idUser ?? false) ? "handle-edit/$idUser" : 'handle-add') ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nome <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Nome" name="name" value="<?= $user->name ?? '' ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="email">E-mail <span class="text-red">*</span></label>
                    <input type="email" class="form-control" id="email" placeholder="E-mail" name="email" value="<?= $user->email ?? '' ?>" required autocomplete="new-email">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="password"><?= !isset($user) ? 'Senha <span class="text-red">*</span>' : 'Nova Senha' ?></label>
                    <input type="password" class="form-control" id="password" placeholder="******" name="password" value="" autocomplete="new-password">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="id_user_type">Tipo <span class="text-red">*</span></label>
                    <select class="form-control" name="id_user_type" required>
                        <?php foreach ($userTypes as $type) : ?>
                            <option value="<?= $type->id ?>" <?= $type->id == ($user->id_user_type ?? '') ? "selected" : '' ?>><?= $type->name ?? '' ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" name="terms" <?= $user->terms ?? true ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch3">Termos</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" value="1" name="approved" <?= $user->approved ?? true ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch2">Aprovado</label>
                    </div>
                    <?php if (isset($user)) : ?>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" value="1" name="status" <?= $user->status ? "checked" : '' ?>>
                            <label class="custom-control-label" for="customSwitch1">Status</label>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="col-sm-12 mt-3">
                <a href="<?= UsersController::ROUTE ?>" class="btn btn-default">Voltar</a>
                <div class='float-right'>
                    <a href="<?= UsersController::ROUTE . "/add" ?>" class="btn btn-success">Novo</a>
                    <button type="submit" class="btn btn-primary"><?= $user->id ?? '' ? "Atualizar" : "Registrar" ?></button>
                </div>
            </div>
        </div>
    </form>
</div>