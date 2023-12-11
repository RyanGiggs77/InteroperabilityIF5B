<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    // authozitation
    // check if current user is authorized to do this action

    public function index()
    {
    
    if (Gate::denies('read-post')) {
        return response()->json([
            'success' => false,
            'status' => 403,
            'message' => 'you are unauthorized'
        ], 403);
    }
    
    if(Auth::user()->role ==='admin') {
        $posts = Post::orderBy("id", "DESC")->paginate(2)->toArray();
    } else {
        $posts = Post::Where(['user_id' => Auth::user()->id])->OrderBy("id","DESC")->paginate(2)->toArray();
    }

    $response = [
        "total_count" => $posts["total"],
        "limit" => $posts["per_page"],
        "paginate" => [
            "next_page" => $posts["next_page_url"],
            "current_page" => $posts["current_page"]
        ],
        "data" => $posts["data"],
    ];

    return response()->json($response, 200);
    }

    public function image($imageName){
        $imagePath = storage_path('uploads/image_profile') . '/' . $imageName;
        if (file_exists($imagePath)) {
            $file = file_get_contents($imagePath);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
        }
    }

    public function video($videoName){
        $videoPath = storage_path('uploads/video_profile') . '/' . $videoName;
        if (file_exists($videoPath)) {
            $file = file_get_contents($videoPath);
            return response($file, 200)->header('Content-Type', 'video/mp4');
        }
    }


    public function store(Request $request)
    {
        $input = $request->all();

        if(Gate::denies('store-post')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'you are unauthorized'
            ], 403);
        }
        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|numeric',
            'categories_id' => 'required|numeric',
            'students_id' => 'required|numeric',
        ];

        $validator = Validator::make($input, $validationRules);


        $acceptHeader = $request->header('Accept');

         
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            // $contentTypeHeader = $request->header('Content-Type');

            // if ($contentTypeHeader === 'application/json') {

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $post = Post::create($input);

                if ($request->hasFile('image')) {
                    $firName = str_replace(' ', '_', $input['title']);
        
                    $imgName = $post->id . '_' . $firName . '_' . 'image';
                    $request->file('image')->move(storage_path('uploads/image_profile'), $imgName);
        
                    // Delete the previous image
                    $current_image_path = storage_path('avatar') . '/' . $post->image;
                    if (file_exists($current_image_path)) {
                        unlink($current_image_path);
                    }
        
                    $post->image = $imgName;
                }

                if ($request->hasFile('video')) {
                    $firName = str_replace(' ', '_', $input['title']);
        
                    $vidName = $post->id . '_' . $firName . '_' . 'video';
                    $request->file('video')->move(storage_path('uploads/video_profile'), $vidName);
        
                    // Delete the previous video
                    $current_video_path = storage_path('avatar') . '/' . $post->video;
                    if (file_exists($current_video_path)) {
                        unlink($current_video_path);
                    }
        
                    $post->video = $vidName;
                }

                
                return response()->json($post, 200);
            // }
            // else if ($contentTypeHeader === 'application/xml') {
            //     $xml = new \SimpleXMLElement($request->getContent());
                
            //     if ($xml === false) {
            //         return response('Invalid XML', 400);
            //     }
        
            //     $post = new Post([
            //         'id' => $xml->id,
            //         'title' => $xml->title,
            //         'status' => $xml->status,
            //         'content' => $xml->content,
            //         'user_id' => $xml->user_id,
            //         'categories_id' => $xml->categories_id,
            //         'students_id' => $xml->students_id,
            //     ]);
            
            //     if ($post->save()) {
            //     return $xml = $post->asXml();
            //     } else {
            //         return response('Gagal menyimpan data ke database', 500);
            //     }
            // } 
            // else {
            //     return response('Unsupported Media Type', 415);
            // }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function show($id)
    {
        $post = Post::find($id);
        $acceptHeader = request()->header('Accept');

        if(Gate::denies('show-post', $post)){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'you are unauthorized'
            ],403);
        }

        if($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            

            if(!$post){
                abort(404);
            }

            if($acceptHeader === 'application/json'){
                return response()->json($post,200);
            }
            else{
                $xml = new \SimpleXMLElement('<post/>');
                $xml->addChild('id',$post->id);
                $xml->addChild('title',$post->title);
                $xml->addChild('status',$post->status);
                $xml->addChild('content',$post->content);
                $xml->addChild('user_id',$post->user_id);
                $xml->addChild('categories_id',$post->categories_id);
                $xml->addChild('students_id',$post->students_id);
                $xml->addChild('created_at',$post->created_at);
                $xml->addChild('updated_at',$post->updated_at);

                return $xml->asXML();
            }
        }
        else{
            return response('Not Acceptable!',406);
        }
        
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $post = Post::find($id);

        if (!$post) {
            abort(404);
        }

        if (Gate::denies('update-post', $post)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'you are unauthorized'
            ], 403);
        }

        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|numeric',
            'categories_id' => 'required|numeric',
            'students_id' => 'required|numeric',
        ];

        $validator = Validator::make($input,$validationRules);

        if ($validator -> fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post->fill($input);

        
        $post->save();

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $acceptHeader = request()->header('Accept');

       if(Gate::denies('destroy-post',$post)) {
              return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'you are unauthorized'
              ], 403);
       }

       if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
           

           if (!$post) {
               abort(404);
           }

           $post->delete();

           $message = ['message' => 'deleted successfully', 'post_id' => $id];

           if ($acceptHeader === 'application/json') {
               return response()->json($message, 200);
           } else {
               $xml = new \SimpleXMLElement('<message/>');
               $xml->addChild('message', 'deleted successfully');
               $xml->addChild('post_id', $id);

               return $xml->asXML();
           }
       } else {
           return response('Not Acceptable!', 406);
       }
    }
}
