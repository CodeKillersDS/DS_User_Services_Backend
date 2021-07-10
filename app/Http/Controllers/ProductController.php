<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allProducts = Product::all();
        return response()->json($allProducts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$user = User::find($id);
        
        $validator = validator::make($request->all(),[
            'name' => 'required',
            'user_id'=> 'required',
            'slug'=> 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        if($validator-> fails()){
            return response()->json(['message' => 'Something went wrong']);
        }

        $productsList = Product::create($request->all());
        return response()->json($productsList);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $singleProduct = Product::find($id);

        if($singleProduct == null){
            return 'No item found';
        }

        return $singleProduct;
       
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
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        if($validator->fails()){
            return 'update has not been updated';
        }
        else{
            $product = Product::find($id);
            $product->update($request->all());
            return response()->json($product);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name','like','%'.$name.'%')->get();
        //return Product::where("name" , $name) -> get();
    }


    public function getItemsByUser(Request $request){
        $field = $request->validate([
            'user_id' => 'required'
        ]);

        return Product::where('user_id',$field['user_id'])->get();
    }
}
