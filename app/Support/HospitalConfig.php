<?php

namespace App\Support;

use App\Models\HospitalSetting;
use Illuminate\Support\Facades\Cache;

class HospitalConfig
{
    public function get(): array
    {
        return Cache::remember('hospital.settings', now()->addMinutes(10), function () {
            $record = HospitalSetting::query()->latest('id')->first();

            if (!$record) {
                return config('hospital.defaults');
            }

            return array_merge(config('hospital.defaults'), $record->toArray());
        });
    }
}
