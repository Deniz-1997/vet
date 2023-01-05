<?php


namespace App\Service\ReportsType;

use App\Entity\ProductStock;
use App\Entity\Reference\Stock;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Statement;
use Doctrine\Migrations\Version\State;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ShiftReport extends PrintReport
{
    /** @var string */
    private string $stock = '';

    /** @var DateTime */
    private DateTime $date;

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
        $dateNow = new DateTime();
        $style = [
            'font' => [
                'name' => 'Arial',
                'size' => 8,
            ],
//            'alignment' => [
//                'horizontal' => Alignment::HORIZONTAL_RIGHT,
//            ],
        ];
        $spreadsheet->getDefaultStyle()->applyFromArray($style);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getRowDimension(1)->setRowHeight(8);
        $sheet->getRowDimension(2)->setRowHeight(8);
        $sheet->getRowDimension(3)->setRowHeight(8);
        for ($i = 4; $i <= 16; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
        $sheet->getRowDimension(17)->setRowHeight(11.5);
        $sheet->getRowDimension(18)->setRowHeight(22);
        foreach (range('A', 'V') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(10.5);
        }
        $sheet->getColumnDimension('I')->setWidth(0.01);
        $sheet->getColumnDimension('M')->setWidth(0.01);
        $sheet->getColumnDimension('P')->setWidth(0.01);
        $sheet->getColumnDimension('R')->setWidth(10.8);
        $sheet->getColumnDimension('H')->setWidth(0.01);
        $sheet->getColumnDimension('T')->setWidth(0.01);
        $sheet->getColumnDimension('L')->setWidth(10.8);
        $sheet->getColumnDimension('O')->setWidth(10.8);
        $sheet->getColumnDimension('D')->setWidth(14.5);
        $sheet->getColumnDimension('A')->setWidth(11.2);

        $sheet->mergeCells("A4:N5");
        $sheet->mergeCells("O4:V5")->setCellValue("O4", "    Типовая межотраслевая форма № СП-37 Утвержденная постановление Госкомстата России от 29.09.97 № 68");
        $sheet->getStyle('O4')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_BOTTOM]);
        $sheet->getStyle('O4')->getAlignment()->setWrapText(true);
        $sheet->mergeCells("A8:O8")->setCellValue("A8", "ОТЧЕТ № ___");
        $sheet->mergeCells("A9:O9")->setCellValue("A9", "о реализации продукции");
        $sheet->mergeCells("A10:O10")->setCellValue("A10", "за  " . $this->date->format('d.m.Y'));
        $sheet->getStyle('A8:A10')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->getCell('A8')->getStyle()->getFont()->setBold(true);
        $sheet->getCell('A9')->getStyle()->getFont()->setBold(true);
        $sheet->mergeCells("Q10:S10")->setCellValue("Q10", "Форма по ОКУД");
        $sheet->getStyle('Q10')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);

        $sheet->mergeCells("A11:S11")->setCellValue("A11", "Дата составления");
        $sheet->getStyle('A11')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->mergeCells("A12:Q12")->setCellValue("A12", 'Организация         Государственное бюджетное учреждение ветеринарии Московской области "Территориальное ветеринарное управление №5"');
        $sheet->mergeCells("A13:Q13")->setCellValue("A13", "Подразделение  " . $this->stock);
        $sheet->mergeCells("A14:Q14")->setCellValue("A14", "Отправитель");
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
        $sheet->getStyle('A14:Q14')->applyFromArray($styleLine);

        $sheet->setCellValue("S12", 'по ОКПО')->getStyle('S12')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_BOTTOM]);
        $sheet->mergeCells("U9:V9")->setCellValue("U9", "Коды");
        $sheet->mergeCells("U10:V10")->setCellValue("U10", "0325037");
        $sheet->getStyle('U9:U10')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
