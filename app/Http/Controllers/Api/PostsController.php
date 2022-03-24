<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\map;

class PostsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $image = Storage::get('public/' . $user->name . '/h5mk7js_cat-generic_625x300_28_August_20.jpg_1647208342.jpg');

        // $image = Storage::get('public' . $user->name . '/');


        $image = Storage::url('public/' . $user->name . '/');
        $image = Storage::download('public/' . $user->name . '/');




        // dd($image);
        // return response()->json([
        //     'image_url' => $image,
        // ]);

        return response()->download(storage_path('app/public/' . $user->name . '/h5mk7js_cat-generic_625x300_28_August_20.jpg_1647208342.jpg'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $user = Auth::user();

        $request->validate([
            'image.*' => 'mimes: jpg, jpeg, png, gif, svg'
        ]);

        $file = $request->file('image');




        $fileName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();

        $fileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;

        // $path = Storage::disk('/local')->put($user->name . '/' . $fileNameToStore . $fileExtension, $file);
        $path = $file->storeAs('public/' . $user->name . '/', $fileNameToStore);

        // $path = $file->storeAs('../pics/' . $user->name . '/', $fileNameToStore);
        return response([
            'message' => 'success',
            'image' => $path
        ]);



        return response([
            'message' => 'error'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
