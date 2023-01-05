<?php

namespace App\Service\Export;

use App\Entity\FtpHistory;
use App\Enum\FtpHistoryTypeEnum;
use App\Model\Env;

class Export
{
    /**
     * @param array $data
     * @param string $filePath
     * @param string $fileName
     */
    public function prepareCsvFile(array $data, string $filePath, string $fileName)
    {
        $path = $filePath . '/' . $fileName;
        if (!file_exists($filePath)) {
            mkdir($filePath, 0775, true);
        }
        $handle = fopen($path, 'w');
        //add headers to csv-file
        fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM

        //add content to csv-file
        foreach ($data as $fields) {
            fputcsv($handle, $fields, ';');
        }
        fclose($handle);
    }

    /**
     *
     * @param $file
     * @return bool
     */
    public function uploadCsvToFtp($file)
    {
        $uploadStatus = false;
        $ftp_conn = null;
        $ftp_conn = ftp_connect(Env::getenv('FTP_1C_SERVER'));
        $login = ftp_login($ftp_conn, Env::getenv('FTP_1C_USERNAME'), Env::getenv('FTP_1C_PASSWORD'));

        if (ftp_put($ftp_conn, basename($file), $file, FTP_ASCII)) {
            $uploadStatus = true;
        }
        ftp_close($ftp_conn);

        return $uploadStatus;
    }

    /**
     * @param string $file
     * @param bool $uploadToFtpStatus
     * @param array $exportData
     * @return FtpHistory
     * @throws \Exception
     */
    public function prepareFtpHistory(string $file, bool $uploadToFtpStatus, array $exportData)
    {
        $report = count($exportData) -1;
        $ftpHistory = new FtpHistory();
        $ftpHistory->setFileName($file);
        $ftpHistory->setDate(new \DateTime());
        $ftpHistory->setImported($uploadToFtpStatus);
        $ftpHistory->setOperationType(FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::EXPORT));
        $ftpHistory->setReport(['total' => $report]);

        return $ftpHistory;
    }
}
