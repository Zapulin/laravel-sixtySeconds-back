<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\Audio;
use App\Models\Comentario;
use App\Models\Usuario;
use App\Models\Post;
use App\Models\Tematica;
use App\Models\TematicaPost;
use Datetime;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\CommentChainService;
use App\Http\Responses\GetPostResponse;
use App\Models\Visibilidad;
use DB;
class GetPostResponseTest extends TestCase
{
   

    public function test_getPostResponse()
    {
        $post =//Post::take(1)->get();
              Post::factory()             
              ->create([
                  'idVisibilidad' => Visibilidad::inRandomOrder()->take(1)->value('idVisibilidad'),
                  'Visualizaciones' => rand(0,100),
                  'idUsuario' =>Usuario::inRandomOrder()->take(1)->value('idUsuario'),
                  'idAudio' => Audio::inRandomOrder()->take(1)->value('idAudio'),
                  'FechaCreacion' => '18-08-1996',
                  'Dislikes' => rand(0,500),
                  'Likes' => rand(0,500),
                  'Titulo' => 'Test Get Post Response'
              ]);
        $tematicaPost = TematicaPost::factory()->for($post,"Post")->for(Tematica::inRandomOrder()->take(1)->first(),"Tematica")->create();
        $comments = Comentario::factory()->for($post,"Post")->count(3)->create([
            'FechaCreacion' => '18-08-1996',
            'Likes' => rand(0,100),
            'Dislikes' => rand(0,100),
            'idAudio' => Audio::inRandomOrder()->take(1)->value('idAudio'),
            'idComentarioPadre' => null,
            'idUsuario' => Usuario::inRandomOrder()->take(1)->value('idUsuario'),
           
            
        ]);
        $dataArr = array();
        foreach($comments as $comentario)
        {
            for($i = 0; $i<= rand(1,5);$i++)
            {
                $data = Comentario::factory()->for($comentario,'Padre')->create([
                    'FechaCreacion' => now(),
                    'Likes' => rand(0,100),
                    'Dislikes' => rand(0,100),
                    'idAudio' => Audio::inRandomOrder()->take(1)->value('idAudio'),
                    'idUsuario' => Usuario::inRandomOrder()->take(1)->value('idUsuario'),
                    'idPost' => $comentario->idPost,
            
                ]);
            
                $this->assertTrue($data!= null,'Data is null');
                $dataArr[] = $data;
            }
            
        }
        
        
        $commentChainService = new CommentChainService();
        $getPostResponse = new GetPostResponse($commentChainService);
        $result = $getPostResponse->getResponseFromMultiplePost($post);
        //Log::channel('stderr')->info(json_encode($result));            
        $this->assertTrue($result != null, 'Result is null');
        foreach($result as $item)
        {
            $this->assertTrue($item != null && !empty($item),'Data is null');
        }
        $tematicaPost->delete();
        foreach($dataArr as $comment)
            $comment->delete();
        foreach($comments as $comment)
            $comment->delete();
        $post->delete();
        
    }
    public function test_borrar_tematicas_sobrantes()
    {
        /*
          "Humor"
          "Deportes"
          "Noticias"
          "Música"
          "Ocio"
          "Ciencia"
          "Cultura"
          "Politica"
        */
        try{
            DB::beginTransaction();
        
            //Arrange
            $post = Post::factory()->create();
            $tematicas = Tematica::whereIn('Nombre',['Deportes','Politica','Ocio'])->get();
            
            foreach($tematicas as $tematica)
            {
                $post->Tematica()->attach($tematica->idTematica);
            }
          
            // Act
            $tematicasOld = [];
            $tematicasNew =  Tematica::whereIn('Nombre',['Deportes' ,'Ciencia' ,'Ocio'])->pluck('idTematica')->toArray();
            $tematicasOld = $post->Tematica()->get()->pluck('idTematica')->toArray();
            /*
            foreach($post->Tematica()->get() as $tematica)
            {
                Log::channel('stderr')->info(json_encode($tematica));
                $tematicasOld[] = $tematica->idTematica;
                }*/
            $tematicaAEliminar = array_diff($tematicasOld,$tematicasNew);
            $tematicaAInsertar = array_diff($tematicasNew,$tematicasOld);

            $post->Tematica()->detach($tematicaAEliminar);
            $post->Tematica()->attach($tematicaAInsertar);
         
        
            // Assert
            $this->assertTrue(count(array_diff($post->Tematica()->get()->pluck('idTematica')->toArray(),$tematicasNew)) == 0);
            DB::rollback();
        }
        catch(Exception $e)
        {
            DB::rollback();
        }
        
    }
    public function test_borrar_tematicas_sobrantes_noInitialdata()
    {
        /*
          "Humor"
          "Deportes"
          "Noticias"
          "Música"
          "Ocio"
          "Ciencia"
          "Cultura"
          "Politica"
        */
        try{
            DB::beginTransaction();
        
            //Arrange
            $post = Post::factory()->create();
            $tematicas = Tematica::whereIn('Nombre',['Deportes','Politica','Ocio'])->get();
            /*
            foreach($tematicas as $tematica)
            {
                $post->Tematica()->attach($tematica->idTematica);
                }*/
          
            // Act
            $tematicasOld = [];
            $tematicasNew =  Tematica::whereIn('Nombre',['Deportes' ,'Ciencia' ,'Ocio'])->pluck('idTematica')->toArray();
            $tematicasOld = $post->Tematica()->get()->pluck('idTematica')->toArray();
            /*
            foreach($post->Tematica()->get() as $tematica)
            {
                Log::channel('stderr')->info(json_encode($tematica));
                $tematicasOld[] = $tematica->idTematica;
                }*/
            $tematicaAEliminar = array_diff($tematicasOld,$tematicasNew);
            $tematicaAInsertar = array_diff($tematicasNew,$tematicasOld);

            $post->Tematica()->detach($tematicaAEliminar);
            $post->Tematica()->attach($tematicaAInsertar);
         
        
            // Assert
            $this->assertTrue(count(array_diff($post->Tematica()->get()->pluck('idTematica')->toArray(),$tematicasNew)) == 0);
            DB::rollback();
        }
        catch(Exception $e)
        {
            DB::rollback();
        }
        
    }
    public function test_borrar_tematicas_sobrantes_noNewdata()
    {
        /*
          "Humor"
          "Deportes"
          "Noticias"
          "Música"
          "Ocio"
          "Ciencia"
          "Cultura"
          "Politica"
        */
        try{
            DB::beginTransaction();
        
            //Arrange
            $post = Post::factory()->create();
            $tematicas = Tematica::whereIn('Nombre',['Deportes','Politica','Ocio'])->get();
            
            foreach($tematicas as $tematica)
            {
                $post->Tematica()->attach($tematica->idTematica);
            }
          
            // Act
            $tematicasOld = [];
            $tematicasNew = [];// Tematica::whereIn('Nombre',['Deportes' ,'Ciencia' ,'Ocio'])->pluck('idTematica')->toArray();
            $tematicasOld = $post->Tematica()->get()->pluck('idTematica')->toArray();
            /*
            foreach($post->Tematica()->get() as $tematica)
            {
                Log::channel('stderr')->info(json_encode($tematica));
                $tematicasOld[] = $tematica->idTematica;
                }*/
            $tematicaAEliminar = array_diff($tematicasOld,$tematicasNew);
            $tematicaAInsertar = array_diff($tematicasNew,$tematicasOld);

            $post->Tematica()->detach($tematicaAEliminar);
            $post->Tematica()->attach($tematicaAInsertar);
         
        
            // Assert
            $this->assertTrue(count(array_diff($post->Tematica()->get()->pluck('idTematica')->toArray(),$tematicasNew)) == 0);
            DB::rollback();
        }
        catch(Exception $e)
        {
            DB::rollback();
        }
        
    }
}
