<?php

namespace App\Services\Tte;

use setasign\Fpdi\Tcpdf\Fpdi;

class CustomFpdi extends Fpdi
{
    /**
     * Data for digital signature appearance.
     * We make it public to allow template_id injection.
     */
    public $signature_appearance = ['page' => 1, 'rect' => '0 0 0 0', 'name' => 'Signature'];
    
    /**
     * Link a template to a specific page's resources so it's written to the PDF.
     */
    public function registerTemplateToPage(string $templateId, int $page): void
    {
        if (isset($this->xobjects[$templateId])) {
            $this->xobjects[$templateId]['page'] = $page;
            // Force the page to include this XObject in its resources
            $this->PageResources[$page]['xobjid'][] = $templateId;
        }
    }

    /**
     * Overriding _putsignature to perform a "Buffer Injection".
     * This is much safer than overriding _enddoc.
     */
    protected function _putsignature()
    {
        // 1. If we have a template for the appearance, inject it into the buffer
        if (isset($this->signature_appearance['template_id'])) {
            $templateId = $this->signature_appearance['template_id'];
            if (isset($this->xobjects[$templateId]['n'])) {
                $xobjId = $this->xobjects[$templateId]['n'];
                
                // The signature widget was JUST written to the buffer by _enddoc()
                // We find the signature widget start and inject the /AP stream
                // We use a more flexible regex-like approach to find the widget
                $patterns = [
                    '/Type /Annot /Subtype /Widget',
                    '/Type /Annot/Subtype /Widget',
                    '/Type/Annot/Subtype/Widget'
                ];
                
                foreach ($patterns as $search) {
                    $pos = strrpos($this->buffer, $search);
                    if ($pos !== false) {
                        $this->buffer = substr_replace($this->buffer, $search . ' /AP << /N ' . $xobjId . ' 0 R >>', $pos, strlen($search));
                        break;
                    }
                }
            }
        }

        // 2. Now call the original TCPDF _putsignature to write the actual signature data
        parent::_putsignature();
    }
}
