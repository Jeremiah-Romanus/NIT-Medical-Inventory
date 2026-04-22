<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'batch_number',
        'quantity',
        'expiry_date',
        'unit_price',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Check if medicine is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    /**
     * Check if medicine is expiring soon (within 3 months)
     */
    public function isExpiringSoon(): bool
    {
        $threeMonthsLater = now()->addMonths(3);
        return $this->expiry_date <= $threeMonthsLater && !$this->isExpired();
    }

    /**
     * Get status as string
     */
    public function getStatus(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        } elseif ($this->isExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'active';
    }
}