//  ------------------------------------  Движение продукции -----------------------------------------------------
        $sheet->mergeCells("A16:D16")->setCellValue('A16', "I. Движение продукции");
        $sheet->getCell('A16')->getStyle()->getFont()->setBold(true);
        $sheet->mergeCells("A17:D18")->setCellValue('A17', "Наименование продукции");
        $sheet->getStyle("A17:V18")->applyFromArray($styleLine);
        $sheet->mergeCells("E17:E18")->setCellValue('E17', "Единица измерения");
        $sheet->mergeCells("F17:H18")->setCellValue('F17', "Реализационная цена, руб. коп");
        $sheet->mergeCells("J17:K18")->setCellValue('J17', "Остаток на начало рабочего дня (кол-во)");
        $sheet->mergeCells("L17:N17")->setCellValue('L17', "Поступление");
        $sheet->mergeCells("O17:Q17")->setCellValue('O17', "Продано");

        $sheet->setCellValue('L18', "количество");
        $sheet->setCellValue('N18', "сумма, руб. коп.");
        $sheet->setCellValue('O18', "количество");
        $sheet->setCellValue('Q18', "сумма, руб. коп.");
        $sheet->mergeCells("R17:R18")->setCellValue('R17', "Тип оплаты");
        $sheet->mergeCells("S17:S18")->setCellValue('S17', "Прочий расход");
        $sheet->mergeCells("U17:V18")->setCellValue('U17', "Остаток на конец рабочего дня (кол-во)");

        $sheet->getStyle('A16:V18')->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->getStyle('A17:V18')->getAlignment()->setWrapText(true);
