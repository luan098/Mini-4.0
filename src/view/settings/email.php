<?php

use Mini\controller\SettingsController;

?>
<div class="tab-pane show active" id="email" role="tabpanel">
    <form method="post" action="<?= SettingsController::ROUTE . "/handle-edit-email" ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nome <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Nome do E-mail" name="name" value="<?= $settingsEmail->name ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_email">E-mail Base <span class="text-red">*</span></label>
                    <input type="email" class="form-control" id="sender_email" placeholder="mail@seuhost.com" name="sender_email" value="<?= $settingsEmail->sender_email ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_password">Senha <span class="text-red">*</span></label>
                    <input type="password" class="form-control" id="sender_password" placeholder="*****" name="sender_password" value="">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_host">Host <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="sender_host" placeholder="mail.seuhost.net.br" name="sender_host" value="<?= $settingsEmail->sender_host ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="port">Porta <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="port" placeholder="587" name="port" value="<?= $settingsEmail->port ?>" required>
                </div>
            </div>

            <div class="col-sm-12 mt-3">
                <div class='float-right'>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </div>
        </div>
    </form>
</div>