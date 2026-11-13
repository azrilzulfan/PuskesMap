<?php

namespace App\Http\Requests\Api;

class RadiusPuskesmasRequest extends PuskesmasFilterRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:100', 'max:20000'],
        ];
    }
}
