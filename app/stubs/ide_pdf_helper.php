<?php

/**
 * File ini digunakan khusus untuk membantu Intelephense dalam mengenali
 * method-method dari library TCPDF yang gagal di-index karena ukuran filenya.
 * File ini tidak boleh di-include atau dijalankan.
 */

namespace {
    class TCPDF
    {
        public $xobjects = [];

        public $PageResources = [];

        public $buffer = '';

        protected function _putsignature() {}

        /**
         * Starts a new XObject template.
         *
         * @param  float  $w  width
         * @param  float  $h  height
         * @param  bool  $group  if true, the template is a group
         * @return string template ID
         */
        public function startTemplate($w = 0, $h = 0, $group = false)
        {
            return '';
        }

        /**
         * Ends the current XObject template.
         */
        public function endTemplate() {}

        /**
         * Puts an image in the page.
         */
        public function Image($file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false) {}

        /**
         * Set the digital signature appearance.
         */
        public function setSignatureAppearance($x = 0, $y = 0, $w = 0, $h = 0, $page = -1, $name = '') {}

        public function SetPrintHeader($val = true) {}

        public function SetPrintFooter($val = true) {}

        public function setSourceFile($filename) {}

        public function importPage($pageno, $boxName = '/CropBox', $groupXObject = true) {}

        public function getTemplateSize($tplidx, $_w = null, $_h = null) {}

        public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false) {}

        public function useTemplate($tplidx, $_x = null, $_y = null, $_w = null, $_h = null, $adjustPageSize = false) {}

        public function setSignature($signing_cert = '', $private_key = '', $private_key_password = '', $extracerts = '', $cert_type = 2, $info = [], $approval = 'A') {}

        public function output($name = 'doc.pdf', $dest = 'I') {}
    }
}
