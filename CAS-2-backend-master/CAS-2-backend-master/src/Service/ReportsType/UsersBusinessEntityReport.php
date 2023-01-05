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

class UsersBusinessEntityReport extends PrintReport
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
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->getRowDimension(1)->setRowHeight(18.8);

        $index = 1;
        $sheet->setCellValue("A{$index}", "Хозяйствующий субъект");
        $sheet->getCell("A{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("A{$index}")->applyFromArray($headStyle);

        $sheet->setCellValue("B{$index}", "ИНН");
        $sheet->getCell("B{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("B{$index}")->applyFromArray($headStyle);

        $sheet->setCellValue("C{$index}", "Пользователь");
        $sheet->getCell("C{$index}")->getStyle()->getFont()->setBold(true);
        $sheet->getStyle("C{$index}")->applyFromArray($headStyle);

        $index++;
        for ($count = 0; $count < count($request); $count++) {
            $sheet->setCellValue("A{$index}", $request[$count]['name']);
            $sheet->getStyle("A{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("B{$index}", $request[$count]["inn"]);
            $sheet->getStyle("B{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            $sheet->getStyle("B{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("C{$index}", $request[$count]["user"]);
            $sheet->getStyle("C{$index}")->applyFromArray($styleLine);
            $index++;
        }

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
        $usersIds = [];
        $businessEntityIds = [];
        if (array_key_exists('usersIds', $data) && count($data['usersIds']) > 0) {
            $usersIds = $data['usersIds'];
        }

        if (array_key_exists('businessEntityIds', $data) && count($data['businessEntityIds']) > 0) {
            $businessEntityIds = $data['businessEntityIds'];
        }
        $excludeSupeuser = false;
        if (array_key_exists('excludeSupeuser', $data) && $data['excludeSupeuser']) {
            $excludeSupeuser = $data['excludeSupeuser'];
        }

        $where = '';

        if (count($usersIds)>0 || count($businessEntityIds)>0 || $excludeSupeuser) {
            $where.=' WHERE ';
            if (count($usersIds)>0) {
                $where.="u.id in (".implode(',', $usersIds).") ";
            }
            if (count($businessEntityIds)>0) {
                $where.= count($usersIds)>0 ? ' AND ' : '';
                $where.="be.id in (" .implode(',', $businessEntityIds).") ";
            }
            if ($excludeSupeuser) {
                $where.="g.code <> 'ROLE_ROOT'";
            }
        }

        $sql = "SELECT DISTINCT
                CASE 
                    WHEN be.legal_forms = 'OOO' THEN 'ООО' 
                    WHEN be.legal_forms = 'ZAO' THEN 'ЗАО'  
                    WHEN be.legal_forms = 'AO' THEN 'АО' 
                    WHEN be.legal_forms = 'IP' THEN 'ИП' 
                    ELSE be.legal_forms 
                END 
                || ' ' || be.name AS name, 
                be.inn AS inn, 
                u.surname || ' ' || u.name || ' ' || u.patronymic AS user
                FROM structure.busines_entity be
                JOIN busines_entity_user beu ON beu.busines_entity_id = be.id
                JOIN users u ON beu.user_id = u.id
                JOIN group_user gu on gu.user_id = u.id
                JOIN groups g on g.id = gu.group_id
                ". $where ."
                ORDER BY 1";
        $types = ['ids' => Connection::PARAM_INT_ARRAY];
        $params = [
        ];

        $result = [];

        $result = $em->getConnection()->fetchAllAssociative($sql, $params, $types);

        return $result;
    }
}
