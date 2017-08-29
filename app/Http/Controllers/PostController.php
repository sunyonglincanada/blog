<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use Purifier;
use Session;
use Image;
use Storage;

class PostController extends Controller
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        // Only login users can access to /posts page
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // create a variable and store all the blog posts in it from the database
        $posts = Post::orderBy('id','desc')->paginate(10);


        // return a view and pass in the above variable
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the data
//        dd($request);
        $this->validate($request, array(
           'title'          => 'required|max:255',
           'slug'           => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
           'category_id'    => 'required|integer',
           'body'           => 'required',
           'featured_image' => 'sometimes|image'
        ));

        // store in the database
        $post = new Post;

        $post->title        = $request->title;
        $post->slug         = $request->slug;
        $post->category_id  = $request->category_id;
        $post->body         = Purifier::clean($request->body);

        // Save featured image
        if($request->hasFile('featured_image')){
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/'. $filename);
            Image::make($image)->resize(800,400)->save($location);

            $post->image = $filename;

        }

        $post->save();

        $post->tags()->sync($request->tags, false);

        Session::flash('success', 'The blog post was successfully saved!');

        // redirect to another page
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // find the post in the database and save as a var
        $post = Post::find($id);
        $categories = Category::all();

        $cats = array();
        foreach($categories as $category){
            $cats[$category->id] = $category->name;
        }

        $tags = Tag::all();
        $tags2 = array();
        foreach ($tags as $tag){
            $tags2[$tag->id] = $tag->name;
        }

        // return the view and pass in the var we previously created
        return view('posts.edit')->withPost($post)->withCategories($cats)->withTags($tags2);
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
        // Validate the data
        $post = Post::find($id);

        $this->validate($request, array(
           'title'          => 'required|max:255',
           'slug'           => "required|alpha_dash|min:5|max:255|unique:posts,slug,$id",
           'category_id'    => 'required|integer',
           'body'           => 'required',
           'featured_image' => 'image'
        ));



        // Save the data to the database
        $post = Post::find($id);

        $post->title = $request->input('title');
        $post->slug  = $request->input('slug');
        $post->category_id  = $request->input('category_id');
        $post->body  = Purifier::clean($request->input('body'));

        if($request->hasFile('featured_image') ){
            // Delete the old image
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/'. $filename);
            Image::make($image)->resize(800,400)->save($location);
            $oldFileName = $post->image;

            // Add the new image
            $post->image = $filename;

            // Update the database
            Storage::delete($oldFileName);
        }


        $post->save();

        if (isset($request->tags)){

            // set detaching true is to delete old relationship and update new relationship
            $post->tags()->sync($request->tags, true);

        } else {
            $post->tags()->sync(array());
        }

        // Set flash data with success message
        Session::flash('success','This post was successfully saved.');

        // Redirect with flash data to posts.show
        return redirect()->route('posts.show', $post->id );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        // delete the relationship in post_tag table for corresponding post id
        $post->tags()->detach();
        // delete the old image in the post
        Storage::delete($post->image);

        $post->delete();

        Session::flash('success', 'The post was successfully deleted.');
        return redirect()->route('posts.index');
    }
}
