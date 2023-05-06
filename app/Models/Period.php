<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use HasFactory, SoftDeletes;


    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CANCELLED,
        self::STATUS_COMPLETED
    ];
    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_SEMI_MONTHLY = 'semi-monthly';
    public const TYPE_WEEKLY = 'weekly';

    public const TYPES = [
        self::TYPE_MONTHLY,
        self::TYPE_SEMI_MONTHLY,
        self::TYPE_WEEKLY
    ];

    protected $fillable = [
        'name',
        'company_id',
        'company_period_number',
        'type',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'string'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
