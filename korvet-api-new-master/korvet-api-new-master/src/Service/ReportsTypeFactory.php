<?php

namespace App\Service;

use App\Service\ReportsType\CullingReport;
use App\Service\ReportsType\PrintReport;
use App\Service\ReportsType\RevenueReport;
use App\Service\ReportsType\ShiftReport;
use App\Service\ReportsType\TotalRevenueReport;
use App\Service\ReportsType\WarehouseStatement;

/**
 * Class ReportsTypeFactory
 */
class ReportsTypeFactory
{
    /**
     * @param string $type
     * @return PrintReport|string
     */
    public static function generateReport(string $type): ?PrintReport
    {
        switch ($type) {
            case 'shiftReport':
                return new ShiftReport('shiftReport');
            case 'revenueReport':
                return new RevenueReport('revenueReport');
            case 'warehouseStatement':
                return new WarehouseStatement('warehouseStatement');
            case 'cullingReport':
                return new CullingReport('cullingReport');
            case 'totalRevenueReport':
                return new TotalRevenueReport('totalRevenueReport');
            default:
                return "Тип $type не найден";
        }
    }
}
