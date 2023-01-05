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
use App\Entity\Contractor;
use App\Exception\ApiException;

class CullingReport extends PrintReport
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
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(27);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(13);
        $sheet->getRowDimension(1)->setRowHeight(18.8);

        $contractorId = 0;
        $index = 0;
        $number = 1;
        for ($count = 0; $count < count($request); $count++) {
            if ($contractorId !== $request[$count]["contractor_id"]) {
                $index ++;
                $sheet->mergeCells("A{$index}:C{$index}")->setCellValue("A{$index}", "Организация:");
                $sheet->mergeCells("D{$index}:M{$index}")->setCellValue("D{$index}", $request[$count]["contractor_name"]);
                $sheet->getCell("D{$index}")->getStyle()->getFont()->setBold(true);
                $index++;
                $sheet->mergeCells("A{$index}:C{$index}")->setCellValue("A{$index}", "Юридический адрес:");
                $sheet->mergeCells("D{$index}:M{$index}")->setCellValue("D{$index}", $request[$count]["juridical_address"]);
                $sheet->getCell("D{$index}")->getStyle()->getFont()->setBold(true);
                $index++;
                $sheet->mergeCells("A{$index}:C{$index}")->setCellValue("A{$index}", "Фактический адрес:");
                $sheet->mergeCells("D{$index}:M{$index}")->setCellValue("D{$index}", $request[$count]["fact_adress"]);
                $sheet->getCell("D{$index}")->getStyle()->getFont()->setBold(true);
                $index++;
                $sheet->mergeCells("A{$index}:C{$index}")->setCellValue("A{$index}", "ИНН:");
                $sheet->mergeCells("D{$index}:M{$index}")->setCellValue("D{$index}",  $request[$count]["contractor_inn"]);
                $sheet->getCell("D{$index}")->getStyle()->getFont()->setBold(true);
                $index++;
                $contractorId = $request[$count]["contractor_id"];

                $maxIndex = $index + 5;
                $incrementIndex = $index + 1;
                $sheet->mergeCells("A{$index}:A{$maxIndex}")->setCellValue("A{$index}", "№ п/п");
                $sheet->getStyle("A{$index}:A{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("B{$index}:B{$maxIndex}")->setCellValue("B{$index}", "№, присвоенный при поступлении");
                $sheet->getStyle("B{$index}:B{$maxIndex}")->applyFromArray($headStyle);
                $sheet->getStyle("B{$index}:B{$maxIndex}")->getAlignment()->setTextRotation(90);
                $sheet->mergeCells("C{$index}:C{$maxIndex}")->setCellValue("C{$index}", "Дата и место отлова животного без владельца");
                $sheet->getStyle("C{$index}:C{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("D{$index}:D{$maxIndex}")->setCellValue("D{$index}", "Описание животного без владельца");
                $sheet->getStyle("D{$index}:D{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("E{$index}:E{$maxIndex}")->setCellValue("E{$index}", "Период карантинирования");
                $sheet->getStyle("E{$index}:E{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("F{$index}:F{$maxIndex}")->setCellValue("F{$index}", "Стерилизация");
                $sheet->getStyle("F{$index}:F{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("G{$index}:G{$maxIndex}")->setCellValue("G{$index}", "Маркирование");
                $sheet->getStyle("G{$index}:G{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("H{$index}:H{$maxIndex}")->setCellValue("H{$index}", "Вакцинация");
                $sheet->getStyle("H{$index}:H{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("I{$index}:K{$index}")->setCellValue("I{$index}", "Дата выбытия");
                $sheet->getStyle("I{$index}:K{$index}")->applyFromArray($headStyle);
                $sheet->mergeCells("I{$incrementIndex}:I{$maxIndex}")->setCellValue("I{$incrementIndex}", "возврат на прежнее место обитания");
                $sheet->getStyle("I{$incrementIndex}:I{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("J{$incrementIndex}:J{$maxIndex}")->setCellValue("J{$incrementIndex}", "возврат владельцу");
                $sheet->getStyle("K{$incrementIndex}:K{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("K{$incrementIndex}:K{$maxIndex}")->setCellValue("K{$incrementIndex}", "дата и причина смерти");
                $sheet->getStyle("K{$incrementIndex}:K{$maxIndex}")->applyFromArray($headStyle);
                $sheet->mergeCells("L{$index}:L{$maxIndex}")->setCellValue("L{$index}", "Находится на постоянном содержании в приюте");
                $sheet->getStyle("L{$index}:L{$maxIndex}")->applyFromArray($headStyle);
                $index = $maxIndex + 1;

                $sheet->setCellValue("A{$index}", 1);
                $sheet->getStyle("A{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("A{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("B{$index}", 2);
                $sheet->getStyle("B{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("B{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("C{$index}", 3);
                $sheet->getStyle("C{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("C{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("D{$index}", 4);
                $sheet->getStyle("D{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("D{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("E{$index}", 5);
                $sheet->getStyle("E{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("E{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("F{$index}", 6);
                $sheet->getStyle("F{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("F{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("G{$index}", 7);
                $sheet->getStyle("G{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("G{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("H{$index}", 8);
                $sheet->getStyle("H{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("H{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("I{$index}", 9);
                $sheet->getStyle("I{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("I{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("J{$index}", 10);
                $sheet->getStyle("J{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("J{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("K{$index}", 11);
                $sheet->getStyle("K{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("K{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $sheet->setCellValue("L{$index}", 12);
                $sheet->getStyle("L{$index}")->applyFromArray($headStyle);
                $sheet->getStyle("L{$index}")->getAlignment()->applyFromArray($centerAlignment);
                $index++;
                $number = 1;
            }
            $sheet->setCellValue("A{$index}", $number);
            $sheet->getStyle("A{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("B{$index}", $request[$count]["animal_number"]);
            $sheet->getStyle("B{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("C{$index}", $request[$count]["place"]);
            $sheet->getStyle("C{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("D{$index}", $request[$count]["animal_info"]);
            $sheet->getStyle("D{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("E{$index}", $request[$count]["quarantine_period"]);
            $sheet->getStyle("E{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("F{$index}", $request[$count]["sterilization_date"]);
            $sheet->getStyle("F{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("G{$index}", $request[$count]["tag_info"]);
            $sheet->getStyle("G{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("H{$index}", $request[$count]["vaccination"]);
            $sheet->getStyle("H{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("I{$index}", $request[$count]["release_to_street"]);
            $sheet->getStyle("I{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("J{$index}", $request[$count]["release_new_owners"]);
            $sheet->getStyle("J{$index}")->applyFromArray($styleLine);
            $sheet->setCellValue("K{$index}", $request[$count]["death"]);
            $sheet->getStyle("K{$index}")->applyFromArray($styleLine);
            $sheet->getStyle("L{$index}")->applyFromArray($styleLine);
            $index++;
            $number++;

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
        $contractorIds = [];
        $startDate = new DateTime();
        $endDate = new DateTime();
        if (array_key_exists('contractorIds', $data) && count($data['contractorIds']) > 0) {
            $contractorIds = $data['contractorIds'];
        }
        else {
            $contractors = $em->getRepository(Contractor::class)->findAll();
            $contractorIds = array_map(function (Contractor $contractor) { return $contractor->getId(); }, 
            array_filter($contractors, function (Contractor $contractor) { return !$contractor->isDeleted(); }));
        }

        if (array_key_exists('startDate', $data)) {
            $startDate = new DateTime($data['startDate']);
        }
        if (array_key_exists('endDate', $data)) {
            $endDate = new DateTime($data['endDate']);
        }

        $sql = "SELECT cntr.id AS contractor_id, cntr.name AS contractor_name, cntr.inn || ' ' AS contractor_inn, cntr.legal_entity_juridical_address AS juridical_address,
                cntr.address_full AS fact_adress, wa.id, to_char(cr.date, 'DD.MM.YYYY') || ' ' || cr.address_full AS place,
                pb.name || ', ' || pt.name || ', ' ||
                CASE WHEN wa.gender='FEMALE' THEN 'Самка' WHEN wa.gender='MALE' THEN 'Самец' END
                || ', ' || CASE WHEN wa.aggressive=TRUE THEN 'агрессивно' WHEN wa.aggressive=FALSE OR wa.aggressive IS NULL THEN 'не агрессивно' END
                || CASE WHEN wa.birthday IS NOT NULL THEN ', ' ||
                CASE WHEN date_part('year', AGE(wa.birthday)) > 0 AND date_part('year', AGE(wa.birthday)) < 5 THEN  date_part('year', AGE(wa.birthday)) || 'г.' WHEN date_part('year', AGE(wa.birthday)) >= 5 THEN date_part('year', AGE(wa.birthday)) || 'л.' ELSE '' END
                || CASE WHEN date_part('month', AGE(wa.birthday)) > 0 THEN date_part('month', AGE(wa.birthday)) || 'м.' ELSE '' END ELSE ' ' END
                || COALESCE(' ' || wa.description, '')
                AS animal_info, COALESCE(wa.animal_number, ' ') AS animal_number,
                to_char(cr.quarantine_period_start_time, 'DD.MM.YYYY') || '-'||to_char(cr.quarantine_period_end_time, 'DD.MM.YYYY') AS quarantine_period,
                to_char(cr.sterilization_date, 'DD.MM.YYYY') AS sterilization_date,
                LTRIM(COALESCE(to_char(cr.tag_date, 'DD.MM.YYYY') || ' ', '') || COALESCE(cr.tag_number || ' ', '') || COALESCE(tf.name || ' ', '') || wa.chip_number || ' ') AS tag_info,                
                to_char(cr.vaccination_date, 'DD.MM.YYYY') || ' ' || vt.name AS vaccination,
                CASE WHEN cr.releASe_type<>'NEW_OWNERS' AND cr.release_type<>'OLD_OWNER' AND wa.date_of_death IS NULL THEN to_char(cr.release_date, 'DD.MM.YYYY') ELSE '' END AS release_to_street,
                CASE WHEN cr.releASe_type='NEW_OWNERS' OR cr.release_type='OLD_OWNER' AND wa.date_of_death IS NULL THEN to_char(cr.release_date, 'DD.MM.YYYY') ELSE '' END AS release_new_owners,
                to_char(wa.date_of_death, 'DD.MM.YYYY') || ' ' || wa.cause_of_death AS death
                from culling_registration cr
                LEFT JOIN reference.reference_shelter rh ON rh.id = cr.release_shelter_id
                LEFT JOIN wild_animal wa ON wa.id = cr.wild_animal_id
                LEFT JOIN reference.reference_breeds pb ON pb.id = wa.breed_id
                LEFT JOIN reference.reference_pet_types pt ON pt.id = pb.type_id 
                LEFT JOIN reference.reference_tag_forms tf ON tf.id = cr.tag_form_id
                LEFT JOIN reference.reference_vaccination_type vt ON vt.id = cr.vaccination_type_id
                JOIN contractor_contact_persons ccp ON ccp.id = cr.contact_person_id
                JOIN contractor cntr ON ccp.contractor_id = cntr.id
                WHERE cntr.id in (:ids) AND cr.date BETWEEN :start_time AND :end_time AND cr.deleted = false AND wa.deleted = false ORDER BY cntr.id, wa.id";
        $types = ['ids' => Connection::PARAM_INT_ARRAY];
        $params = [
            'ids' => $contractorIds,
            'start_time' => $startDate->format('d.m.Y') . ' 00:00:00',
            'end_time' => $endDate->format('d.m.Y') . ' 23:59:59'
        ];

        $result = [];

        $result = $em->getConnection()->fetchAllAssociative($sql, $params, $types);

        if (!count($result)) {
            throw new ApiException("За выбранный период " . $params['start_time']. " ". $params['end_time'] ." не найдены отловы животных",'CULLING_REPORT_EMPTY',null, 400);
        }

        return $result;
    }
}
