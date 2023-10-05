<?php

use Mini\controller\UsersController;

?>
<div class="tab-pane show active" id="data" role="tabpanel">
    <form method="post" action="<?= UsersController::ROUTE . "/" . ($idUser ? "handle-edit/$idUser" : 'handle-add') ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?= $user->name ?? '' ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="cpf_cnpj">CPF/CNPJ <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="cpf_cnpj" placeholder="CPF/CNPJ" name="cpf_cnpj" value="<?= $user->cpf_cnpj ?? '' ?>" required>
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
                    <label for="password"><?= !isset($user) ? 'Password <span class="text-red">*</span>' : 'New Password' ?></label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="" autocomplete="new-password">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="id_user_type">Type <span class="text-red">*</span></label>
                    <select class="form-control" name="id_user_type" required>
                        <?php foreach ($userTypes as $type) : ?>
                            <option value="<?= $type->id ?>" <?= $type->id == ($user->id_user_type ?? '') ? "selected" : '' ?>><?= $type->name ?? '' ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="datemask">Date of Birth <span class="text-red">*</span></label>
                    <input type="date" class="form-control" name="date_birth" value="<?= $user->date_birth ?? '1990-01-01' ?>" required>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="language">Language <span class="text-red">*</span></label>
                    <select class="form-control" name="language">
                        <?php foreach ($languages as $language) : ?>
                            <option <?= ($user->language ?? 'en') == $language->code ? 'selected' : '' ?> value="<?= $language->code ?? '' ?>"><?= $language->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" name="terms" <?= $user->terms ?? true ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch3">Terms</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" value="1" name="approved" <?= $user->approved ?? true ? "checked" : '' ?>>
                        <label class="custom-control-label" for="customSwitch2">Approved</label>
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
                <a href="<?= UsersController::ROUTE ?>" class="btn btn-default">Back</a>
                <div class='float-right'>
                    <a href="<?= UsersController::ROUTE . "/add" ?>" class="btn btn-success">New</a>
                    <button type="submit" class="btn btn-primary"><?= $user->id ?? '' ? "Update" : "Register" ?></button>
                </div>
            </div>
        </div>
    </form>
</div>