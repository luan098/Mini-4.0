<?php

use Mini\controller\SettingsController;

?>
<div class="tab-pane show active" id="email" role="tabpanel">
    <form method="post" action="<?= SettingsController::ROUTE . "/handle-edit-email" ?>">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Mail Name" name="name" value="<?= $settingsEmail->name ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_email">Sender email <span class="text-red">*</span></label>
                    <input type="email" class="form-control" id="sender_email" placeholder="E-mail" name="sender_email" value="<?= $settingsEmail->sender_email ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_password">Sender Password <span class="text-red">*</span></label>
                    <input type="password" class="form-control" id="sender_password" placeholder="*****" name="sender_password" value="">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sender_host">Host <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="sender_host" placeholder="mail.ydeal.net.br" name="sender_host" value="<?= $settingsEmail->sender_host ?>" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="port">Port <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="port" placeholder="587" name="port" value="<?= $settingsEmail->port ?>" required>
                </div>
            </div>

            <div class="col-sm-12 mt-3">
                <div class='float-right'>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>