<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Event;

class EventRepository extends Repository
{

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

}
