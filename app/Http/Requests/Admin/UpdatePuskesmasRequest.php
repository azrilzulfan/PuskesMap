<?php

namespace App\Http\Requests\Admin;

use App\Enums\ServiceCategory;
use App\Models\Puskesmas;
use App\Services\SpatialAnalysisService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdatePuskesmasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Puskesmas|int|string|null $routeParam */
        $routeParam = $this->route('puskesmas');
        $puskesmasId = $routeParam instanceof Puskesmas ? $routeParam->id : $routeParam;

        return [
            'kode_puskesmas' => [
                'required',
                'string',
                'max:30',
                Rule::unique('puskesmas', 'kode_puskesmas')->ignore($puskesmasId),
            ],
            'kecamatan_id' => ['required', 'integer', 'exists:kecamatan,id'],
            'nama_puskesmas' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string'],
            'x_long' => ['required', 'numeric', 'between:-180,180'],
            'y_lat' => ['required', 'numeric', 'between:-90,90'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'kategori_layanan' => ['required', Rule::enum(ServiceCategory::class)],
            'jumlah_posyandu' => ['required', 'integer', 'min:0', 'max:1000'],
            'jumlah_sekolah' => ['required', 'integer', 'min:0', 'max:1000'],
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
