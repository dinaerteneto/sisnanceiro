<?php

namespace App\Http\Controllers;

use Sisnanceiro\Services\SynchronizerService;

class SynchronizerController extends Controller
{

    public function __construct(SynchronizerService $synchronizerService)
    {
        $this->synchronizerService = $synchronizerService;
    }

    public function customerSync()
    {
        $this->synchronizerService->sync();
    }

}
