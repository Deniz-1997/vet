<?php

namespace App\Interfaces\CAS;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Reference\BusinesEntity;
use App\Entity\Reference\Station;
use App\Entity\ApiData\ApiQueueRow;
use App\Entity\Reference\SupervisedObjects;
use App\Entity\Reference\Subdivision;

interface CasExcelUploadInterface
{
    public function UploadApiQueueRow(ApiQueueRow $row);
    public function SaveFile(UploadedFile $file, ?Station $station, ?BusinesEntity $businesEntity, ?SupervisedObjects $supervisedObject, ?Subdivision $subdivision);
}
