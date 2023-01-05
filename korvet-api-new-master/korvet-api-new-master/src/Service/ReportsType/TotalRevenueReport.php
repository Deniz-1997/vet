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
use App\Exception\ApiException;

class TotalRevenueReport extends PrintReport
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var DateTime */
    private DateTime $startDate;
    /** @var DateTime */
    private DateTime $endDate;

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
                'size' => 12,
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
                'size' => 12,
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
        $rightAlignment = ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER];

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(100);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getRowDimension(1)->setRowHeight(18.8);

        $unitId = 0;
        $index = 0;
        $totalCash = 0;
        $totalTerminal = 0;
        $totalCount = 0;
        $total = count($request);
        for ($count = 0; $count < $total; $count++) {
            if ($unitId !== $request[$count]["unit_id"]) {
                $index++;
                $totalCash = 0;
                $totalTerminal = 0;
                $totalCount = 0;
                $sheet->mergeCells("A{$index}:D{$index}")->setCellValue("A{$index}", "Расшифровка по выручке {$request[$count]['unit_name']}");
                $sheet->getCell("A{$index}")->getStyle()->getFont()->setBold(true);
                $sheet->getCell("A{$index}")->getStyle()->getFont()->setSize(14);
                $sheet->getStyle("A{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $index++;
                $sheet->mergeCells("A{$index}:D{$index}")->setCellValue("A{$index}", "за  период с {$this->startDate->format('d.m.Y')} по {$this->endDate->format('d.m.Y')}");
                $sheet->getStyle("A{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $index++;

                $sheet->setCellValue("A{$index}", "Наименование");
                $sheet->getStyle("A{$index}")->applyFromArray($headStyle);
                $sheet->getCell("A{$index}")->getStyle()->getFont()->setBold(true);

                $sheet->setCellValue("B{$index}", "Кол-во");
                $sheet->getStyle("B{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->getCell("B{$index}")->getStyle()->getFont()->setBold(true);

                $sheet->setCellValue("C{$index}", "Наличные");
                $sheet->getStyle("C{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("C{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->getCell("C{$index}")->getStyle()->getFont()->setBold(true);

                $sheet->setCellValue("D{$index}", "Терминал");
                $sheet->getStyle("D{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("D{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->getCell("D{$index}")->getStyle()->getFont()->setBold(true);
                $index++;
                $unitId = $request[$count]["unit_id"];
            }
            if ($request[$count]["payment_object"] === 'COMMODITY') {
                $sheet->setCellValue("A{$index}", $request[$count]["stock_name"]);
                if ($count > 0 && $request[$count]["stock_name"] === $request[$count - 1]["stock_name"] 
                    && $request[$count]["unit_id"] === $request[$count - 1]["unit_id"]) {
                    $index--;
                }
            }
            if ($request[$count]["payment_object"] === 'SERVICE' || $request[$count]["payment_object"] === 'CATEGORY') {
                $sheet->setCellValue("A{$index}", $request[$count]["name"]);
                if ($count > 0 && $request[$count]["name"] === $request[$count - 1]["name"]
                    && $request[$count]["unit_id"] === $request[$count - 1]["unit_id"]) {
                    $index--;
                }
            }
            if ($request[$count]["payment_type"] === 'ELECTRONICALLY') {
                $sheet->setCellValue("D{$index}", $request[$count]["sum"]);
                $totalTerminal += $request[$count]["sum"];
            } else {
                $sheet->setCellValue("C{$index}", $request[$count]["sum"]);
                $totalCash += $request[$count]["sum"];
            }
          
            $sheet->setCellValue("B{$index}", $request[$count]["count"] + $sheet->getCell("B{$index}")->getValue());
            $totalCount += $request[$count]["count"];

            $sheet->getStyle("A{$index}")->applyFromArray($styleLine);
            $sheet->getStyle("B{$index}")->applyFromArray($styleLine);
            $sheet->getStyle("C{$index}")->applyFromArray($styleLine);
            $sheet->getStyle("D{$index}")->applyFromArray($styleLine);
            $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray($rightAlignment);
            $sheet->getStyle("C{$index}")->getAlignment()->applyFromArray($rightAlignment);
            $sheet->getStyle("D{$index}")->getAlignment()->applyFromArray($rightAlignment);

            $index++;

            if ($count == $total - 1 || $unitId !== $request[$count + 1]["unit_id"]) {
                $sheet->setCellValue("A{$index}", "Выручка всего");
                $sheet->getStyle("A{$index}")->applyFromArray($headStyle);
                $sheet->getCell("A{$index}")->getStyle()->getFont()->setBold(true);
                $sheet->setCellValue("B{$index}", $totalCount);
                $sheet->getStyle("B{$index}")->applyFromArray($headStyle);
                $sheet->getCell("B{$index}")->getStyle()->getFont()->setBold(true);
                $sheet->setCellValue("C{$index}", $totalCash);
                $sheet->getStyle("C{$index}")->applyFromArray($headStyle);
                $sheet->getCell("C{$index}")->getStyle()->getFont()->setBold(true);
                $sheet->setCellValue("D{$index}", $totalTerminal);
                $sheet->getStyle("D{$index}")->applyFromArray($headStyle);
                $sheet->getCell("D{$index}")->getStyle()->getFont()->setBold(true);
                $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray($rightAlignment);
                $sheet->getStyle("C{$index}")->getAlignment()->applyFromArray($rightAlignment);
                $sheet->getStyle("D{$index}")->getAlignment()->applyFromArray($rightAlignment);

                $index += 2;
                $sheet->setCellValue("A{$index}", "Ответственный__________________________________");

                $index += 2;
                $sheet->setCellValue("A{$index}", "Согласовано____________________________________");

                $index += 3;
            }
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
        $categoriesId = [];
        $categoriesIdString = null;
        $productsId = [];
        $productsIdString = null;
        $unitId = null;
        $this->startDate = new DateTime();
        $this->endDate = new DateTime();
        if (array_key_exists('categoriesId', $data) && count($data['categoriesId']) > 0) {
            $categoriesId = $data['categoriesId'];
            $categoriesIdString = implode(",", $categoriesId);
        }
        if (array_key_exists('productsId', $data)) {
            $productsId = $data['productsId'];
            $productsIdString = implode(",", $productsId);
        }
        if (array_key_exists('unitId', $data)) {
            $unitId = $data['unitId'];
        }

        if (array_key_exists('startDate', $data)) {
            $this->startDate = new DateTime($data['startDate']);
        }
        if (array_key_exists('endDate', $data)) {
            $this->endDate = new DateTime($data['endDate']);
        }

        $sql = "SELECT * FROM 
        /* Товары по магазину и приемам */
            (SELECT commodity.unit_id,
                    commodity.stock_id,
                    commodity.payment_object,
                    '' as name,
                    commodity.payment_type,
                    sum(commodity.total_sum),
                    sum(commodity.total_count) as count,
                    commodity.unit_name,
                    commodity.stock_name
             FROM
                 (SELECT u.id as unit_id,
                         coalesce(api.stock_id, 0) as stock_id,
                         p.payment_object,
                         a.payment_type,
                         sum(api.amount) as total_sum,
                         sum(api.quantity) as total_count,
                         u.name as unit_name,
                         st.name as stock_name
                  FROM appointment.appointment_product_item api
                  INNER JOIN appointment.appointments a ON a.id = api.appointment_id
                  INNER JOIN product.product as p ON p.id = api.product_id
                  INNER JOIN unit u on u.id = a.unit_id
                  INNER JOIN reference.reference_stock st on st.id = api.stock_id
                  WHERE p.payment_object= 'COMMODITY'
                      AND a.deleted = false
                      AND a.date_end BETWEEN :start_date AND :end_date
                      " . (isset($unitId) ? " AND u.id = {$unitId} " : "") . "
                  GROUP BY u.id,
                           api.stock_id,
                           p.payment_object,
                           a.payment_type,
                           u.name,
                           st.name 
                           /* По магазину */
                  UNION SELECT u.id as unit_id,
                               coalesce(api.stock_id, 0) as stock_id,
                               p.payment_object,
                               s.payment_type,
                               sum(api.amount) as total_sum,
                               sum(api.quantity) as total_count,
                               u.name as unit_name,
                               st.name as stock_name
                  FROM shop.shop_product_item api
                  INNER JOIN shop.shop_order s ON s.id = api.shop_order_id
                  INNER JOIN product.product as p ON p.id = api.product_id
                  INNER JOIN unit u on u.id = s.unit_id
                  INNER JOIN reference.reference_stock st on st.id = api.stock_id
                  WHERE p.payment_object= 'COMMODITY'
                      AND s.deleted = false
                      AND s.date BETWEEN :start_date AND :end_date
                      " . (isset($unitId) ? " AND u.id = {$unitId} " : "") . "
                  GROUP BY u.id,
                           api.stock_id,
                           p.payment_object,
                           s.payment_type,
                           u.name,
                           st.name) commodity
             GROUP BY commodity.unit_id,
                      commodity.stock_id,
                      commodity.payment_object,
                      commodity.payment_type,
                      commodity.unit_name,
                      commodity.stock_name 
                /* Категории услуг */
             UNION SELECT category.unit_id,
                          category.stock_id,
                          category.payment_object,
                          category.name,
                          category.payment_type,
                          sum(category.total_sum),
                          sum(category.total_count) as count,
                          category.unit_name,
                          category.stock_name
             FROM
                 (SELECT us.id as unit_id,
                         coalesce(api.stock_id, 0) as stock_id,
                         'CATEGORY' as payment_object,
        
                      (WITH RECURSIVE categories (id, parent_id, name) as
                           (SELECT t1.id,
                                   t1.parent_id,
                                   t1.name
                            FROM reference.reference_category_nomenclature t1
                            WHERE t1.id = p.category_nomenclature_id
                            UNION SELECT t2.id,
                                         t2.parent_id,
                                         t2.name
                            FROM reference.reference_category_nomenclature t2
                            INNER JOIN categories on (categories.parent_id = t2.id)) select categories.name
                       FROM categories
                       WHERE categories.parent_id IS NULL) as name,
                         a.payment_type,
                         sum(api.amount) as total_sum,
                         sum(api.quantity) as total_count,
                         us.name as unit_name,
                         '' as stock_name
                  FROM appointment.appointment_product_item api
                  INNER JOIN appointment.appointments a ON a.id = api.appointment_id
                  INNER JOIN unit us on us.id = a.unit_id
                  INNER JOIN product.product as p ON p.id = api.product_id
                  INNER JOIN reference.reference_category_nomenclature as cn ON cn.id = p.category_nomenclature_id
                  WHERE p.payment_object= 'SERVICE'
                      AND a.deleted = false
                      AND a.date_end BETWEEN :start_date AND :end_date
                      " . (isset($unitId) ? " AND us.id = {$unitId} " : "") . "
                      AND p.category_nomenclature_id in
                          (WITH RECURSIVE categories (id, parent_id, name) as
                               (SELECT t1.id,
                                       t1.parent_id,
                                       t1.name
                                FROM reference.reference_category_nomenclature t1
                                WHERE 
                                " . (count($categoriesId) > 0 ? " t1.id in ({$categoriesIdString}) " : " t1.parent_id is NULL ") . "
                                UNION SELECT t2.id,
                                             t2.parent_id,
                                             t2.name
                                FROM reference.reference_category_nomenclature t2
                                INNER JOIN categories on (categories.id = t2.parent_id)) SELECT categories.id
                           FROM categories)
                  GROUP BY us.id,
                           api.stock_id,
                           p.payment_object,
                           cn.name,
                           a.payment_type,
                           us.name,
                           p.category_nomenclature_id) category
             GROUP BY category.unit_id,
                      category.stock_id,
                      category.payment_object,
                      category.name,
                      category.payment_type,
                      category.unit_name,
                      category.stock_name
                      /* Отдельные услуги */
             UNION SELECT us.id as unit_id,
                          coalesce(api.stock_id, 0) as stock_id,
                          p.payment_object,
                          p.name as service_name,
                          a.payment_type,
                          sum(api.amount) as total_sum,
                          sum(api.quantity) as total_count,
                          us.name as unit_name,
                          '' as stock_name
             FROM appointment.appointment_product_item api
             INNER JOIN appointment.appointments a ON a.id = api.appointment_id
             INNER JOIN unit us on us.id = a.unit_id
             INNER JOIN product.product as p ON p.id = api.product_id
             WHERE p.payment_object= 'SERVICE'
                 AND a.deleted = false
                 AND " . (count($productsId) > 0 ? " p.id IN ({$productsIdString}) " : " p.category_nomenclature_id IS NULL ") . "
                 AND a.date_end BETWEEN :start_date AND :end_date
                 " . (isset($unitId) ? " AND us.id = {$unitId} " : "") . "
             GROUP BY us.id,
                      api.stock_id,
                      p.payment_object,
                      p.name,
                      a.payment_type,
                      us.name) b
        ORDER BY 1, 2 DESC, 3, 4;";
        $types = ['ids' => Connection::PARAM_INT_ARRAY];
        $params = [
            'start_date' => $this->startDate->format('Y-m-d') . ' 00:00:00',
            'end_date' => $this->endDate->format('Y-m-d') . ' 23:59:59'
        ];

        $result = [];

        $result = $em->getConnection()->fetchAllAssociative($sql, $params, $types);

        if (!count($result)) {
            throw new ApiException("За выбранный период с " . $this->startDate->format('d.m.Y') . " по " . $this->endDate->format('d.m.Y') . " данные не найдены", 'TOTAL_REPORT_EMPTY', null, 400);
        }

        return $result;
    }
}
