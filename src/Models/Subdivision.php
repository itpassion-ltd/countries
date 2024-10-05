<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subdivision extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = ['iso_3166_2', 'name', 'type'];

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'subdivisions';
    }

    /**
     * The country to which this subdivision belongs.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}