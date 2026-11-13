<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EquityAnalysisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatan,id'],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:2100'],
        ];
    }
}
