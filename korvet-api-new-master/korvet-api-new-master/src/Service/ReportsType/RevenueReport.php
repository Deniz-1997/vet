<?php

namespace App\Service\ReportsType;

use App\Entity\Reference\Unit;
use DateInterval;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Entity\Reference\Stock;

class RevenueReport extends PrintReport
{

    /** @var DateTime */
    private DateTime $date;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /**
     * @param $request
     * @param $appKernel
     * @return string
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function createFile($request, $appKernel): string
    {
        $sql = "select id, name from reference.reference_stock where not deleted";
        $data = $this->em->getConnection()->fetchAllAssociative($sql);
        $stocks = array_combine(array_column($data, 'id'), array_column($data, 'name'));
        $stocks[0] = 'Услуги';

        $stocksData = [];
        foreach ($request as $item) {
            $stockId = $item['stock_id'];
            if (!array_key_exists($stockId, $stocksData)) {
                $stocksData[$stockId] = [
                    'name' => $stocks[$stockId],
                    'totalCash' => 0,
                    'totalElectronically' => 0
                ];
            }

            if (strcasecmp($item['payment_type'], 'CASH') == 0) {
                $stocksData[$stockId]['totalCash'] = $item['total_sum'] + (float)$stocksData[$stockId]['totalCash'];
            } elseif (strcasecmp($item['payment_type'], 'ELECTRONICALLY') == 0) {
                $stocksData[$stockId]['totalElectronically'] = $item['total_sum'] + (float)$stocksData[$stockId]['totalElectronically'];
            }
        }

        $spreadsheet = new Spreadsheet();
        $style = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getDefaultStyle()->applyFromArray($style);
        $styleLine = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
                'inside' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $headAlignment = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(44);
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('C')->setWidth(13.5);
        $sheet->getColumnDimension('D')->setWidth(13.1);
        $sheet->getRowDimension(1)->setRowHeight(18.8);

        $sheet->getCell('A1')->getStyle()->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->applyFromArray($headAlignment);
        $sheet->getStyle('B4:D4')->getAlignment()->applyFromArray($headAlignment);
        $sheet->setCellValue('A1', 'Отчет по выручке');
        $sheet->setCellValue('A2', 'за  ' . $this->date->format('d.m.Y'));
        $sheet->setCellValue('A4', 'Наименование');
        $sheet->setCellValue('B4', 'Наличные');
        $sheet->setCellValue('C4', 'Терминал');
        $sheet->setCellValue('D4', 'Всего');
        $position = 5;
        foreach ($stocksData as $item) {
            $sheet->setCellValue("A{$position}", $item['name']);
            $sheet->setCellValue("B{$position}", $item['totalCash']);
            $sheet->setCellValue("C{$position}", $item['totalElectronically']);
            $sheet->setCellValue("D{$position}", "=SUM(B{$position}:C{$position})");
            $position += 1;
        }
        $lastItemPosition = $position - 1;
        $sheet->setCellValue("A{$position}", 'ИТОГО');
        $sheet->setCellValue("B{$position}", "=SUM(B5:B{$lastItemPosition})");
        $sheet->setCellValue("C{$position}", "=SUM(C5:C{$lastItemPosition})");
        $sheet->setCellValue("D{$position}", "=SUM(D5:D{$lastItemPosition})");

        $sheet->getStyle("B5:D{$position}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("B5:D{$position}")->getAlignment()->applyFromArray([
            'horizontal' => Alignment::HORIZONTAL_RIGHT,
            'vertical' => Alignment::VERTICAL_CENTER
        ]);
        $sheet->getStyle("A4:D{$position}")->applyFromArray($styleLine);
        $position += 2;
        $sheet->setCellValue("A{$position}", 'Ответственный__________________________________');
        $position += 2;
        $sheet->setCellValue("A{$position}", 'Согласовано_____________________________________');
        for ($i = 2; $i < $position; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(16);
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
        $this->date = new DateTime($data['date']);
        $this->em = $em;

        if (array_key_exists('stockIds', $data) && count($data['stockIds']) === 1) {
            $stock = $em->getRepository(Stock::class)->findOneBy(['id' => $data['stockIds']]);
            $this->stockName = $stock->getName();
        }

        if (array_key_exists('unitIds', $data) && !empty($data['unitIds'])) {
            $units = $em->getRepository(Unit::class)->findBy(['id' => $data['unitIds']]);
        } else {
            $units = $em->getRepository(Unit::class)->findBy(['deleted' => false]);
        }

        $unitIds = array_map(
            function (Unit $unit) { return $unit->getId(); },
            array_filter($units, function (Unit $unit) { return !$unit->getIsAroundClock(); })
        );
        $aroundTheClockUnitIds = array_map(
            function (Unit $unit) { return $unit->getId(); },
            array_filter($units, function (Unit $unit) { return $unit->getIsAroundClock(); })
        );


        $sql = "SELECT 'appointment' as type, coalesce(api.stock_id, 0) as stock_id, 
                        p.payment_object, 
                        a.payment_type, 
                        sum(api.amount)  as total_sum 
                        FROM appointment.appointment_product_item api 
                INNER JOIN appointment.appointments a ON a.id = api.appointment_id
                INNER JOIN product.product as p ON p.id = api.product_id
                WHERE a.unit_id IN (:ids) and a.deleted = false
                AND a.date_end BETWEEN :start_time AND :end_time
                GROUP BY a.payment_type, api.stock_id, p.payment_object
                UNION 
                SELECT 'shop' as type, coalesce(api.stock_id, 0) as stock_id, 
                        p.payment_object, 
                        s.payment_type, 
                        sum(api.amount)  as total_sum 
                        FROM shop.shop_product_item api 
                INNER JOIN shop.shop_order s ON s.id = api.shop_order_id
                INNER JOIN product.product as p ON p.id = api.product_id
                WHERE s.unit_id IN (:ids) AND s.deleted = false
                AND s.date BETWEEN :start_time AND :end_time
                GROUP BY s.payment_type, api.stock_id, p.payment_object";
        $types = ['ids' => Connection::PARAM_INT_ARRAY];

        $result = [];
        if(count($unitIds) > 0) {
            $params = [
                'ids' => $unitIds,
                'start_time' => $this->date->format('d.m.Y') . ' 00:00:00',
                'end_time' => $this->date->format('d.m.Y') . ' 23:59:59'
            ];

            if(isset($data['debug'])){
                echo json_encode($params);
            }

            $result = $em->getConnection()->fetchAllAssociative($sql, $params, $types);
        }

        $aroundTheClockResult = [];
        if(count($aroundTheClockUnitIds) > 0) {
            $nextDay = clone $this->date;
            $nextDay->add(new DateInterval('P1D'));
            $params = [
                'ids' => $aroundTheClockUnitIds,
                'start_time' => $this->date->format('d.m.Y') . ' 08:00:00',
                'end_time' => $nextDay->format('d.m.Y') . ' 07:59:59'
            ];

            if(isset($data['debug'])){
                echo json_encode($params);
            }

            $aroundTheClockResult = $em->getConnection()->fetchAllAssociative($sql, $params, $types);
        }

        return array_merge($result, $aroundTheClockResult);
    }

}
