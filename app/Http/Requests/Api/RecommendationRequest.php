<?php

namespace App\Http\Requests\Api;

use App\Enums\MautScenario;
use App\Services\SpatialAnalysisService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RecommendationRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->input('scenario') === MautScenario::Pemda->value) {
            $user = $this->user();

            return $user !== null && $user->is_active && $user->hasAnyRole('pemerintah', 'admin');
        }

        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'scenario' => ['required', Rule::enum(MautScenario::class)],
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

                $lat = (float) $this->input('lat');
                $lng = (float) $this->input('lng');

                if (! app(SpatialAnalysisService::class)->isWithinBogorBounds($lat, $lng)) {
                    $validator->errors()->add(
                        'lat',
                        'Koordinat lokasi berada di luar batas administratif Kota Bogor.',
                    );
                }
            },
        ];
    }
}
