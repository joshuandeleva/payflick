<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //$this->authorize('isAdmin');

            return Category::latest()->paginate(5);

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
            'category_name' => 'required|string|max:191',
            'parent_id' => 'required|integer|max:191',
            'description' => 'required|string|max:191',
            'url' => 'required|string|max:191',
            'photo' => 'required|string|min:191',
        ]);

        if($request->isMethod('post')){

            if (empty($request['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }
            $category = new Category;
            $category->category_name=$request['category_name'];
            $category->parent_id=$request['parent_id'];
            $category->description=$request['description'];

            $category->url=$request['url'];

            //check for current photo
            $currentPhoto = $category->photo;
            //Upload Image
            if($request->photo != $currentPhoto){
                $imgUpload = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
                \Image::make($request->photo)->save(public_path('images/categories/').$imgUpload);
                //upload to the db using the merge function
                $category->photo = $imgUpload;

                //delete old photo if user updates their categories picture
                $oldPhoto = public_path('images/categories/').$currentPhoto;
                if (file_exists($oldPhoto)) {
                    @unlink($oldPhoto);
                }

            }
            $category->status = $status;
            //echo "<pre>";print_r($category);die;
            $category->save();
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

        $categories = Category::findOrFail($id);
        //validate user information
        $this->validate($request, [
            'category_name' => 'required|string|max:191',
            'description' => 'required|string|max:191',
        ]);
        //update user
        $categories->update($request->all());
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

        $categories = Category::findOrFail($id);
        //delete the categories
        $categories->delete();
        //return ['message' => 'category is Deleted'];
    }
}
