<?php

namespace App\Models;


use App\Enum\ProductStatusEnum;

class Product extends BaseModel
{
    protected $fillable=["title","description","slug","sku","status","sub_label"];
    protected $casts=[
        "status"=>ProductStatusEnum::class
    ];
}
