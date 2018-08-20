<?php

namespace App\Tenant;

use App\Models\Company;

class TenantManager
{
    private $tenant;

    public function getTenant()
    {
        if (!$this->tenant) {
            $user = \Auth::user();

            if ($user) {
                $this->tenant = Company
                    ::where('id', $user->company_id)
                    ->first();
            } else {
                print_r(\Auth::user());
                return null;
            }
        }

        return $this->tenant;
    }
}
