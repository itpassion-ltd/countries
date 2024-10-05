<?php

namespace ItpassionLtd\Countries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Country extends Model
{
    /**
     * @inheritdoc
     */
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

    /**
     * The neighboring Countries of this Country.
     *
     * @return BelongsToMany
     */
    public function neighbors(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, config('itpassion-ltd-countries.table_prefix').'borders', 'country_id', 'neighbor_country_id')
            ->using(Border::class);
    }

    /**
     * The Nationality to which this Country belongs.
     *
     * @return BelongsTo
     */
    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    /**
     * The Region to which this Country belongs.
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * The Subdivisions that belong to this Country.
     *
     * @return HasMany
     */
    public function subdivisions(): HasMany
    {
        return $this->hasMany(Subdivision::class);
    }
}