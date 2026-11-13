<?php

namespace App\Http\Requests\Admin;

use App\Enums\ServiceCategory;
use App\Services\SpatialAnalysisService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePuskesmasRequest extends FormRequest
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
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'kode_puskesmas' => ['required', 'string', 'max:30', Rule::unique('puskesmas', 'kode_puskesmas')],
            'kecamatan_id' => ['required', 'integer', 'exists:kecamatan,id'],
            'nama_puskesmas' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string'],
            'x_long' => ['required', 'numeric', 'between:-180,180'],
            'y_lat' => ['required', 'numeric', 'between:-90,90'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'kategori_layanan' => ['required', Rule::enum(ServiceCategory::class)],
            'jumlah_posyandu' => ['required', 'integer', 'min:0', 'max:1000'],
            'jumlah_sekolah' => ['required', 'integer', 'min:0', 'max:1000'],
            'status_igd_24jam' => ['boolean'],
            'jam_buka' => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_tutup' => ['nullable', 'date_format:H:i,H:i:s'],
            'kontak_darurat' => ['nullable', 'string', 'max:50'],
            'rawat_inap' => ['boolean'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $lat = (float) $this->input('y_lat');
                $lng = (float) $this->input('x_long');

                if (! app(SpatialAnalysisService::class)->isWithinBogorBounds($lat, $lng)) {
                    $validator->errors()->add(
                        'y_lat',
                        'Koordinat Puskesmas berada di luar batas administratif Kota Bogor.',
                    );
                }
            },
        ];
    }
}
