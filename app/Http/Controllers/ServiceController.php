<?php

namespace App\Http\Controllers;

use App\Models\FavoritServices;
use App\Models\Service;
use App\SaveImageHelperClass;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query()->with('type','category','user')->orderBy('search_value','DESC');

        $query->when($request->has('name'),function($q) use ($request){
            return $q->where('name', 'like' , '%' . $request->name . '%');
        });
        $query->when($request->has('type_id'),function($q) use ($request){
            return $q->where('type_id',$request->type_id);
        });
        $query->when($request->has('category_id'),function($q) use ($request){
            return $q->where('category_id',$request->category_id);
        });
        $query->when($request->has('location'),function($q) use ($request){
            return $q->where('location',$request->location);
        });
        $query->when($request->has('price'),function($q) use ($request){
            return $q->where('price',$request->price);
        });
        $query->when($request->has('service_type'),function($q) use ($request){
            return $q->where('service_type',$request->service_type);
        });

        $services = $query->get();

        $favoritedServiceIds = auth()->user()->favorite_services()->pluck('service_id')->toArray();

        $content = $services->map(function($service) use ($favoritedServiceIds)
        {
            $service->is_favorite = in_array($service->id, $favoritedServiceIds);
            return $service;
        });

        $count = $content->count();

        return response()->json([
            'results_count' => $count,
            'content' => $content
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = auth()->user();

         try {
            $request->validate([
                'name' => 'required',
                'type_id' => 'required',
                'category_id' => 'required',
                'description' => 'required',
                'location' => 'required',
                'image' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $imageData = $request->input('image');
        $fileName = SaveImageHelperClass::saveBase64Image($imageData);

        $service = Service::create([
        'name' => request()->get('name'),
        'type_id' => request()->get('type_id'),
        'category_id'=> request()->get('category_id'),
        'image'=> $fileName,
        'description'=>request()->get('description'),
        'price'=>request()->get('price'),
        'service_type'=>request()->get('service_type'),
        'location'=>request()->get('location'),
        'user_id' =>  $user->id
        ]);

        return response($service->load(['user']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json('Service deleted successfully');
    }

    public function addServiceTofavorit(Request $request)
    {
        $user = auth()->user();

        FavoritServices::create([
            'user_id' => $user->id,
            'service_id' => $request->get('service_id')
        ]);

        return response([
            'added to favorit'
        ]);
    }

    public function deleteServiceFromFavorite(Request $request)
    {
    $user = auth()->user();

    $favoriteService = FavoritServices::where('user_id', $user->id)
        ->where('service_id', $request->get('service_id'))
        ->first();

    if ($favoriteService) {
        $favoriteService->delete();
    }
        return response('Service removed from favorites');
    }


    public function addSearchValue(Request $request,Service $service){
        $service->update([
            'search_value' => $request->get('search_value'),
        ]);

        return response('search value added');
    }

}
