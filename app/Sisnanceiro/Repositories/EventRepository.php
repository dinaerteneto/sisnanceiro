<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Event;

class EventRepository extends Repository
{

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function load($start, $end) 
    {
        return $this->model
                    ->where('start_date', '>=', $start)
                    ->where('end_date', '<=', $end)
                    ->get();
    }

}
