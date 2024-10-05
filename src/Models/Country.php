<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Country extends Model
{
    protected $fillable = [
        'address_format', 'capital', 'flag_path', 'iso_3166_1_alpha2', 'iso_3166_1_alpha3', 'iso_3166_1_numeric', 'landlocked', 'name_common', 'name_official',
        'national_destination_code_lengths', 'national_number_lengths', 'national_prefix', 'nationality_id', 'region_id', 'uses_postal_code',
    ];

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return config('itpassion-ltd-countries.table_prefix').'countries';
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}