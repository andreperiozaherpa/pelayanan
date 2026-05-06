<?php

namespace App\Services\Tte;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LSNepomuceno\LaravelA1PdfSign\Exceptions\FileNotFoundException;
use LSNepomuceno\LaravelA1PdfSign\Exceptions\InvalidPdfSignModeTypeException;
use LSNepomuceno\LaravelA1PdfSign\Sign\ManageCert;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ProfessionalSignaturePdf
{
    const MODE_DOWNLOAD = 'MODE_DOWNLOAD';

    const MODE_RESOURCE = 'MODE_RESOURCE';

    private CustomFpdi $pdf;

    private ManageCert $cert;

    private string $pdfPath;

    private string $mode;

    private string $fileName;

    private ?array $image = null;

    private array $info = [];

    private bool $hasSignedSuffix;

    private bool $hasSealImgOnEveryPages;

    public function __construct(
        string $pdfPath,
        ManageCert $cert,
        string $mode = self::MODE_RESOURCE,
        string $fileName = '',
        bool $hasSignedSuffix = true
    ) {
        if (! File::exists($pdfPath)) {
            throw new FileNotFoundException($pdfPath);
        }
        if (! in_array($mode, [self::MODE_RESOURCE, self::MODE_DOWNLOAD])) {
            throw new InvalidPdfSignModeTypeException($mode);
        }

        $this->cert = $cert;

        try {
            $this->cert->validate();
        } catch (Throwable $th) {
            throw $th;
        }

        $this->setFileName($fileName)
            ->setHasSignedSuffix($hasSignedSuffix)
            ->setSealImgOnEveryPages(false);

        $this->mode = $mode;
        $this->pdfPath = $pdfPath;
        $this->setPdf();
    }

    public function setInfo(
        ?string $name = null,
        ?string $location = null,
        ?string $reason = null,
        ?string $contactInfo = null
    ): self {
        $info = [];
        $name && ($info['Name'] = $name);
        $location && ($info['Location'] = $location);
        $reason && ($info['Reason'] = $reason);
        $contactInfo && ($info['ContactInfo'] = $contactInfo);
        $this->info = $info;

        return $this;
    }

    public function getPdfInstance(): CustomFpdi
    {
        return $this->pdf;
    }

    public function setPdf(
        string $orientation = 'P',
        string $unit = 'mm',
        string $pageFormat = 'A4',
        bool $unicode = true,
        string $encoding = 'UTF-8'
    ): self {
        $this->pdf = new CustomFpdi($orientation, $unit, $pageFormat, $unicode, $encoding);

        return $this;
    }

    public function setImage(
        string $imagePath,
        float $pageX = 155,
        float $pageY = 250,
        float $imageW = 50,
        float $imageH = 0,
        int $page = -1
    ): self {
        $this->image = compact('imagePath', 'pageX', 'pageY', 'imageW', 'imageH', 'page');

        return $this;
    }

    public function setSealImgOnEveryPages(bool $hasSealImgOnEveryPages = true): self
    {
        $this->hasSealImgOnEveryPages = $hasSealImgOnEveryPages;

        return $this;
    }

    public function setFileName(string $fileName): self
    {
        $ext = explode('.', $fileName);
        $ext = end($ext);
        $this->fileName = str_replace(".{$ext}", '', $fileName);

        return $this;
    }

    public function setHasSignedSuffix(bool $hasSignedSuffix): self
    {
        $this->hasSignedSuffix = $hasSignedSuffix;

        return $this;
    }

    private function implementSignatureImage(?int $currentPage = null): void
    {
        if ($this->image) {
            extract($this->image);

            // --- PROFESSIONAL TTE LINKING ---
            // 1. Create a template for the image
            $h = $imageH > 0 ? $imageH : $imageW;
            $templateId = $this->pdf->startTemplate($imageW, $h);
            $this->pdf->Image($imagePath, 0, 0, $imageW, $h, 'PNG');
            $this->pdf->endTemplate();

            // Link the template to the current page's resources so it's written to the PDF
            // but NOT drawn on the page content. This ensures it gets an Object ID.
            $this->pdf->registerTemplateToPage($templateId, $currentPage ?? $page);

            // 2. Set signature appearance
            $this->pdf->setSignatureAppearance($pageX, $pageY, $imageW, $h, $currentPage ?? $page);

            // 3. Inject the template ID into the public signature_appearance array
            // Our CustomFpdi class will use this in _enddoc() to link the /AP stream.
            $this->pdf->signature_appearance['template_id'] = $templateId;
            // --------------------------------
        }
    }

    public function signature(): string|BinaryFileResponse
    {
        $pageCount = $this->pdf->setSourceFile($this->pdfPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $pageIndex = $this->pdf->importPage($i);
            $this->pdf->SetPrintHeader(false);
            $this->pdf->SetPrintFooter(false);

            $templateSize = $this->pdf->getTemplateSize($pageIndex);
            ['width' => $width, 'height' => $height] = $templateSize;

            $this->pdf->AddPage($width > $height ? 'L' : 'P', [$width, $height]);
            $this->pdf->useTemplate($pageIndex);

            $insertImageOnLastPage = ! empty($this->image['page']) && $this->image['page'] === -1 && $i === $pageCount;
            if (
                $this->hasSealImgOnEveryPages ||
                $i === ($this->image['page'] ?? 0) ||
                $insertImageOnLastPage
            ) {
                $this->implementSignatureImage($i);
            }
        }

        $certificate = $this->cert->getCert()->original;
        $password = $this->cert->getCert()->password;

        $this->pdf->setSignature($certificate, $certificate, $password, '', 3, $this->info, 'A');

        if (empty($this->fileName)) {
            $this->fileName = Str::orderedUuid();
        }
        if ($this->hasSignedSuffix) {
            $this->fileName .= '_signed';
        }

        $this->fileName .= '.pdf';

        $output = "{$this->cert->getTempDir()}{$this->fileName}";

        if (! File::exists($output)) {
            File::put($output, $this->pdf->output($this->fileName, 'S'));
        }

        switch ($this->mode) {
            case self::MODE_RESOURCE:
                $content = File::get($output);
                File::delete([$output]);

                return $content;

            case self::MODE_DOWNLOAD:
            default:
                return response()->download($output)->deleteFileAfterSend();
        }
    }
}
