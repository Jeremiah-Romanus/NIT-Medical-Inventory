<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineFactory> */
    use HasFactory;

    protected $fillable = [
        'medical_id',
        'name',
        'category',
        'formulation_strength',
        'batch_number',
        'quantity',
        'pharmacy_quantity',
        'stored_date',
        'expiry_date',
        'unit_price',
    ];

    protected $casts = [
        'stored_date' => 'date',
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
        'pharmacy_quantity' => 'integer',
    ];

    /**
     * Check if medicine is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    /**
     * Check if medicine is expiring soon (within 6 months)
     */
    public function isExpiringSoon(): bool
    {
        $sixMonthsLater = now()->addMonths(6);
        return $this->expiry_date <= $sixMonthsLater && !$this->isExpired();
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
