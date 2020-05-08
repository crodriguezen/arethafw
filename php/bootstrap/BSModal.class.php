<?php
class BSModal {
    private static $isTabIndex = true;
    private static $isCloseModalJS = false;

    private static $isButtonCancelDismiss = true;
    private static $isButtonConfirmDismiss = true;
    private static $isButtonExitDismiss = true;

    public static function disableTabIndex() {
        BSModal::$isTabIndex = false;
    }

    public static function enableCloseModalJS() {
        BSModal::$isCloseModalJS = true;
    }

    public static function disableCloseModalJS() {
        BSModal::$isCloseModalJS = false;
    }

    public static function disableButtonsDismiss() {
        BSModal::$isButtonCancelDismiss  = false;
        BSModal::$isButtonConfirmDismiss = false;
        BSModal::$isButtonExitDismiss    = false;
    }

    public static function enableButtonsDismiss() {
        BSModal::$isButtonCancelDismiss  = true;
        BSModal::$isButtonConfirmDismiss = true;
        BSModal::$isButtonExitDismiss    = true;
    }

    public static function disableButtonCancelDismiss() {
        BSModal::$isButtonCancelDismiss = false;
    }

    public static function enableButtonCancelDismiss() {
        BSModal::$isButtonCancelDismiss = true;
    }

    public static function disableButtonConfirmDismiss() {
        BSModal::$isButtonConfirmDismiss = false;
    }

    public static function enableButtonConfirmDismiss() {
        BSModal::$isButtonConfirmDismiss = true;
    }

    public static function disableButtonExitDismiss() {
        BSModal::$isButtonExitDismiss = false;
    }

    public static function enableButtonExitDismiss() {
        BSModal::$isButtonExitDismiss = true;
    }

    private static function checkPositionV($position) {
        $pos = "";
        switch ($position) {
            case 'top'    : $pos = ""; break;
            case 'center' : $pos = " modal-dialog-centered"; break;
            default       : $pos = ""; break;
        }
        return $pos;
    }

    private static function checkPositionH($position) {
        $pos = "";
        switch ($position) {
            case 'left' : $pos = " modal-slide-left"; break;
            case 'right': $pos = " modal-slide-right"; break;
            default     : $pos = ""; break;
        }
        return $pos;
    }

    private static function checkSize($size) {
        $msize = "";
        switch ($size) {
            case 'lg' : $msize = " modal-lg"; break;
            case 'xl' : $msize = " modal-xl"; break;
            default   : $msize = ""; break;
        }
        return $msize;
    }

