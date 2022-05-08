<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\Audio;
use App\Models\Comentario;
use App\Models\Usuario;
use App\Models\Post;
use Datetime;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\FileSystem;
use App\Services\CommentChainService;

class CommentChainServiceTests extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }
   
    public function test_parseCommentsToResponse()
    {
        //Obtenemos un audio para utilizar
        $commentChainService = new CommentChainService();
        $comments = Comentario::factory()->count(5)->create([
            'FechaCreacion' => '18-08-1996',
            'Likes' => rand(0,100),
            'Dislikes' => rand(0,100),
            'idAudio' => Audio::inRandomOrder()->take(1)->value('idAudio'),
            'idComentarioPadre' => null,
            'idUsuario' => Usuario::inRandomOrder()->take(1)->value('idUsuario'),
            'idPost' => Post::inRandomOrder()->take(1)->value('idPost'),
            
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
        //Log::channel('stderr')->info(json_encode($comments));            
        $result = $commentChainService->parseCommentsToResponse($comments);
        //Log::channel('stderr')->info(json_encode($result));            


        //Proar el metodo de ParseCommentstoresponse
        foreach($dataArr as $data)
        {
            $data->delete();
        }
        foreach($comments as $comment)
        {
            $comment->delete();
        }
   
    }
    
    /*SELECT "idComentario", "idUsuario", "idComentarioPadre", "idPost", "idAudio", "FechaCreacion", "Likes", "Dislikes"
	FROM "default"."Comentarios";*/
    public function test_parseCommentsToResponse_simpleCases()
    {
        //Obtenemos un audio para utilizar
        $commentChainService = new CommentChainService();
        $comments = Comentario::factory()->count(5)->create([
            'FechaCreacion' => '18-08-1996',
            'Likes' => rand(0,100),
            'Dislikes' => rand(0,100),
            'idAudio' => Audio::inRandomOrder()->take(1)->value('idAudio'),
            'idComentarioPadre' => null,
            'idUsuario' => Usuario::inRandomOrder()->take(1)->value('idUsuario'),
            'idPost' => Post::inRandomOrder()->take(1)->value('idPost'),
            
        ]);
            
        $result = $commentChainService->parseCommentsToResponse($comments);
        $this->assertTrue(count($result) == 5, 'No se han procesado todos los comentarios');
        //Proar el metodo de ParseCommentstoresponse
        
        foreach($comments as $comment)
        {
            $comment->delete();
        }
   
    }
    public function test_parseCommentsToResponse_noCases()
    {
        //Obtenemos un audio para utilizar
        $commentChainService = new CommentChainService();
        $comments = [];
            
        $result = $commentChainService->parseCommentsToResponse($comments);
        $this->assertTrue(count($result) == 0, 'No debe procesarse nada');
        //Proar el metodo de ParseCommentstoresponse
        
        foreach($comments as $comment)
        {
            $comment->delete();
        }
   
    }
}
