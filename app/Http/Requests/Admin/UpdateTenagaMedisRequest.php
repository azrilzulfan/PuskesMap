<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenagaMedisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'dokter_umum' => ['required', 'integer', 'min:0', 'max:500'],
            'dokter_gigi' => ['required', 'integer', 'min:0', 'max:500'],
            'bidan' => ['required', 'integer', 'min:0', 'max:500'],
            'perawat' => ['required', 'integer', 'min:0', 'max:500'],
            'farmasi' => ['required', 'integer', 'min:0', 'max:500'],
            'gizi' => ['required', 'integer', 'min:0', 'max:500'],
            'kesling' => ['required', 'integer', 'min:0', 'max:500'],
            'kesmas' => ['required', 'integer', 'min:0', 'max:500'],
            'atlm' => ['required', 'integer', 'min:0', 'max:500'],
        ];
    }
}
