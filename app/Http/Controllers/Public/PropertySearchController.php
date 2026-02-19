<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Properties\PropertyViewResource;
use App\Models\Facility;
use App\Models\Geoobject;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertySearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/search",
     *     summary="Search for properties",
     *     description="Search for properties based on various criteria",
     *     tags={"Properties"},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date for booking (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date for booking (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="price_from",
     *         in="query",
     *         description="Minimum price per night",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="price_to",
     *         in="query",
     *         description="Maximum price per night",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="city_id",
     *         in="query",
     *         description="ID of the city",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="country_id",
     *         in="query",
     *         description="ID of the country",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="geoobject_id",
     *         in="query",
     *         description="ID of the geoobject (e.g. airport, train station)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="adult_capacity",
     *         in="query",
     *         description="Number of adults",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="children_capacity",
     *         in="query",
     *         description="Number of children",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="facilities[]",
     *         in="query",
     *         description="Array of facility IDs",
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $propertiesQuery = Property::withWhereHas('apartments.prices', function ($query) use ($request) {
            $query->isValidForRange(range: [
                $request->start_date ?? now()->addDay()->toDateString(),
                $request->end_date ?? now()->addDays(5)->toDateString(),
            ]);
        })
            ->with([
                'city:id,name',
                'apartments.apartment_type:id,name',
                'apartments.beds.bed_type:id,name',
                'media' => fn ($query) => $query->orderBy('order_column')
            ])
            ->when($request->price_from && $request->price_to, function ($query) use ($request) {
                $query->whereHas('apartments.prices', function ($query) use ($request) {
                    $query->where('price_per_night', '>=', $request->price_from)
                        ->where('price_per_night', '<=', $request->price_to);
                });
            })
            ->when($request->price_from, function ($query) use ($request) {
                $query->whereHas('apartments.prices', function ($query) use ($request) {
                    $query->where('price_per_night', '>=', $request->price_from);
                });
            })
            ->when($request->price_to, function ($query) use ($request) {
                $query->whereHas('apartments.prices', function ($query) use ($request) {
                    $query->where('price_per_night', '<=', $request->price_to);
                });
            })
            ->when($request->city__id, function ($query) use ($request) {
                $query->where('city_id', $request->city_id);
            })
            ->when($request->country_id, function ($query) use ($request) {
                $query->whereHas('city', fn ($q) => $q->where('country_id', $request->country_id));
            })
            ->when($request->geoobject_id, function ($query) use ($request) {
                $geoobject = Geoobject::find($request->geoobject_id);
                if ($geoobject) {
                    $condition = "(2 * 6371
                    * asin(sqrt(
                        pow(sin((radians(`lat`) - radians($geoobject->lat)) / 2), 2)
                        + cos(radians($geoobject->lat))
                        * cos(radians(`lat`))
                        * pow(sin((radians(`long`) - radians($geoobject->long)) / 2), 2)
                    ))) < 10";
                    $query->whereRaw($condition);
                }
            })
            ->when(
                $request->adult_capacity && $request->children_capacity,
                function ($query) use ($request) {
                    $query->withWhereHas('apartments', function ($query) use ($request) {
                        $query->where('adult_capacity', '>=', $request->adult_capacity)
                            ->where('children_capacity', '>=', $request->children_capacity)
                            ->orderBy('adult_capacity')
                            ->orderBy('children_capacity')
                            ->limit(1);
                    });
                }
            )
            ->when($request->facilities, function ($query) use ($request) {
                $query->whereHas('facilities', function ($query) use ($request) {
                    $query->whereIn('facilities.id', $request->facilities);
                });
            });

        $facilities = Facility::query()
            ->withCount(['properties' => function ($property) use ($propertiesQuery) {
                $property->whereIn('property_id', $propertiesQuery->pluck('id'));
            }])
            ->get()
            ->where('properties_count', '>', 0)
            ->sortByDesc('properties_count')
            ->pluck('properties_count', 'name');

        $properties = $propertiesQuery
            ->orderBy('bookings_avg_rating', 'desc')
            ->paginate(10)
            ->withQueryString();

        return [
            'properties' => PropertyViewResource::collection($properties)
                ->response()
                ->getData(true),
            'facilities' => $facilities,
        ];
    }
}