    public static function createEmpty($id, $title, $content = "", $positionh = "", $positionv = "", $size = "", $buttons = null) {

        $tabindex = "";
        if (BSModal::$isTabIndex) {
            $tabindex = ' tabindex="-1" ';
        }

        $posh = "";
        $posh = BSModal::checkPositionH($positionh);

        $posv = "";
        $posv = BSModal::checkPositionV($positionv);
        
        $msize = "";
        $msize = BSModal::checkSize($size);

        $modal  = '<!-- Begin Modal ' . $id . ' -->' . "\n";
        $modal .= '<div class="modal ' . $posh . ' fade" id="' . $id . '"' . $tabindex;
        $modal .= 'role="dialog" aria-labelledby="" aria-hidden="true">' . "\n";
        $modal .= '    <div class="modal-dialog' . $posv . '' . $msize . '" role="document" id="' . $id . '-dialog">' . "\n";
        $modal .= '        <div class="modal-content" id="' . $id . '-content">' . "\n";
        $modal .= '            <div class="modal-header">' . "\n";
        $modal .= '                <h5 class="modal-title" id="">' . $title . '</h5>' . "\n";
        
        $modal .= '                <button type="button" class="close afclose-' . $id . '" data-dismiss="modal" aria-label="Close" title="Cerrar">' . "\n";
        $modal .= '                    <span aria-hidden="true">&times;</span>' . "\n";
        $modal .= '                </button>' . "\n";
        
        $modal .= '                <button type="button" class="aretha-fullview" data-target="#' . $id . '" data-state="normal" title="Vista Completa">' . "\n";
        $modal .= '                    <span aria-hidden="true"><i class="mdi mdi-fullscreen" id="' . $id . '-icon"></i></span>' . "\n";
        $modal .= '                </button>' . "\n";

        $modal .= '            </div>' . "\n";
        $modal .= '            <div class="modal-body" style="overflow:scroll;">' . "\n";
        $modal .= '                ' . $content;
        $modal .= '            </div>' . "\n";
        $modal .= '            <div class="modal-footer">' . "\n";

        if ($buttons != null) {
            if (is_array($buttons)) {
                foreach ($buttons as $button) {
                    $modal .= '                ' . $button->getHTML() . "\n";   
                }
            }
        }
        
        $btnCancel = '';
        if (BSModal::$isButtonCancelDismiss) {
            $btnCancel = 'data-dismiss="modal"';
        }

        $modal .= '                <button type="button" class="btn btn-secondary" ' . $btnCancel . '>' . "\n";
        $modal .= '                    CANCELAR' . "\n";
        $modal .= '                </button>' . "\n";

        $modal .= '            </div>' . "\n";
        $modal .= '        </div>' . "\n";
        $modal .= '    </div>' . "\n";
        $modal .= '</div>' . "\n";
        $modal .= '<!-- End Modal ' . $id . ' -->' . "\n" . "\n";

        return $modal;
    }

    public static function createImportDialog($id, $title, $content = "", $positionh = "", $positionv = "", $size = "", $buttons = null) {
        $form  = '<div class="form-row">' . "\n";
        $form .= '    <div class="form-group col-md-4">' . "\n";
        $form .= '        <label for="importacion_formato">Formato de importación</label>' . "\n";
        $form .= '        <div class="input-group mb-3">';
        $form .= '            <select class="form-control id="importacion_formato">' . "\n";
        $form .= '                <option selected>Fiscalli XLSX</option>' . "\n";
        $form .= '            </select>' . "\n";
        $form .= '            <div class="input-group-append">';
        $form .= '                <span class="input-group-text"><i class="mdi mdi-cloud-download" title="Descargar"></i></span>';
        $form .= '            </div>';
        $form .= '        </div>';
        $form .= '    </div>' . "\n";
        $form .= '    <div class="form-group col-md-4">';
        $form .= '        <label for="importacion_duplicados">Permitir duplicados</label>';
        $form .= '        <select class="form-control id="importacion_duplicados">';
        $form .= '            <option selected>NO</option>';
        $form .= '            <option>SI</option>';
        $form .= '        </select>';
        $form .= '    </div>' . "\n";
        $form .= '    <div class="form-group col-md-4">' . "\n";
        $form .= '        <label for="importacion_habilitar">Habilitar</label>' . "\n";
        $form .= '        <select class="form-control id="importacion_habilitar">' . "\n";
        $form .= '            <option selected>Usar Formato</option>' . "\n";
        $form .= '            <option>SI</option>' . "\n";
        $form .= '            <option>NO</option>' . "\n";
        $form .= '        </select>' . "\n";
        $form .= '    </div>' . "\n";
        $form .= '</div>' . "\n";
        $form .= '<div class="form-row">' . "\n";
        $form .= '    <div class="form-group col-md-12">';
        $form .= '        <label for="importacion_habilitar">Selecciona el archivo a procesar</label>' . "\n";
        $form .= '        <div class="input-group mb-3">' . "\n";
        $form .= '            <div class="input-group-prepend">' . "\n";
        $form .= '                <span class="input-group-text">Máximo 5Mb</span>' . "\n";
        $form .= '            </div>' . "\n";
        $form .= '            <div class="custom-file">' . "\n";
        $form .= '                <input type="file" class="custom-file-input" id="importacion_file">' . "\n";
        $form .= '                <label class="custom-file-label" for="importacion_file">Buscar Archivo</label>' . "\n";
        $form .= '            </div>' . "\n";
        $form .= '        </div>' . "\n";
        $form .= '    </div>' . "\n";
        $form .= '</div>' . "\n";

        if ($buttons == null) {
            $buttons = array(new BSButton("Importar", "success", "btnImportarArchivo", ""));
        } else {
            $buttons[] = new BSButton("Importar", "success", "btnImportarArchivo", "");
        }

        return BSModal::createEmpty($id, $title, $form, $positionh, $positionv, $size, $buttons);
    }