//------------------------------------------  Заполнение движения продукции  -----------------------------------------------------------
        $numberOfProduct = count($request);
        $index = 19;
        $summQuantity = 0;
        $summQuantityCash = 0;
        $summQuantityElectronically = 0;
        $summAmount = 0;
        $summAmountCash = 0;
        $summAmountElectronically = 0;
        $balance = 0;

        for ($count = 0; $count < $numberOfProduct; $count++) {
            $index = 19 + $count;
            if (($count > 0 && $request[$count]['name'] != $request[$count - 1]['name']) || $count == 0) {
                $corectIndex = $index;
                if ($count + 1 < $numberOfProduct && $request[$count]['name'] == $request[$count + 1]['name']) {
                    $corectIndex += 1;
                }
                $sheet->mergeCells("A{$index}:D{$corectIndex}")->setCellValue("A{$index}", $request[$count]['name'])
                    ->getStyle("A{$index}")->getAlignment()->applyFromArray(['vertical' => Alignment::VERTICAL_CENTER]);
                $sheet->mergeCells("E{$index}:E{$corectIndex}")->setCellValue("E{$index}", $request[$count]['measure']);
                $sheet->mergeCells("F{$index}:H{$corectIndex}")->setCellValue("F{$index}", $request[$count]['price'])
                    ->getStyle("F{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $sheet->mergeCells("J{$index}:K{$corectIndex}")->setCellValue("J{$index}", $request[$count]['startBalance'])
                    ->getStyle("J{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
                $sheet->mergeCells("L{$index}:L{$corectIndex}")->setCellValue("L{$index}", $request[$count]['receipt'])->getStyle("L{$index}")
                    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
                $sheet->mergeCells("N{$index}:N{$corectIndex}")->setCellValue("N{$index}", "=L{$index}*{$request[$count]['price']}")->getStyle("N{$index}")
                    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->mergeCells("S{$index}:S{$corectIndex}")->setCellValue("S{$index}", $request[$count]['consumption'])->getStyle("S{$index}")
                    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
                $sheet->mergeCells("U{$index}:V{$corectIndex}")->setCellValue("U{$index}", $request[$count]['endBalance'])
                    ->getStyle("U{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
            }

            $sheet->setCellValue("O{$index}", $request[$count]['sell_quantity'])->getStyle("O{$index}")
                ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
            $sheet->setCellValue("Q{$index}", $request[$count]['total_amount'])->getStyle("Q{$index}")
                ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            if (isset($request[$count]['payment_type'])) {
                switch ($request[$count]['payment_type']) {
                    case 'CASH':
                        $sheet->setCellValue("R{$index}", 'наличный');
                        $summQuantityCash += $request[$count]['sell_quantity'];
                        $summAmountCash += $request[$count]['total_amount'];
                        break;
                    case 'ELECTRONICALLY':
                        $sheet->setCellValue("R{$index}", 'б/наличный');
                        $summQuantityElectronically += $request[$count]['sell_quantity'];
                        $summAmountElectronically += $request[$count]['total_amount'];
                        break;
                }
            }

            $summAmount += $request[$count]['total_amount'];
            $balance += $request[$count]['endBalance'];
            $summQuantity += $request[$count]['sell_quantity'];
            $sheet->getRowDimension("{$index}")->setRowHeight(11.5);
        }
        $sheet->getStyle("A19:V{$index}")->applyFromArray($styleLine);
        $last = $index;
        $index++;
        $sheet->getRowDimension("{$index}")->setRowHeight(11.5);
        $sheet->getStyle("E19:V{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->setCellValue("A{$index}", 'Итого');
        $sheet->mergeCells("J{$index}:K{$index}")->setCellValue("J{$index}", "=SUM(J19:J{$last})")
            ->getStyle("J{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
        $sheet->setCellValue("L{$index}", "=SUM(L19:L{$last})")
            ->getStyle("L{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
        $sheet->setCellValue("N{$index}", "=SUM(N19:N{$last})")
            ->getStyle("N{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->setCellValue("O{$index}", "=SUM(O19:O{$last})")
            ->getStyle("O{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
        $sheet->setCellValue("Q{$index}", "=SUM(Q19:Q{$last})")
            ->getStyle("Q{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->setCellValue("S{$index}", "=SUM(S19:S{$last})")
            ->getStyle("S{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
        $sheet->mergeCells("U{$index}:V{$index}")->setCellValue("U{$index}", "=SUM(U19:U{$last})")
            ->getStyle("U{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);

        $sheet->getStyle("J{$index}:V{$index}")->applyFromArray($styleLine);
//-------------------------------------Итого нал/безнал--------------------------------------------------
        $index++;
        $sheet->getRowDimension("{$index}")->setRowHeight(11.5);
        $sheet->getStyle("E19:V{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->setCellValue("A{$index}", 'Итого наличная оплата');
        $sheet->setCellValue("O{$index}", $summQuantityCash)
            ->getStyle("O{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
        $sheet->setCellValue("Q{$index}", $summAmountCash)
            ->getStyle("Q{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("O{$index}:Q{$index}")->applyFromArray($styleLine);

        $index++;
        $sheet->getRowDimension("{$index}")->setRowHeight(11.5);
        $sheet->getStyle("E19:V{$index}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->setCellValue("A{$index}", 'Итого безналичная оплата');
        $sheet->setCellValue("O{$index}", $summQuantityElectronically)
            ->getStyle("O{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
        $sheet->setCellValue("Q{$index}", $summAmountElectronically)
            ->getStyle("Q{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("O{$index}:Q{$index}")->applyFromArray($styleLine);

//------------------------------------------------ Движение выручки -------------------------------------------------------------
        for ($rowNumber = ++$index; $rowNumber <= $index + 15; $rowNumber++) {
            $sheet->mergeCells("A{$rowNumber}:H{$rowNumber}");
            $sheet->mergeCells("J{$rowNumber}:N{$rowNumber}");
            $sheet->mergeCells("O{$rowNumber}:V{$rowNumber}");
            $sheet->getRowDimension($rowNumber)->setRowHeight(20);
        }
        $index++;
        $temp = $index + 1;
        $sheet->getStyle("A{$index}:O{$temp}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->setCellValue("A{$index}", "II. Движение выручки");
        $sheet->getStyle("A{$index}")->getFont()->setSize(10)->setBold(true);
        $index++;
        $styleDoubleLine = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $temp = $index + 13;
        $sheet->getStyle("A{$index}:V{$index}")->applyFromArray($styleDoubleLine);
        $sheet->getStyle("A{$index}:H{$temp}")->applyFromArray($styleDoubleLine);
        $sheet->getStyle("H{$index}:N{$temp}")->applyFromArray($styleDoubleLine);
        $sheet->getStyle("N{$index}:V{$temp}")->applyFromArray($styleDoubleLine);
        $sheet->setCellValue("A{$index}", 'Показатели')->getStyle("A{$index}")->getFont()->setSize(10);
        $sheet->setCellValue("J{$index}", 'Сумма, руб. коп.')->getStyle("J{$index}")->getFont()->setSize(10);
        $sheet->setCellValue("O{$index}", 'Наименование и номер документов')->getStyle("O{$index}")->getFont()->setSize(10);
        $index++;
        $sheet->getStyle("J{$index}:O{$temp}")->getAlignment()->applyFromArray(['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]);
        $sheet->setCellValue("A{$index}", "Остаток на                    " . $dateNow->format('d.m.Y'));
        $sheet->setCellValue("J{$index}", '0')->getStyle("J{$index}")->getFont()->setSize(10);
        $index++;
        $sheet->setCellValue("A{$index}", 'Выручка от релизации        ' . $summAmount . ' р.')->getStyle("A{$index}")->getFont()->setSize(10);
        $sheet->setCellValue("J{$index}", $summAmount . ' р.')->getStyle("J{$index}")->getFont()->setSize(10);
        $index++;
        $sheet->setCellValue("A{$index}", "Всего с остатком                      " . $summAmount . ' р.');
        $sheet->setCellValue("J{$index}", $summAmount . ' р.');
        $index++;
        $sheet->setCellValue("A{$index}", 'Использованная выручка          ' . $summAmount . ' р.');
        $sheet->setCellValue("J{$index}", $summAmount . ' р.');
        $index += 4;
        $sheet->setCellValue("A{$index}", "Сдано в кассу            " . $summAmount . ' р.')->getStyle("A{$index}")->getFont()->setSize(10);
        $sheet->setCellValue("J{$index}", $summAmount . ' р.')->getStyle("J{$index}")->getFont()->setSize(10);
        $index += 2;
        $sheet->setCellValue("A{$index}", 'Внесено в банк на расчетный счет');
        $index++;
        $sheet->setCellValue("A{$index}", 'Внесено в банк для перечисления на расчетный счет');
        $index++;
        $sheet->setCellValue("A{$index}", 'Внесено в отд.связи для перечисления');
        $index++;
        $sheet->setCellValue("A{$index}", "Остаток на                    " . $dateNow->format('d.m.Y'));
        $sheet->setCellValue("J{$index}", '0');
        $index++;
//----------------------------------------- Конец документа ----------------------------------------------------------------------
        for ($rowNumber = $index; $rowNumber <= $index + 5; $rowNumber++) {
            $sheet->getRowDimension($rowNumber)->setRowHeight(20);
            $sheet->mergeCells("A{$rowNumber}:V{$rowNumber}");
        }
        $indent = '                                                        ';
        $sheet->getStyle("A{$index}")->getFont()->setSize(10)->setBold(true);
        $sheet->setCellValue("A{$index}", 'Заведующий магазином (старший продавец) _______________     ______________________________');
        $index++;
        $sheet->setCellValue("A{$index}", $indent . $indent . '(подпись)                (расшифровка подписи)');
        $index++;
        $sheet->getStyle("A{$index}")->getFont()->setSize(10)->setBold(true);
        $sheet->setCellValue("A{$index}", 'Администратор');
        $index++;
        $sheet->setCellValue("A{$index}", $indent . $indent . '(подпись)                (расшифровка подписи)');
        $index++;
        $sheet->setCellValue("A{$index}", "                Приложение _________________________ документов");
        $index++;
        $sheet->getStyle("A{$index}")->getFont()->setSize(10)->setBold(true);
        $sheet->setCellValue("A{$index}", "            Отчет проверен  ____________________  _______________  ______________________________");

        $styleMediumLine = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $sheet->getStyle('U10:V14')->applyFromArray($styleMediumLine);

        return $this->saveFile($appKernel, $spreadsheet);

    }

    /**
     * @param $data
     * @param EntityManagerInterface $em
     * @return mixed[]
     * @throws DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    function getData($data, EntityManagerInterface $em): array
    {

        if (isset($data['stockIds']) && !empty($data['stockIds'])) {
            $stocks = $em->getRepository(Stock::class)->findBy(['id' => $data['stockIds']]);
        } else {
            $stocks = $em->getRepository(Stock::class)->findAll();
        }

        $stockIds = '';
        $stockAroundClockIds = '';
        /** @var Stock $stock */
        foreach ($stocks as $stock) {
            $this->stock .= $stock->getName() . ', ';

            $unit = $stock->getUnit();
            if (!is_null($unit)) {
                if ($unit->getIsAroundClock()) {
                    $stockAroundClockIds .= $stock->getId() . ',';
                } else {
                    $stockIds .= $stock->getId() . ',';
                }
            } else {
                $stockIds .= $stock->getId() . ',';
            }
        }

        $this->stock = substr($this->stock, 0, -2);
        if (count($data['stockIds']) === 1) {
            $this->stockName = $this->stock;
        }
        $stockIds = substr($stockIds, 0, -1);
        $stockAroundClockIds = substr($stockAroundClockIds, 0, -1);

        $this->date = new DateTime($data['date']);
        $startTime = substr($data['date'], 0, 10) . ' 00:00:00';
        $endTime = substr($data['date'], 0, 10) . ' 23:59:59';
        $now = new DateTime();
        $nowTime = $now->format('Y-m-d H:i:s');

        $conn = $em->getConnection();

//        Получаем весь реализованный товар
        $soldProducts = $this->_getSoldProducts($conn, $stockIds, $startTime, $endTime, $stockAroundClockIds, $data);

//        Получаем список товара, который пришел\переместили\списали
        $balanceHistory = array_merge($this->getHist($conn, $stockIds, $startTime, $endTime),
            $this->getHist($conn, $stockAroundClockIds,
                substr($data['date'], 0, 10) . ' 08:00:00',
                substr(date('d.m.Y', strtotime($data['date'] . ' +1 day')), 0, 10) . ' 7:59:59'));

//        Обьединяем реализованный товар с приходом\списанием
        $result = [];
        foreach ($soldProducts as $item) {
            $item['receipt'] = 0;
            $item['consumption'] = 0;
            $item['startBalance'] = 0;
            $item['endBalance'] = 0;
            $result[] = $item;
        }
        foreach ($balanceHistory as $itemBalance) {
            $isSameProduct = false;
            if (is_null($itemBalance['quantity_consumption'])) $itemBalance['quantity_consumption'] = 0;
            if (is_null($itemBalance['quantity_receipt'])) $itemBalance['quantity_receipt'] = 0;
            for ($count = 0; $count < count($result); $count++) {
                if ($itemBalance['product_id'] == $result[$count]['id']) {
                    $result[$count]['endBalance'] += $itemBalance['quantity_receipt'] + $itemBalance['quantity_consumption'];
                    switch ($itemBalance['operation_type']) {
                        case "EXPENSE":
                        case 'IMPORT':
                        case 'TRANSFER':
                        case 'INVENTORY':
                            $result[$count]['receipt'] += $itemBalance['quantity_receipt'];
                            $result[$count]['consumption'] -= $itemBalance['quantity_consumption'];
                            break;
                    }
                    $isSameProduct = true;
                    break;
                }
            }
            if (!$isSameProduct && $itemBalance['operation_type'] !== 'APPOINTMENT') {
                $newItem = [
                    'name' => $itemBalance['name'],
                    'measure' => $itemBalance['measure'],
                    'price' => $itemBalance['price'],
                    'id' => $itemBalance['product_id'],
                    'sell_quantity' => 0,
                    'total_amount' => 0,
                    'receipt' => 0,
                    'consumption' => 0,
                    'startBalance' => 0,
                    'endBalance' => 0
                ];
                if (strcasecmp($itemBalance['operation_type'], 'IMPORT') == 0 ||
                    strcasecmp($itemBalance['operation_type'], 'TRANSFER') == 0 ||
                    strcasecmp($itemBalance['operation_type'], 'INVENTORY') == 0 ||
                    strcasecmp($itemBalance['operation_type'], 'EXPENSE') == 0) {
                    $newItem['receipt'] += $itemBalance['quantity_receipt'];
                    $newItem['consumption'] -= $itemBalance['quantity_consumption'];
                }
                $newItem['endBalance'] += $itemBalance['quantity_receipt'] + $itemBalance['quantity_consumption'];
                $result[] = $newItem;
            }
        }

        for ($count = 1; $count < count($result); $count++) {
            if ($result[$count]['id'] == $result[$count - 1]['id']) {
                $result[$count]['endBalance'] = $result[$count - 1]['endBalance'];
                $result[$count]['receipt'] = $result[$count - 1]['receipt'];
                $result[$count]['consumption'] = $result[$count - 1]['consumption'];
            }
        }
        if (empty($result)) return $result;

//        Для каждого товара определяем остаток в начале и конце указанной даты
        $productIds = '';
        for ($count = 0; $count < count($result); $count++) {
            $productIds .= $result[$count]['id'] . ', ';
        }
        $productIds = substr($productIds, 0, -2);
        $productsBalance = array_merge($this->getProdBalance($conn, $stockIds, $productIds, $startTime, $nowTime),
            $this->getProdBalance($conn, $stockAroundClockIds, $productIds,
                substr($data['date'], 0, 10) . ' 08:00:00',
                substr(date('d.m.Y', strtotime($data['date'] . ' +1 day')), 0, 10) . ' 7:59:59'));

        if (count($data['stockIds']) == 0) {
            $data['stockIds'] = explode(',', $stockIds);
        }
        for ($count = 0; $count < count($result); $count++) {
            /**
             * @var ProductStock[] $productStock
             */
            $productStock = $em->getRepository(ProductStock::class)->findBy(['product' => $result[$count]['id'], 'stock' => $data['stockIds']]);
            if (empty($productStock)) continue;
            $productBalance = 0;
            foreach ($productStock as $ps) {
                $productBalance += $ps->getQuantity();
            }
            $isFound = false;
            foreach ($productsBalance as $balance) {
                if ($result[$count]['id'] == $balance['product_id']) {
                    $startBalance = $productBalance - $balance['total_quantity'];
                    $result[$count]['startBalance'] = $startBalance;
                    $result[$count]['endBalance'] += $startBalance;
                    $isFound = true;
                    break;
                }
            }
            if (!$isFound) {
                $result[$count]['startBalance'] = $productBalance;
                $result[$count]['endBalance'] += $productBalance;
            }
        }

        usort($result, $this->alphabetOrderSorter('name'));

        return $result;
    }

    /**
     * @param Connection $conn
     * @param $stockIds
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @throws DBALException
     */
    public function getApp(Connection $conn, $stockIds, $startTime, $endTime): array
    {
        if (empty($stockIds)) return [];

        $sql = $this->_getSqlForProducts($stockIds,
            'appointment.appointments',
            'LEFT JOIN appointment.appointment_product_item api ON tab.id = api.appointment_id',
            'date_end');
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery(['startTime' => $startTime, 'endTime' => $endTime]);
        return $fetch->fetchAllAssociative();

    }

    /**
     * @throws DBALException
     */
    public function getShopProd(Connection $conn, $stockIds, $startTime, $endTime): array
    {
        if (empty($stockIds)) return [];

        $sql = $this->_getSqlForProducts($stockIds,
            'shop.shop_order',
            'LEFT JOIN shop.shop_product_item api ON tab.id = api.shop_order_id',
            'date');

        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery(['startTime' => $startTime, 'endTime' => $endTime]);


        return $fetch->fetchAllAssociative();
    }

    /**
     * @param Connection $conn
     * @param $stockIds
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @throws DBALException
     */
    public function getHist(Connection $conn, $stockIds, $startTime, $endTime): array
    {
        if (empty($stockIds)) return [];

        $sql = 'SELECT dh.product_id, SUM(CASE WHEN dh.quantity < 0 THEN dh.quantity END) as quantity_consumption,
                    SUM(CASE WHEN dh.quantity >= 0 THEN dh.quantity END) as quantity_receipt, dh.operation_type, p.name, p.price, m.name as measure
                    FROM document_history as dh
                    LEFT JOIN product.product p on dh.product_id = p.id
                    LEFT JOIN reference.reference_measurement_units m ON p.measurement_units_id = m.id
                    WHERE dh.date BETWEEN :startTime AND :endTime
                    AND stock_id in (' . $stockIds . ')
                    AND dh.deleted = false
                    GROUP BY dh.operation_type, dh.product_id, p.name, p.price, m.name ';
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery(['startTime' => $startTime, 'endTime' => $endTime]);
        return $fetch->fetchAllAssociative();
    }

    /**
     * @param Connection $conn
     * @param $stockIds
     * @param $productIds
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @throws DBALException
     */
    public function getProdBalance(Connection $conn, $stockIds, $productIds, $startTime, $endTime): array
    {
        if (empty($stockIds)) return [];

        $sql = 'SELECT dh.product_id, SUM(dh.quantity) as total_quantity
                    FROM document_history as dh
                    WHERE dh.date BETWEEN :startTime AND :endTime
                    AND stock_id IN (' . $stockIds . ')
                    AND product_id IN (' . $productIds . ')
                    AND dh.deleted = false
                    GROUP BY dh.product_id';
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery(['startTime' => $startTime, 'endTime' => $endTime]);
        return $fetch->fetchAllAssociative();
    }

    /**
     * @param Connection $conn
     * @param $stockIds
     * @param $startTime
     * @param $endTime
     * @param $stockAroundClockIds
     * @param $data
     * @return array
     * @throws DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    private function _getSoldProducts(Connection $conn, $stockIds, $startTime, $endTime, $stockAroundClockIds, $data): array
    {
        $appointments = array_merge(
            $this->getApp($conn, $stockIds, $startTime, $endTime),
            $this->getApp($conn, $stockAroundClockIds,
                substr($data['date'], 0, 10) . ' 08:00:00',
                substr(date('d.m.Y', strtotime($data['date'] . ' +1 day')), 0, 10) . ' 7:59:59')
        );

        $shops = array_merge(
            $this->getShopProd($conn, $stockIds, $startTime, $endTime),
            $this->getShopProd($conn, $stockAroundClockIds,
                substr($data['date'], 0, 10) . ' 08:00:00',
                substr(date('d.m.Y', strtotime($data['date'] . ' +1 day')), 0, 10) . ' 7:59:59')
        );


        foreach ($appointments as $index => $appointment) {
            foreach ($shops as $a => $shop) {
                if ($shop['payment_type'] === $appointment['payment_type'] &&
                    $shop['id'] === $appointment['id']) {
                    $appointments[$index]['sell_quantity'] += $shop['sell_quantity'];
                    $appointments[$index]['total_amount'] += $shop['total_amount'];
                    unset($shops[$a]);
                }
            }
        }

        return array_merge($appointments, array_values($shops));
    }

    /**
     * @param string $stockIds
     * @param string $table
     * @param string $joinForApi
     * @param string $dateField
     * @return string
     */
    private function _getSqlForProducts(string $stockIds, string $table, string $joinForApi, string $dateField): string
    {
        return 'SELECT p.name, m.name as measure , api.price, api.product_id AS id, 
                    SUM(api.quantity) AS sell_quantity, 
                    SUM(api.amount) AS total_amount,
                    tab.payment_type
                    FROM ' . $table . ' tab
                    ' . $joinForApi . '
                    LEFT JOIN product.product p ON api.product_id = p.id
                    LEFT JOIN reference.reference_measurement_units m ON p.measurement_units_id = m.id
                    WHERE api.stock_id IN (' . $stockIds . ') 
                    AND tab.' . $dateField . ' BETWEEN :startTime AND :endTime
                    AND payment_object = \'COMMODITY\'
                    AND tab.state <> \'DRAFT\' AND tab.state <> \'ERROR\'
                    GROUP BY p.name, m.name , api.price, api.product_id, tab.payment_type';
    }
}
