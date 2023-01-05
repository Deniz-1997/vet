<?php

namespace App\Service\ReportsType;

use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Exception\ApiException;

class WarehouseStatement extends PrintReport
{
    /**
     * @var DateTime
     */
    private DateTime $startTime;

    /**
     * @var DateTime
     */
    private DateTime $endTime;

    /**
     * @var string
     */
    private string $stock;

    /**
     * @var string
     */
    private string $product;

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
                'name' => 'Arial',
                'size' => 10,
            ]
        ];
        $spreadsheet->getDefaultStyle()->applyFromArray($style);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(16);
        $sheet->getColumnDimension('C')->setWidth(16);
        foreach (range('D', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(11.5);
        }
        $sheet->getRowDimension(1)->setRowHeight(18);
        for ($i = 2; $i < 10;) {
            $sheet->getRowDimension($i++)->setRowHeight(13);
            $sheet->getRowDimension($i++)->setRowHeight(11.5);
        }
        $sheet->getRowDimension(10)->setRowHeight(36);

        $sheet->mergeCells('A1:B1')->setCellValue('A1', 'ВЕДОМОСТЬ ПО ТОВАРАМ НА СКЛАДАХ');
        $sheet->getCell('A1')->getStyle()->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->getStyle('A3:A7')->getFont()->setSize(8);
        $sheet->setCellValue('A3', 'Начало периода: ' . $this->startTime->format('d.m.Y'));
        $sheet->setCellValue('A5', 'Конец периода: ' . $this->endTime->format('d.m.Y'));
        $sheet->setCellValue('A7', 'Склад: ' . $this->stock);
        if (isset($this->product)) {
            $sheet->mergeCells('C3:D3')->setCellValue('C3', 'Номенклатура: ' . $this->product);
        }
//  -----------------------------------------  Заголовок для таблицы  ----------------------------------------------------
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
        $spreadsheet->getActiveSheet()->getStyle('A10:K11')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->mergeCells('A10:A11')->setCellValue('A10', 'Номенклатура');
        $sheet->mergeCells('B10:B11')->setCellValue('B10', "Единица измерения");
        $sheet->getStyle('B10')->getAlignment()->setWrapText(true);
        $sheet->mergeCells('C10:C11')->setCellValue('C10', 'Цена');
        $sheet->mergeCells('D10:E10')->setCellValue('D10', 'Начальный остаток');
        $sheet->setCellValue('D11', 'Количество');
        $sheet->setCellValue('E11', 'Сумма');
        $sheet->mergeCells('F10:G10')->setCellValue('F10', 'Приход');
        $sheet->setCellValue('F11', 'Количество');
        $sheet->setCellValue('G11', 'Сумма');
        $sheet->mergeCells('H10:I10')->setCellValue('H10', 'Расход');
        $sheet->setCellValue('H11', 'Количество');
        $sheet->setCellValue('I11', 'Сумма');
        $sheet->mergeCells('J10:K10')->setCellValue('J10', 'Конечный остаток');
        $sheet->setCellValue('J11', 'Количество');
        $sheet->setCellValue('K11', 'Сумма');
        $sheet->getStyle('A10:K11')->applyFromArray($styleLine);
//  ----------------------------------------- Заполнение таблицы позициями ------------------------------------------------
        $productAmount = count($request);
        $index = 12;
        for ($iter = 0; $iter < $productAmount; $iter++) {
            $sheet->setCellValue("A{$index}", $request[$iter]['name']);
            $sheet->setCellValue("B{$index}", $request[$iter]['measure']);
            $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT]);
            $sheet->setCellValue("C{$index}", $request[$iter]['price']);
            $sheet->getStyle("C{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $sheet->setCellValue("D{$index}", $request[$iter]['start_balance']);
            $sheet->getStyle("D{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
            $sheet->setCellValue("E{$index}", "=C{$index}*D{$index}");
            $sheet->getStyle("E{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $sheet->setCellValue("F{$index}", $request[$iter]['receipt']);
            $sheet->getStyle("F{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
            $sheet->setCellValue("G{$index}", "=C{$index}*F{$index}");
            $sheet->getStyle("G{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $sheet->setCellValue("H{$index}", $request[$iter]['consumption']);
            $sheet->getStyle("H{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
            $sheet->setCellValue("I{$index}", "=C{$index}*H{$index}");
            $sheet->getStyle("I{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $sheet->setCellValue("J{$index}", $request[$iter]['end_balance']);
            $sheet->getStyle("J{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
            $sheet->setCellValue("K{$index}", "=C{$index}*J{$index}");
            $sheet->getStyle("K{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $index++;
        }
        $sheet->setCellValue("A{$index}", 'Итого');
        $tempIndex = $index - 1;
        $sheet->getStyle("E{$index}:K{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->setCellValue("K{$index}", "=SUM(K12:K{$tempIndex})");
        $sheet->setCellValue("E{$index}", "=SUM(E12:E{$tempIndex})");
        $sheet->setCellValue("G{$index}", "=SUM(G12:G{$tempIndex})");
        $sheet->setCellValue("I{$index}", "=SUM(I12:I{$tempIndex})");

        $sheet->getStyle("A12:K{$index}")->applyFromArray($styleLine);

        $this->stockName = $this->stock;

        return $this->saveFile($appKernel, $spreadsheet);
    }

    /**
     * @param $data
     * @param EntityManagerInterface $em
     * @return mixed[]
     * @throws \Exception
     */
    public function getData($data, EntityManagerInterface $em): array
    {
        $this->startTime = new DateTime($data['startTime']);
        $this->endTime = new DateTime($data['endTime']);
        $now = new DateTime();
        $nowTime = $now->format('Y-m-d H:i:s');
        $startTime = $data['startTime'];
        $endTime = $data['endTime'];

        $stock = $em->getRepository(Stock::class)->findBy(['id' => $data['stockId']]);

        if (empty($stock)) {
            throw new ApiException('Stock with ID = ' . $data['stockId'] . ' does not exist', '', null, 400);
        }

        $this->stock = $stock[0]->getName();

        if (array_key_exists('productId', $data) && !is_null($data['productId'])) {
            if (array_key_exists('id', $data['productId']) && $data['productId']['id']) {
                $getProduct = $em->getRepository(Product::class)->findBy(['id' => $data['productId']['id']]);

                if (empty($getProduct)) {
                    throw new ApiException('Product with ID = ' . $data['productId'] . ' does not exist', '', null, 400);
                }

                $this->product = $getProduct[0]->getName();
            }
        }

//        Достаем список всей продукции на складе
        $sql = 'SELECT p.id, p.name, m.name as measure, p.price, ps.quantity AS current_quantity
                    FROM product.product_stock ps
                    LEFT JOIN product.product p ON ps.product_id = p.id
          LEFT JOIN reference.reference_measurement_units m ON p.measurement_units_id = m.id
                    WHERE ps.stock_id = :stock
                    AND p.payment_object = \'COMMODITY\'';

        $executeData = ['stock' => $data['stockId']];

        if (!empty($this->product)) {
            $sql .= 'AND p.name = :product_name';
            $executeData['product_name'] = $this->product;
        }

        $conn = $em->getConnection();
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery($executeData);
        $result = $fetch->fetchAllAssociative();

        $countResult = count($result);

        if (!$countResult && !empty($this->product)) {
            throw new ApiException($this->product . ' за выбранный период, на складе ' . $this->stock . ' не найден.', '', null, 400);
        }

        for ($count = 0; $count < $countResult; $count++) {
            $result[$count]['receipt'] = 0;
            $result[$count]['consumption'] = 0;
            $result[$count]['start_balance'] = 0;
            $result[$count]['end_balance'] = 0;
        }
//        Получаем Приход и Расход за указанный промежуток
        $sql = 'SELECT product_id,
                    SUM(quantity) FILTER ( WHERE quantity>0 ) AS receipt, SUM(quantity) FILTER ( WHERE quantity < 0) AS consumption
                    FROM document_history AS dh
                    WHERE dh.stock_id = :stock
                    AND dh.date BETWEEN :startTime AND :endTime
                    AND dh.deleted = false
                    GROUP BY product_id';
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery(['stock' => $data['stockId'], 'startTime' => $startTime, 'endTime' => $endTime]);
        $productsBalance = $fetch->fetchAllAssociative();
//        Добавляем Приход\Расход в общую таблицу по товарам
        foreach ($productsBalance as $value) {
            for ($count = 0; $count < count($result); $count++) {
                if ($result[$count]['id'] == $value['product_id']) {
                    if ($value['receipt'] !== null) $result[$count]['receipt'] += $value['receipt'];
                    if ($value['consumption'] !== null) $result[$count]['consumption'] -= $value['consumption'];
                    break;
                }
            }
        }
//        Получаем изменение остатков за промежуток времени с начала указанной даты по текущий момент
        $sql = 'SELECT product_id, SUM(quantity) as balance_diff
                    FROM document_history AS dh
                    WHERE dh.stock_id = :stock
                    AND dh.date BETWEEN :startTime AND :endTime
                    AND dh.deleted = false
                    GROUP BY product_id';
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery(['stock' => $data['stockId'], 'startTime' => $startTime, 'endTime' => $nowTime]);
        $productChange = $fetch->fetchAllAssociative();

        for ($count = 0; $count < count($result); $count++) {
            foreach ($productChange as $item) {
                if ($result[$count]['id'] == $item['product_id']) {
                    $result[$count]['start_balance'] = $item['balance_diff'];
                    break;
                }
            }
            $result[$count]['start_balance'] = $result[$count]['current_quantity'] - $result[$count]['start_balance'];
            $result[$count]['end_balance'] = $result[$count]['start_balance'] + $result[$count]['receipt'] - $result[$count]['consumption'];
        }

        $result = array_filter($result, function ($k) {
            if (isset($k['start_balance']) && isset($k['end_balance'])) {
                return !($k['start_balance'] == 0 && $k['end_balance'] == 0);
            }

            return true;
        });

        if (!count($result)) {
            throw new ApiException("По складу $this->stock за выбранный период $startTime - $endTime не найдены товары", '', null, 400);
        }

        usort($result, $this->alphabetOrderSorter('name'));

        $result = array_values($result);

        return $result;
    }
}
