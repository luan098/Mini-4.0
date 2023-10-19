<?php

use Mini\controller\UsersController;
use Mini\model\Users;

?>

<div class="tab-pane show active" id="cover" role="tabpanel">
    <form method="post" action="<?= UsersController::ROUTE . "/handle-add-update-cover/$idUser" ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group">
                    <label for="cover">Capa</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">Imagens</span>
                        </div>
                        <div class="custom-file">
                            <input id="imageInput" type="file" multiple="false" accept=".jpg, .jpeg, .png, .gif, .webp" class="custom-file-input" aria-describedby="send-cover" name="file" required>
                            <label class="custom-file-label" for="file" data-browse="Search">Selecione um arquivo...</label>
                        </div>
                    </div>
                    <span id="errorMsg" class="error text-red"></span>
                </div>
            </div>
            <div class="col-sm-2" style="margin-top: 32px !important;">
                <button type="submit" class="btn btn-primary float-right">Enviar</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="col-sm-8 d-flex justify-content-center align-items-center">
                <?php if ($coverUploaded) : ?>
                    <img class="img-fluid" src="<?= "./" . Users::UPLOADS_PATH . "$idUser/$coverUploaded->file" ?>" alt="Users Cover" />
                <?php endif ?>
            </div>
        </div>
        <div class="col-sm-12 mt-3">
            <a href="<?= UsersController::ROUTE ?>" class="btn btn-default">Voltar</a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#imageInput').on('change', function(e) {
            const errorMsg = $('#errorMsg');
            const allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
            const maxFileSize = 100 * 1024; // 100 KB
            const maxFilesAllowed = 1;
            const input = e.currentTarget;
            let errorMsgText = "";

            if (input.files.length === 0) {
                errorMsgText = "Por favor selecione um arquivo de imagem.";
            } else if (input.files.length > maxFilesAllowed) {
                errorMsgText = "Você pode selecionar até " + maxFilesAllowed + " arquivo.";
            }

            Array.from(input.files).forEach(file => {
                const fileName = file.name;
                const fileSize = file.size;
                const fileExtension = fileName.split(".").pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    errorMsgText = "Formato de imagem inválido. Somente JPG, JPEG, PNG, WEBP, e GIF são permitidos.";
                } else if (fileSize > maxFileSize) {
                    errorMsgText = "O tamanho do arquivo excede o limite permitido (100 KB).";
                }
            });

            if (errorMsgText) input.value = "";
            errorMsg.text(errorMsgText);
        });
    });
</script>