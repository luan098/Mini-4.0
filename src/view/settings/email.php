<?php

use Mini\controller\SettingsController;

?>
<div class="tab-pane show active" id="email" role="tabpanel">
    <form method="post" action="<?= SettingsController::ROUTE . "/handle-edit-email" ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_name">Nome <span class="text-red">*</span></label>
                    <input type="text" class="form-control" placeholder="Nome do E-mail" id="sender_name" name="sender_name" value="<?= $settingsEmail->sender_name ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_email">E-mail Base <span class="text-red">*</span></label>
                    <input type="email" class="form-control" placeholder="mail@seuhost.com" id="sender_email" name="sender_email" value="<?= $settingsEmail->sender_email ?>" autocomplete="email" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_password">Senha <span class="text-red">*</span></label>
                    <input type="password" class="form-control" placeholder="*****" id="sender_password" name="sender_password" value="">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="host">Host <span class="text-red">*</span></label>
                    <input type="text" class="form-control" placeholder="mail.seuhost.net.br" id="host" name="host" value="<?= $settingsEmail->host ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="port">Porta <span class="text-red">*</span></label>
                    <input type="text" class="form-control" placeholder="587" id="port" name="port" value="<?= $settingsEmail->port ?>" required>
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