    public static function createExportDialog($id, $title, $content = "", $positionh = "", $positionv = "", $size = "", $buttons = null) {
        $form  = '<div class="form-row">' . "\n";
        $form .= '    <div class="form-group col-md-4">' . "\n";
        $form .= '        <label for="exportacion_formato">Formato de exportación</label>' . "\n";
        $form .= '        <select class="form-control id="exportacion_formato">' . "\n";
        $form .= '            <option>Microsfot Excel (XLSX)</option>' . "\n";
        $form .= '            <option selected>Valores Separados por Comas (CSV)</option>' . "\n";
        $form .= '            <option>PDF</option>' . "\n";
        $form .= '        </select>' . "\n";
        $form .= '    </div>' . "\n";
        $form .= '    <div class="form-group col-md-4">';
        $form .= '        <label for="exportacion_duplicados">Exportar duplicados</label>';
        $form .= '        <select class="form-control id="exportacion_duplicados">';
        $form .= '            <option selected>NO</option>';
        $form .= '            <option>SI</option>';
        $form .= '        </select>';
        $form .= '    </div>' . "\n";
        $form .= '    <div class="form-group col-md-4">' . "\n";
        $form .= '        <label for="exportacion_habilitados">Exportar solo habilitados</label>' . "\n";
        $form .= '        <select class="form-control id="exportacion_habilitados">' . "\n";
        $form .= '            <option>SI</option>' . "\n";
        $form .= '            <option selected>NO</option>' . "\n";
        $form .= '        </select>' . "\n";
        $form .= '    </div>' . "\n";
        $form .= '</div>' . "\n";

        if ($buttons == null) {
            $buttons = array(new BSButton("Exportar", "success", "btnExportarArchivo", ""));
        } else {
            $buttons[] = new BSButton("Exportar", "success", "btnExportarArchivo", "");
        }

        return BSModal::createEmpty($id, $title, $form, $positionh, $positionv, $size, $buttons);
    }

    public static function createDeleteConfirmation($id, $title, $subtitle = "", $positionh = "", $positionv = "", $size = "") {
        return BSModal::createConfirmation($id, $title, $subtitle, $positionh,  $positionv, $size, "SI, ELIMINAR");
    }

    public static function createDisableConfirmation($id, $title, $subtitle = "", $positionh = "", $positionv = "", $size = "") {
        return BSModal::createConfirmation($id, $title, $subtitle, $positionh,  $positionv, $size, "SI, DESHABILITAR");
    }

    public static function createEnableConfirmation($id, $title, $subtitle = "", $positionh = "", $positionv = "", $size = "") {
        return BSModal::createConfirmation($id, $title, $subtitle, $positionh,  $positionv, $size, "SI, HABILITAR", "btn-success");
    }

    public static function createAgreeConfirmation($id, $title, $subtitle = "", $positionh = "", $positionv = "", $size = "") {
        return BSModal::createConfirmation($id, $title, $subtitle, $positionh,  $positionv, $size, "SI, DE ACUERDO", "btn-success");
    }

