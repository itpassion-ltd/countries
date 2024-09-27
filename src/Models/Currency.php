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
    protected $appends = ['html_entity'];

    /**
     * @inheritdoc
     */
    protected $fillable = ['alpha_3', 'minor_name', 'minor_symbol', 'minor_unit', 'major_name', 'major_symbol', 'numeric'];

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'currencies';
    }

    /**
     * Get the htmlEntity for the Unicode symbol
     * @return Attribute
     */
    protected function htmlEntity(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                return Str::replace('/\\u([0-9a-fA-F]{4})/', '&#x$1;', $this->major_symbol);
            }
        );
    }
}