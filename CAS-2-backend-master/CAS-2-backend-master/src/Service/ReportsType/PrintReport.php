<?php

namespace App\Service\ReportsType;

use Closure;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class PrintReport
{
    /**
     * @var string
     */
    protected string $reportType;
    /**
     * @var string
     */
    protected string $stockName;

    public function __construct(string $type)
    {
        $this->reportType = $type;
    }

    abstract function createFile($request, $appKernel);

    abstract function getData($data, EntityManagerInterface $em);

    /**
     * @param $appKernel
     * @param $spreadsheet
     * @return string
     * @throws Exception
     */
    protected function saveFile($appKernel, $spreadsheet): string
    {
        $dateNow = new DateTime();
        $reportName = $this->reportType;
        switch ($this->reportType) {
            case 'usersBusinessEntity':
                $reportName = "Пользователи хозяйствующих субъектов";
                break;
            case 'reportsCount':
                $reportName = "Количество сданных отчетов";
            case 'reportsCountByType':
                $reportName = "Количество сданных отчетов(по видам отчетов)";
                break;
        }

        if (!empty($this->stockName)) {
            $reportName = $reportName . '_' . $this->stockName;
        }
        $fileName = sprintf('%s_%s_%s.xlsx', str_replace(['/'], '_', $reportName), $dateNow->format('d-m-Y'), $dateNow->format('His'));
        $pathToRootDir = $appKernel->getProjectDir() . '/public';
        $pathToDocs = '/docs/xlsx/';
        $pathToFile = sprintf('%s%s%s', $pathToRootDir, $pathToDocs, $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($pathToFile);

        return $fileName;
    }

    /**
     * @param $key
     * @return Closure
     */
    protected function alphabetOrderSorter($key): Closure
    {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }
}