    private static function createConfirmation($id, $title, $subtitle = "", $positionh = "", $positionv = "", $size = "", $confirmButtonText = "SI", $confirmButtonClass = "btn-danger") {

        $posh = "";
        $posh = BSModal::checkPositionH($positionh);

        $posv = "";
        $posv = BSModal::checkPositionV($positionv);

        $msize = "";
        $msize = BSModal::checkSize($size);

        $confirmBtnClass = strtolower($confirmButtonClass);

        $isSubtitle = false;
        if (trim($subtitle) != "") {
            $isSubtitle = true;
        }

        $modal  = '<!-- Begin: Modal Confirmation ' . $id . ' -->' . "\n";
        $modal .= '<div class="modal' . $posh . ' fade" id="' . $id . '" data-backdrop="static" tabindex="-1" ';
        $modal .= 'role="dialog" aria-hidden="true">' . "\n";
        $modal .= '    <div class="modal-dialog' . $posv . '" role="document">' . "\n";
        $modal .= '        <div class="modal-content">' . "\n";

        $btnExit = '';
        if (BSModal::$isButtonExitDismiss) {
            $btnExit = 'data-dismiss="modal"';
        }

        $modal .= '            <button type="button" class="close afclose-' . $id . '" ' . $btnExit . ' aria-label="Close">' . "\n";

        $modal .= '                <span aria-hidden="true">&times;</span>' . "\n";
        $modal .= '            </button>' . "\n";
        $modal .= '            <div class="modal-body" id="' . $id . 'content">' . "\n";
        $modal .= '                <div class="px-3 pt-3 text-center">' . "\n";
        $modal .= '                    <div class="event-type warning">' . "\n";
        $modal .= '                        <div class="event-indicator">' . "\n";
        $modal .= '                            <i class="mdi mdi-exclamation text-white" style="font-size: 60px"></i>' . "\n";
        $modal .= '                        </div>' . "\n";
        $modal .= '                    </div>' . "\n";
        $modal .= '                    <h3 class="pt-3">' . $title . '</h3>' . "\n";
        if ($isSubtitle) { 
            $modal .= '                    <p class="text-muted">' . "\n";
            $modal .= '                        ' . $subtitle . "\n";
            $modal .= '                    </p>' . "\n";
        }
        $modal .= '                </div>' . "\n";
        $modal .= '            </div>' . "\n";
        $modal .= '            <div class="modal-footer ">' . "\n";

        $btnConfirm = '';
        if (BSModal::$isButtonConfirmDismiss && !BSModal::$isCloseModalJS) {
            $btnConfirm = 'data-dismiss="modal"';
        }

        $modal .= '              <button class="btn ' . $confirmBtnClass . '" ' . $btnConfirm . ' id="button-' . $id . '" >' . "\n";
        $modal .= strtoupper($confirmButtonText) . "\n";
        $modal .= '              </button>' . "\n";

        $btnCancel = '';
        if (BSModal::$isButtonCancelDismiss && !BSModal::$isCloseModalJS) {
            $btnCancel = 'data-dismiss="modal"';
        }

        $modal .= '              <a href="#" class="btn btn-secondary afclose-' . $id . '" ' . $btnCancel . '>CANCELAR</a>' . "\n";
        $modal .= '            </div>' . "\n";
        $modal .= '        </div>' . "\n";
        $modal .= '    </div>' . "\n";
        $modal .= '</div>' . "\n";
        
        if (BSModal::$isCloseModalJS) {
            $modal .= "<!-- JS Modal " . $id . " -->" . "\n";
            $modal .= '<script type="text/javascript">' . "\n";

            $modal .= "// Modal Close Button"  . "\n";
            $modal .= '$("body").off("click", ".afclose-' . $id . '");'. "\n";
            $modal .= '$("body").on("click", ".afclose-' . $id . '", function(e) {' . "\n";
            $modal .= "    $('#" . $id . "').modal('hide');" . "\n";
            $modal .= "    console.log('af-clic: Close Button');" . "\n";
            $modal .= "});" . "\n";

            $modal .= '</script>' . "\n";
        }

        $modal .= '<!-- End: Modal Confirmation -->' . "\n" . "\n";

        return $modal;
    }
}
?>