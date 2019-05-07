<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use TenantModels;

    protected $table      = 'event';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'start_date',
        'end_date',
        'people_limit',
        'guest_limit_per_person',
        'days_for_cancel',
        'value_per_person',
        'description',
        'zipcode',
        'address',
        'address_number',
        'city',
        'district',
        'uf',
        'complement',
        'reference',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'company_id',
    ];

    /**
     * Get the main Guests of the event
     */
    public function mainGuests()
    {
        return $this->hasMany('Sisnanceiro\Models\EventGuest')->whereNull('invited_by_id');
    }
}
