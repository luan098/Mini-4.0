<?php

namespace Mini\core;

class FrontController
{
    private $styles = [
        "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback",
        "./plugins/fontawesome-free/css/all.min.css",
        "./plugins/sortable/jquery-ui.css",
        "./plugins/fancybox5/fancybox5.css",
        "./plugins/overlayScrollbars/css/OverlayScrollbars.min.css",
        "./plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css",
        "./plugins/summernote/summernote-bs4.min.css",
        "./plugins/select2/css/select2.min.css",
        "./plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
        "./plugins/datatables/datatables.min.css",
        "./plugins/adminLTE3/css/adminlte.min.css",
        "./css/application.css",
    ];
    private $headerScript = [
        "./plugins/sweetalert2/sweetalert2.min.js",
        "./plugins/jquery/jquery.min.js",
        "./plugins/js-cookie/js-cookie.min.js",
        "./js/Ajax.js",
        "./plugins/datatables/datatables.min.js",
        "./plugins/moment/moment.min.js",
        "./js/YPDatatable.js",
    ];

    private $script = [
        "./plugins/bootstrap/js/bootstrap.bundle.min.js",
        "./plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js",
        "./plugins/summernote/summernote-bs4.min.js",
        "./plugins/bs-custom-file-input/bs-custom-file-input.min.js",
        "./plugins/sortable/jquery-ui.js",
        "./plugins/fancybox5/fancybox5.js",
        "./plugins/adminLTE3/js/adminlte.js",
        "./plugins/jquery-mousewheel/jquery.mousewheel.js",
        "./plugins/raphael/raphael.min.js",
        "./plugins/jquery-mapael/jquery.mapael.min.js",
        "./plugins/jquery-mapael/maps/usa_states.min.js",
        "./plugins/chart.js/Chart.min.js",
        "./plugins/inputmask/jquery.inputmask.min.js",
        "./plugins/select2/js/select2.full.min.js",
        "./plugins/filterizr/jquery.filterizr.min.js",
        "./js/application.js",
    ];
    /**
     * Configuração de render para renderizar tudo sem filtrar
     */
    public const RENDER_CONFIG_ALL = 1;

    /**
     * Configuração de render para renderizar somente os scripts do footer
     */
    public const RENDER_CONFIG_FOOTER_SCRIPT = 2;

    /**
     * Configuração de render para renderizar somente os scripts do header
     */
    public const RENDER_CONFIG_HEADER_SCRIPT = 3;

    /** Carrega todos os elementos padrão do site */
    public function __construct()
    {
    }

    public function getStyles()
    {
        return $this->styles;
    }

    public function addStyle($stylePath)
    {
        if (!in_array($stylePath, $this->getStyles())) {
            $this->styles[] = $stylePath;
        }
    }

    public function removeStyle($stylePath)
    {
        if (in_array($stylePath, $this->getStyles())) {
            unset($this->styles[array_search($stylePath, $this->getStyles())]);
            $this->styles = array_values($this->getStyles());
        }
    }

    public function renderStyle()
    {
        $return = [];

        foreach ($this->getStyles() as $style) {
            array_push($return, "<link rel='stylesheet' href='{$style}'>");
        }

        return implode("\n", $return);
    }

    public function getScripts()
    {
        return $this->script;
    }

    public function getHeaderScripts()
    {
        return $this->headerScript;
    }

    public function addScript($scriptPath, $headerScript = false)
    {
        if (!$headerScript && !in_array($scriptPath, $this->getScripts())) {
            $this->script[] = $scriptPath;
        } elseif ($headerScript && !in_array($scriptPath, $this->getHeaderScripts())) {
            $this->headerScript[] = $scriptPath;
        }
    }

    public function removeScript($scriptPath, $headerScript = false)
    {
        if (!$headerScript && in_array($scriptPath, $this->getScripts())) {
            unset($this->script[array_search($scriptPath, $this->getScripts())]);
            $this->script = array_values($this->getScripts());
        } elseif (!$headerScript && in_array($scriptPath, $this->getScripts())) {
            unset($this->headerScript[array_search($scriptPath, $this->getScripts())]);
            $this->headerScript = array_values($this->getScripts());
        }
    }

    /**
     * Render definido de scripts do sistema
     *
     * @param integer $renderConfig self::RENDER_CONFIGS...
     * @return string
     */
    public function renderScript($renderConfig = 1)
    {
        $return = [];

        if ($renderConfig == SELF::RENDER_CONFIG_ALL || $renderConfig == SELF::RENDER_CONFIG_HEADER_SCRIPT) {
            foreach ($this->getHeaderScripts() as $script) {
                if (file_exists(str_replace(URL, "", $script)) === true) {
                    $fileVer = filesize(str_replace(URL, "", $script));
                    array_push($return, "<script src='{$script}?filever={$fileVer}'></script>");
                } else {
                    array_push($return, "<script src='{$script}'></script>");
                }
            }
        }

        if ($renderConfig == SELF::RENDER_CONFIG_ALL || $renderConfig == SELF::RENDER_CONFIG_FOOTER_SCRIPT) {
            foreach ($this->getScripts() as $script) {
                if (file_exists(str_replace(URL, "", $script)) === true) {
                    $fileVer = filesize(str_replace(URL, "", $script));
                    array_push($return, "<script src='{$script}?filever={$fileVer}'></script>");
                } else {
                    array_push($return, "<script src='{$script}'></script>");
                }
            }
        }

        return implode("\n", $return);
    }
}
