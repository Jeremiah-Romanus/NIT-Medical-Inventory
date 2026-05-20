<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date as d/m/Y
     */
    public static function formatDate($date, $format = 'd/m/Y')
    {
        if ($date === null) {
            return null;
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->format($format);
    }

    /**
     * Format date with time as d/m/Y H:i
     */
    public static function formatDateTime($date)
    {
        return self::formatDate($date, 'd/m/Y H:i');
    }

    /**
     * Format for CSV/exports as d/m/Y
     */
    public static function formatForExport($date)
    {
        return self::formatDate($date, 'd/m/Y');
    }
}
