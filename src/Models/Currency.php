<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Currency extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'alpha_3', 'html_entity', 'minor_name', 'minor_symbol', 'minor_unit', 'major_name','major_symbol', 'numeric',
    ];

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'currencies';
    }
}