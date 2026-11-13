<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKunjunganRequest extends FormRequest
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
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2020,2100'],
            'total_pasien_bulanan' => ['required', 'integer', 'min:0', 'max:1000000'],
            'kapasitas_maksimal_bulanan' => ['nullable', 'integer', 'min:1', 'max:1000000'],
        ];
    }
}
