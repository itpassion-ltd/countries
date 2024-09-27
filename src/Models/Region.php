<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Region extends Model
{
    protected $fillable = ['name', 'parent_region_id', 'un_numeric'];

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'regions';
    }

    public function parentRegion(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_region_id');
    }
}