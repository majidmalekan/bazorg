<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class BaseModel extends Model
{
    use HasFactory;

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {

        return Jalalian::fromCarbon(Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Tehran')
        )->toString();
    }

    /**
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value): string
    {
        return Jalalian::fromCarbon(Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Tehran')
        )->toString();
    }

}
