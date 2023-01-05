<?php

namespace App\Packages\PrintEngine;

use App\Entity\PrintForm;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Class MicrosoftWordPrintEngine
 */
class MicrosoftWordPrintEngine extends AbstractPrintEngine
{
    /**
     * @param PrintForm $printForm
     * @return bool
     */
    public function support(PrintForm $printForm): bool
    {
        return in_array($printForm->getFile()->getMimeType(), [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'application/octet-stream'
        ]);
    }

    /**
     * @param PrintForm $printForm
     * @param array $variables
     * @return string
     */
    public function processTemplate(PrintForm $printForm, array $variables): string
    {
        $templateFileDestination = $this->getTemplateFileDestination($printForm);
        $templateFileDestinationInfo = explode('.', $templateFileDestination);
        $templateFileExtension = array_pop($templateFileDestinationInfo);

        switch ($templateFileExtension) {
            case 'doc':
                $content = $this->readDoc($templateFileDestination);
                break;

            case 'docx':
                $content = $this->readDocx($templateFileDestination);
                break;

            default:
                throw new \RuntimeException('Expected .doc or .docs extension');
        }

        $variables = $this->processExpressions($content, $variables);

        $template = new TemplateProcessor($templateFileDestination);
        foreach ($variables as $variable => $value) {
            $template->setValue($variable, $value);
        }

        $temporaryFileName = $template->save();
        if (!$temporaryFileName || !file_exists($temporaryFileName)) {
            throw new \RuntimeException('Microsoft Word file cannot be processed');
        }

        return file_get_contents($temporaryFileName);
    }

    /**
     * @param string $file
     * @return string|null
     */
    private function readDocx(string $file): ?string
    {
        $content = '';

        $zip = zip_open($file);
        if (!$zip || is_numeric($zip)) {
            return null;
        }

        while ($zipEntry = zip_read($zip))
        {
            if (zip_entry_open($zip, $zipEntry) == false) {
                continue;
            }

            if (zip_entry_name($zipEntry) != 'word/document.xml') {
                continue;
            }

            $content .= zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
            zip_entry_close($zipEntry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', ' ', $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        return strip_tags($content);
    }

    /**
     * @param string $file
     * @return string|string[]|null
     */
    private function readDoc(string $file)
    {
        $fileHandle = fopen($file, 'r');
        $line = @fread($fileHandle, filesize($file));
        $lines = explode(chr(0x0D),$line);

        $outText = '';
        foreach($lines as $line)
        {
            $pos = strpos($line, chr(0x00));
            if (($pos !== false) || (strlen($line) == 0)) {
            } else {
                $outText .= $line.' ';
            }
        }
        $outText = preg_replace('/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/', '', $outText);

        return $outText;
    }
}
