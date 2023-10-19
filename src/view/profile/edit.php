<?php

use Mini\controller\ProfileController;

?>

<form class="form-horizontal" method="post" action="<?= ProfileController::ROUTE . "/handleEdit" ?>" enctype="multipart/form-data">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Nome</label>
        <div class="col-sm-10">
            <input type="name" class="form-control" id="inputName" name='name' placeholder="Nome" value="<?= $user->name ?>" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">E-mail</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" placeholder="E-mail" value="<?= $user->email ?>" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="new-password" class="col-sm-2 col-form-label">Nova Senha</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" autocomplete="new-password" name="new-password" placeholder="Digite uma nova senha" value="">
        </div>
    </div>
    <div class="form-group row">
        <label for="confirm-new-password" class="col-sm-2 col-form-label">Confirmação</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" autocomplete="new-password" name="confirm-new-password" placeholder="Confirme a nova senha" value="">
        </div>
    </div>
    <div class="form-group row">
        <label for="cover" class="col-sm-2 col-form-label">Imagem Capa</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text">Imagem</span>
                </div>
                <div class="custom-file">
                    <input id="imageInput" type="file" multiple="false" accept=".jpg, .jpeg, .png, .gif, .webp" class="custom-file-input" aria-describedby="send-cover" name="file">
                    <label class="custom-file-label" for="file" data-browse="Search"><?= $uploadCover ? $uploadCover->name : "Selecione um arquivo..." ?></label>
                </div>
            </div>
            <span id="errorMsg" class="error text-red"></span>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="form-group">
            <div class="input-group">
                <div class="custom-file">
                </div>
            </div>
            <span id="errorMsg" class="error text-red"></span>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary float-right">Salvar</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#imageInput').on('change', function(e) {
            const errorMsg = $('#errorMsg');
            const allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
            const maxFileSize = 5 * 1024 * 1024; // 5 MB
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
                    errorMsgText = "O tamanho do arquivo excede o limite permitido (5 MB).";
                }
            });

            if (errorMsgText) input.value = "";
            errorMsg.text(errorMsgText);
        });
    });
</script>