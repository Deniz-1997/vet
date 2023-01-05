<?php

namespace App\Service;

use App\Service\ReportsType\PrintReport;
use App\Service\ReportsType\UsersBusinessEntityReport;
use App\Service\ReportsType\ReportsCountReport;
use App\Service\ReportsType\ReportsCountByTypeReport;

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
            case 'usersBusinessEntity':
                return new UsersBusinessEntityReport($type);
            case 'reportsCount':
                return new ReportsCountReport($type);
            case 'reportsCountByType':
                return new ReportsCountByTypeReport($type);
            default:
                return "Тип $type не найден";
        }
    }
}
