<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $post = Post::orderBy('created_at','desc')->paginate(16);
        return view('home',['posts'=>$post]);
    }

    public function getDashboard(){
        
    }

    public function postCreatePost(Request $request){
        $this->validate($request,[
            'new-post' => 'required|max:1000'
        ]);

        // print_r(Auth::id()); die();
        $post = new Post();
        $post->body = $request->input('new-post');
        // $request->user()->posts()->save($post);
        $post->id_user = Auth::id();

        $message = "Post Not Created";
        if($post->save()){
            $message = "Post Created";
        }
        return redirect()->back()->with(["message"=>$message]);
    }

    public function getPostEdit(Request $request){
        $this->validate($request,[
            'body' => 'required|max:1000'
        ]);

        $post = Post::find($request->input('postId'));
        if(Auth::id()!== $post->id_user){
            return response()->json(['message'=> "Unable to Edit"],200);
        }
        $post->body = $request->input('body');

        $message = "Post Not Updated";
        if($post->save()){
            $message = "Post Updated";
        }
        return response()->json(['message'=> $message],200);
    }

    public function getDeletePost($id)
    {
        $post = Post::find($id);
        if(Auth::id()!== $post->id_user){
            return redirect()->back()->with(['message'=>"Unable to Delete"]);
        }
        $post->delete();
        return redirect()->back()->with(['message'=>"Post Deleted"]);
    }

    public function updateProfileView(){
        $user = User::find(Auth::id());
        return view('profile',['user'=>$user]);
    }

    public function updateProfile(Request $request)
        {
            $this->validate($request, [
               'name' => 'required|max:120'
            ]);

            $user = Auth::user();

            $old_name = $user->name;
            $user->name = $request['name'];
            $user->update();

            $file = $request->file('image');

            $filename = $request['name'] . '-' . $user->id . '.jpg';
            $old_filename = $old_name . '-' . $user->id . '.jpg';
            $update = false;
            if (Storage::disk('local')->has($old_filename)) {
                $old_file = Storage::disk('local')->get($old_filename);
                Storage::disk('local')->put($filename, $old_file);
                $update = true;
            }
            if ($file) {

                Storage::disk('local')->put($filename, File::get($file));
            }
            if ($update && $old_filename !== $filename) {
                Storage::delete($old_filename);
            }
            return redirect()->back();
        }

        public function getUserImage($filename)
        {
            $file = Storage::disk('local')->get($filename);
            return new Response($file, 200);
        }

        public function getPostLike(Request $request){
            $this->validate($request,[
                'postId' => 'required'
            ]);
    
            $likes = Like::where(["id_post"=>$request->input('postId'), "id_user"=>Auth::id()])->get();
            
            $message = "Post Unable to Liked";
            if(count($likes)==0){
                $likes = new Like();
                $likes->id_user = Auth::id();
                $likes->id_post = $request->input('postId');
        
                if($likes->save()){
                    $message = "Post Liked";
                }
            }
            return response()->json(['message'=> $message],200);
        }

        public function getPostunLike(Request $request){
            $this->validate($request,[
                'postId' => 'required'
            ]);
    
            $likes = Like::where(["id_post"=>$request->input('postId'), "id_user"=>Auth::id()])->get();
           
            $likes[0]->delete();
    
            $message = "Post UnLiked";
            
            return response()->json(['message'=> $message],200);
        }

}
