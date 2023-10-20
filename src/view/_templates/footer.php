<?php

use Mini\core\FrontController;
?>
</div>

<footer class="main-footer">
    <strong>Copyright &copy; 2023-<?= date('Y'); ?> <a href="#" target="_blank">Your Company Here</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
</footer>
</div>
<?= $this->renderScript(FrontController::RENDER_CONFIG_FOOTER_SCRIPT) ?>
<?php require_once APP . 'view/_templates/components/toast-fire.php' ?>