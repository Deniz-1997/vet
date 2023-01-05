<?php

namespace App\Service\ReportsType;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Entity\Contractor;
use App\Exception\ApiException;

class ReportsCountByTypeReport extends PrintReport
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /**
     * @param $request
     * @param $appKernel
     * @return string
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function createFile($request, $appKernel): string
    {
        $spreadsheet = new Spreadsheet();
        $style = [
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => TRUE,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '000000'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $spreadsheet->getDefaultStyle()->applyFromArray($style);
        $styleLine = array(
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        );
        $headStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => TRUE,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '000000'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $centerAlignment = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $index = 1;
        $sheet->setCellValue("A{$index}", "Наименование отчета");
        $sheet->getCell("A{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("A{$index}")->applyFromArray($headStyle);

        $sheet->setCellValue("B{$index}", "Количество отчетов");
        $sheet->getCell("B{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("B{$index}")->applyFromArray($headStyle);

        $index++;
        $total = 0;
        for ($count = 0; $count < count($request); $count++) {
            $sheet->setCellValue("A{$index}", $request[$count]['name']);
            $sheet->getStyle("A{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("B{$index}", $request[$count]["count"]);
            $sheet->getStyle("B{$index}")->applyFromArray($styleLine);
            $sheet->getStyle("B{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);
            $total+=$request[$count]["count"];
            $index++;
        }
        $sheet->setCellValue("A{$index}", "Итого:");
        $sheet->getCell("A{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("A{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->setCellValue("B{$index}", $total);
        $sheet->getCell("B{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);


        return $this->saveFile($appKernel, $spreadsheet);
    }

    /**
     * @param $data
     * @param EntityManagerInterface $em
     * @return mixed[]
     * @throws \Exception
     */
    function getData($data, EntityManagerInterface $em): array
    {
        $stationIds = [];
        if (array_key_exists('stationsIds', $data) && count($data['stationsIds']) > 0) {
            $stationIds = $data['stationsIds'];
        }
        
        $businessEntityIds = [];
        if (array_key_exists('businessEntityIds', $data) && count($data['businessEntityIds']) > 0) {
            $businessEntityIds = $data['businessEntityIds'];
        }

        $where = '';
        if (count($stationIds)>0 || count($businessEntityIds)>0) {
            $where.=' WHERE ';
            if (count($stationIds)>0) {
                $where.="s.id in (".implode(',', $stationIds).") ";
            }
            if (count($businessEntityIds)>0) {
                $where.= count($stationIds)>0 ? ' AND ' : '';
                $where.="be.id in (" .implode(',', $businessEntityIds).") ";
            }
        }

        $sql = "SELECT r.name, count(r.id) FROM reports.reports_data rd
        JOIN reports.reports r on r.id = rd.reports_id
        JOIN structure.busines_entity be on be.id = rd.businessentity_id
        JOIN reference.reference_station s on s.id = rd.station_id
        ".$where."
        GROUP BY r.name, r.id";
        $types = ['ids' => Connection::PARAM_INT_ARRAY];
        $params = [
        ];

        $result = $em->getConnection()->fetchAllAssociative($sql, $params, $types);

        return $result;
    }
}
