<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Gate;
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = Product::latest()->with('category')->paginate(5);
        //echo "<pre>";print_r($products);die;
        return $products;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required|string|max:191',
            'category_id' => 'required|integer|max:191',
            'description' => 'required|string|max:191',
            'product_code' => 'required|string|max:191',
            'photo' => 'required|string|min:191',
        ]);

        if($request->isMethod('post')){
            $product = new Product;
            $product->product_name=$request['product_name'];
            $product->product_code=$request['product_code'];
            $product->category_id=$request['category_id'];
            $product->description=$request['description'];
            $product->price = $request['price'];
            //check for current photo
            $currentPhoto = $product->photo;
            //Upload Image
            if($request->photo != $currentPhoto){
                $imgUpload = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
                \Image::make($request->photo)->save(public_path('images/products/').$imgUpload);
                //upload to the db using the merge function
                $product->photo = $imgUpload;

                //delete old photo if user updates their products picture
                $oldPhoto = public_path('images/products/').$currentPhoto;
                if (file_exists($oldPhoto)) {
                    @unlink($oldPhoto);
                }

            }
            //echo "<pre>";print_r($product);die;
            $product->save();
        }
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

        $products = Product::findOrFail($id);
        //validate product information
        $this->validate($request, [
            'product_name' => 'required|string|max:191',
            'product_code' => 'required|string|max:191',
            'category_id' => 'required|max:20',
            'description' => 'required|string|max:191',
            'price' => 'required|integer',
            'photo' => 'required|string|max:191',
        ]);
        //update product
         $products->update($request->all());
        //return ['message'=>'updating'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $products = Product::findOrFail($id);
        //delete the products
        $products->delete();
        //return ['message' => 'product is Deleted'];
    }
}
