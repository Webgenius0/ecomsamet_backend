<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(){
        $favorites= Favorite::with(['apiuser','service'])->get();
        return view('backend.layouts.favorites.index',compact('favorites'));
    }

    public function destroy($id){

        try{
            Favorite::find($id)->delete();
            return redirect()->route('favorite.index');
        }catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }

    }

}
