<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CountryCodeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'country' => CountryCodeEnum::class
    ];

    public function getCountry(): CountryCodeEnum
    {
        return $this->country;
    }

    public function getDate(): Carbon
    {
        return $this->date;
    }
}
