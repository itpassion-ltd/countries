<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Border extends Pivot
{
    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'borders';
    }

    /**
     * The Country model.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * The neighbor Country model.
     *
     * @return BelongsTo
     */
    public function neighborCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}