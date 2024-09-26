<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['alpha_3', 'minor_name', 'minor_symbol', 'minor_unit', 'major_name', 'major_symbol', 'numeric'];

    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'currencies';
    }
}