<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Default date format: day/month/year (e.g., 05/06/2026)
     */
    const DEFAULT_DATE_FORMAT = 'd/m/Y';
    
    /**
     * Default datetime format: day/month/year hour:minute (e.g., 05/06/2026 14:30)
     */
    const DEFAULT_DATETIME_FORMAT = 'd/m/Y H:i';

    /**
     * Format date as d/m/Y (day/month/year)
     */
    public static function formatDate($date, $format = self::DEFAULT_DATE_FORMAT)
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
    public static function formatDateTime($date, $format = self::DEFAULT_DATETIME_FORMAT)
    {
        return self::formatDate($date, $format);
    }

    /**
     * Format for CSV/exports as d/m/Y
     */
    public static function formatForExport($date)
    {
        return self::formatDate($date, self::DEFAULT_DATE_FORMAT);
    }
    
    /**
     * Format for HTML5 date input (Y-m-d)
     */
    public static function formatForInput($date)
    {
        if ($date === null) {
            return '';
        }
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format('Y-m-d');
    }
    
    /**
     * Format for HTML5 datetime-local input (Y-m-d\TH:i)
     */
    public static function formatForDateTimeInput($date)
    {
        if ($date === null) {
            return '';
        }
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format('Y-m-d\TH:i');
    }
}
