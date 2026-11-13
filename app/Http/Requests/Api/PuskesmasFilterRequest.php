<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PuskesmasFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('igd_24jam') && $this->input('igd_24jam') !== null && $this->input('igd_24jam') !== '') {
            $parsed = filter_var($this->input('igd_24jam'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($parsed !== null) {
                $this->merge(['igd_24jam' => $parsed]);
            }
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatan,id'],
            'igd_24jam' => ['nullable', 'boolean'],
            'jam_operasional' => ['nullable', 'string', 'in:buka_sekarang,pagi,siang'],
            'kelengkapan_nakes' => ['nullable', 'string', 'in:Lengkap,Kurang Lengkap'],
        ];
    }
}
