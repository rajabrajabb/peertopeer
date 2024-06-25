<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\SaveImageHelperClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function register(Request $request){
         try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|regex:/^\+?\d{8,11}$/|numeric'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'fcm_user_id' => $request->fcm_user_id
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user_id' => $user->id
        ]);
    }

     public function login(Request $request){
         $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                "token" => $token,
                "user" => $user
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $content  = $user->load(
           'favorite_services.service.type',
            'favorite_services.service.category'
        );
        return response($content);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user = auth()->user();
        $fields = ['email', 'name', 'phone', 'bio','avatar','fcm_user_id'];

        foreach($fields as $field){
            if($request->has($field)){
                  $user->{$field} = $request->get($field);
            }
        }

         if ($request->has('avatar')) {

        $avatarData = $request->input('avatar');
        $fileName = SaveImageHelperClass::saveBase64Image($avatarData);

        $user->avatar = $fileName;
        }


        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

   public function favoriteServices()
    {
        $user = auth()->user();

        $favoriteServices = $user->favorite_services()
        ->with(['service' => function ($query) {
            $query->with(['user', 'type', 'category']);
        }])
        ->get()
        ->map(function ($favoriteService) {
            return $favoriteService->service;
        });

        return response()->json($favoriteServices);
    }

      public function userServices(){
        $user = auth()->user();

        $services =  Service::where('user_id',"=",$user->id)
        ->with(['user', 'type', 'category'])
        ->get();

        return response($services);
    }
}
