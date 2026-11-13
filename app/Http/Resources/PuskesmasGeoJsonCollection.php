<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PuskesmasGeoJsonCollection extends ResourceCollection
{
    public static $wrap = null;

    public $collects = PuskesmasGeoJsonResource::class;

    /**
     * @var array<string, mixed>
     */
    private array $metaData;

    /**
     * @param  array<string, mixed>  $metaData
     */
    public function __construct(mixed $resource, array $metaData = [])
    {
        parent::__construct($resource);
        $this->metaData = $metaData;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payload = [
            'type' => 'FeatureCollection',
            'count' => $this->collection->count(),
            'features' => $this->collection->map(
                fn (PuskesmasGeoJsonResource $resource): array => $resource->toArray($request),
            )->values()->all(),
        ];

        if ($this->metaData !== []) {
            $payload['meta'] = $this->metaData;
        }

        return $payload;
    }
}
