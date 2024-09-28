<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallingCode extends Model
{
    protected $fillable = ['calling_code'];

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'calling_codes';
    }
}