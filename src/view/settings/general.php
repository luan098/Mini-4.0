<?php

use Mini\controller\SettingsController;

?>
<div class="tab-pane show active" id="general" role="tabpanel">
    <form method="post" action="<?= SettingsController::ROUTE . "/handleEditGeneral" ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="row col-12 m-0">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="logo">Logo Brand</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">(165x165)</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="logo">
                                <label class="custom-file-label" for="logo">Buscar Imagem...</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <img src="./images/config/logo.png" alt="System logo" />
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="favicon">Favicon</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">(20x20)</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="favicon">
                                <label class="custom-file-label" for="favicon">Buscar Imagem...</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <img src="./images/config/favicon.png" alt="System favicon" />
                        </div>
                    </div>
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