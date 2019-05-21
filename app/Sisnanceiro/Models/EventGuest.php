<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class EventGuest extends Model
{
    use TenantModels;

    const STATUS_WAITING   = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_DENIED    = 3;

    protected $table      = 'event_guest';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'company_id',
        'event_id',
        'person_id',
        'payment_method_id',
        'responsable_of_payment',
        'value',
        'invited_by_id',
        'person_name',
        'email',
        'token_email',
        'status',
    ];

    protected $hidden = [
        'company_id',
    ];


    /**
     * Get the event
     */
    public function event()
    {
        return $this->hasOne('Sisnanceiro\Models\Event', 'id', 'event_id');
    }

    /**
     * Get the main Guests of the event
     */
    public function invitedBy()
    {
        return $this->hasOne('Sisnanceiro\Models\EventGuest', 'id', 'invited_by_id');
    }

    /**
     * Get the main Guests of the event
     */
    public function invitedByMe()
    {
        return $this->hasMany('Sisnanceiro\Models\EventGuest', 'invited_by_id');
    }

    /**
     * Get label by status
     */
    public function getStatus()
    {
        $status = null;
        switch ($this->status) {
            case 1:
                $status = 'Aguardando';
                break;
            case 2:
                $status = 'Confirmado';
                break;
            case 3:
                $status = 'Recusado';
                break;
        }
        return $status;
    }

}
