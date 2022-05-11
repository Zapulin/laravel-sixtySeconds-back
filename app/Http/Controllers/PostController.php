<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Visibilidad;
use App\Models\Tematica;
use App\Services\CommentChainService;
use App\Http\Responses\GetPostResponse;
use App\Services\FileSystem;
use Response;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
  
    protected CommentChainService $commentChainService;
    protected FileSystem $fileSystem;
    public function __construct(CommentChainService $_commentChainService, FileSystem $_fileSystem)
    {
        $this->commentChainService = $_commentChainService;
        $this->fileSystem = $_fileSystem;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $returnCode = 200;
        $posts = Post::orderBy('idPost','desc')->offset($request->input('skip',0))->take($request->input('take',25))->get();
        $getPostResponse = new GetPostResponse($this->commentChainService);
        if(!empty($posts))
            $response =  $getPostResponse->getResponseFromMultiplePost($posts);
        else
        {
            $response[] = ["error" => "Post not found"];
            $returnCode = 404;
        }
        return Response::json($response, $returnCode); // Status code here

    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        //Log::channel('stderr')->info("Inserción de datos");
        //Log::channel('stderr')->info($request);

        $response = [];
        try{
            DB::beginTransaction();
            $post = new Post();
            //$post->idPost = $request->id;
            $post->idUsuario = $request->user()->idUsuario;
            $visibilidad = Visibilidad::where('Visibilidad', $request->visibility )->first();

            if(!isset($visibilidad) || empty($visibilidad))
            {
                $visibilidad = Visibilidad::where('Visibilidad','Public')->first();
            }
       
            $audio = $this->fileSystem->saveFile($request->file('file'),$visibilidad->idVisibilidad);
            $post->idAudio = $audio->idAudio;
            $post->Titulo = $request->title;

            $post->idVisibilidad = $visibilidad->idVisibilidad;
            $post->FechaCreacion = $request->creationDate;
            $post->Likes = 0;
            $post->Dislikes = 0;

            if($post->save())
            {
                if(isset($request->category) && !empty($request->category))
                {
                    $tematicas = explode(',',$request->category);
                    //Log::channel('stderr')->info('Guardado Correcto');
                    foreach($tematicas as $tematica)
                    {
                        $tematica = Tematica::where('Nombre', $tematica)->first();

                        if(isset($tematica))
                            $post->Tematica()->attach($tematica->idTematica);
                        //$user->roles()->attach($roleId, ['expires' => $expires]);
                    }
                }
                $response = [ 'id' => $post->idPost];

            }
            else
            {
                throw new Exception ('Error al almacenar el post');
            }


            DB::commit();
       }
        catch(Exception $e){
            DB::rollback();
            
            return  Response::json($e, 500); // Status code here

        }
        return Response::json($response, 201); // Status code here

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($idPost)
    {
        $post = Post::where('idPost',$idPost)->first();
        $getPostResponse = new GetPostResponse($this->commentChainService);
        $response = [];
        $returnCode = 200;
        if(!empty($post))
            $response =  $getPostResponse->getResponseFromMultiplePost($post);
        else
        {
            $response[] = ["error" => "Post not found"];
            $returnCode = 404;
        }
        return Response::json($response, $returnCode); // Status code here

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request)
    {
        $response = [];
        try{

            DB::beginTransaction();
   
            $post = Post::where('idPost',$request->post_id)->first();
            //$post->idPost = $request->id;

                if($post->Usuario->idUsuario != $request->user()->idUsuario)
                {
                    DB::rollback();
                    $response[] = ['message' => 'Error: No estas autorizado para editar este post.'];
                    return  Response::json($response, 401); // Status code here
                }
            if($request->visibility)
                $visibilidad = Visibilidad::where('Visibilidad', $request->visibility )->first();

            if(!isset($visibilidad) || empty($visibilidad))
            {
                $visibilidad = $post->Visibilidad ;
            }
            // Se podrá cambiar el audio?
            //$audio = $this->fileSystem->saveFile($request->file('file'),$visibilidad->idVisibilidad);
            //$post->idAudio = $audio->idAudio;
            if(isset($request->title))
                $post->Titulo = $request->title;

            $post->idVisibilidad = $visibilidad->idVisibilidad;
            //$post->FechaCreacion = $request->creationDate;
            //$post->Likes = 0;
            //$post->Dislikes = 0;

            if($post->save())
            {
                if(isset($request->category))
                {
                    $tematicasNewNombre = explode(',',$request->category);

                    $tematicasOld = [];
                    $tematicasNew =  Tematica::whereIn('Nombre',$tematicasNewNombre)->pluck('idTematica')->toArray();
                    $tematicasOld = $post->Tematica()->get()->pluck('idTematica')->toArray();

                    $tematicasAEliminar = array_diff($tematicasOld,$tematicasNew);
                    $tematicasAInsertar = array_diff($tematicasNew,$tematicasOld);

                    $post->Tematica()->detach($tematicasAEliminar);
                    $post->Tematica()->attach($tematicasAInsertar);


                }
                $response = [ 'id' => $post->idPost];

            }
            else
            {
                throw new Exception ('Error al almacenar el post');
            }


            DB::commit();
       }
        catch(Exception $e){
            DB::rollback();
            
            return  Response::json($e, 500); // Status code here
        }
        return Response::json($response, 200); // Status code here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,$idPost)
    {
        $response = [];
        try{

            DB::beginTransaction();
            $post = Post::where('idPost',$idPost)->first();
                             
            if($post != null && $post->exists() )
            {
                if($post->Usuario->idUsuario != $request->user()->idUsuario)
                {
                    DB::rollback();
                    $response[] = ['message' => 'Error: No estas autorizado para eliminar este post.'];
                    return  Response::json($response, 401); // Status code here
                }
                if($post->Tematica()->exists())
                    $post->Tematica()->detach($post->Tematica()->get()->pluck('idTematica')->toArray());

                if( $post->Comentarios != null)
                {

                    $comentarios = $post->Comentarios()
                                        ->whereNotNull('idComentarioPadre')
                                        ->orderBy('idComentarioPadre', 'desc')
                                        ->get();
                                 // ->get();
                    //Log::channel('stderr')->info('Comentarios'.json_encode($comentarios));
                    foreach($comentarios as $comentario)
                    {
                        $comentario->delete();
                    }
                    $post->Comentarios()->delete();
                }
              
                $post->delete();
                if($post->Audio()->exists())
                    $post->Audio->delete();
                $response[] = ['message' => 'Post eliminado correctamente'];
            }
            else{
                $response[] = ['message' => 'El post a eliminar no existe'];

            }
            DB::commit();

        }
        catch(Exception $e){
            DB::rollback();
            return  Response::json($e, 500); // Status code here
        }
        return Response::json($response, 200); // Status code here
    }
    public function addComment(Request $request)
    {
        $response = [];
        try{
            
            DB::beginTransaction();
            $comentario = new Comentario();
            $comentario->idUsuario = $request->user()->idUsuario;
            $comentario->FechaCreacion = $request->creationDate;
            $comentario->idPost = $request->postID;
            $visibilidad = Visibilidad::where('Visibilidad', $request->visibility )->first();
                    
            if(!isset($visibilidad) || empty($visibilidad))
            {
                $visibilidad = $post->Visibilidad;
            }

            $audio = $this->fileSystem->saveFile($request->file('file'),$visibilidad->idVisibilidad);
            $comentario->idAudio = $audio->idAudio;
            DB::commit();
            $comentario->Likes = 0;
            $comentario->Dislikes = 0;
            $comentario->idComentarioPadre = $request->parentId;

            if($comentario->save())
            {
                
                $response = [ 'id' => $comentario->idComentario];

            }
            else
            {
                throw new Exception ('Error al almacenar el post');
            }
            DB::commit();
            
        }
        catch(Exception $e){
            DB::rollback();
            
            return  Response::json($e, 500); // Status code here
        }
        return Response::json($response, 200); // Status code here
       
    }
    public function destroyComment(Request $request,$idComment)
    {
         $response = [];
        try{
            
            DB::beginTransaction();
            $comentario = Comentario::where('idcomentario',$idComment)->first();
            
            if($comentario != null && $comentario->exists())
            {
                
                 if($comentario->Usuario->idUsuario != $request->user()->idUsuario)
                {
                    DB::rollback();
                    $response[] = ['message' => 'Error: No estas autorizado para eliminar este comentario.'];
                    return  Response::json($response, 401); // Status code here
                }
                 
                if( $comentario->Comentarios != null)
                {
                    this->deleteCommentChildren($comentario->Comentarios);
                }
                if($post->Audio()->exists())
                    $post->Audio->delete();
                $comentario->delete();
                $response[] = ['message' => 'Post eliminado correctamente'];
            }
            else{
                $response[] = ['message' => 'El post a eliminar no existe'];

            }
            DB::commit();

        }
        catch(Exception $e){
            DB::rollback();
            
            return  Response::json($e, 500); // Status code here
        }
        return Response::json($response, 200); // Status code here
    
    }
    protected function deleteCommentChildren($comentarios)
    {
        foreach($comentarios as $comentario)
        {
            if($comentario->Comentarios != null)
            {
                this->deleteCommentChildren($comentario->Comentarios());
            }
            $comentario->delete();
        }
    }

}
