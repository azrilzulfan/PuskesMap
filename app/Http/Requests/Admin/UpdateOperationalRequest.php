<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status_igd_24jam' => $this->boolean('status_igd_24jam'),
            'rawat_inap' => $this->boolean('rawat_inap'),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'status_igd_24jam' => ['required', 'boolean'],
            'jam_buka' => ['required', 'date_format:H:i,H:i:s'],
            'jam_tutup' => ['required', 'date_format:H:i,H:i:s'],
            'kontak_darurat' => ['nullable', 'string', 'max:50'],
            'rawat_inap' => ['required', 'boolean'],
        ];
    }
}
