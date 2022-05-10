<?php

namespace Tests\Feature;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Models\Audio;
use App\Models\Usuario;
use App\Models\Post;
use App\Models\Tematica;
use App\Models\Visibilidad;
use DB;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

    }
    public function test_get_posts()
    {

        $response = $this->get('/api/posts');
        //Log::channel('stderr')->info(json_encode($response));
        $response->assertStatus(200);
    }
    public function test_create_post()
    {
        $file =  Storage::disk('local')->get('/test/testAudio.mp3');

        $response = $this->post('api/post/create',[
            'file' => $file,
            'userId' => Usuario::inRandomOrder()->take(1)->value('idUsuario'),
            'title' => 'TestCreate',
            'visibility' => 'Public',
            'category' => 'Politica,Deportes',
            'creationDate' => '18-08-1996'
        ]);
        //Log::channel('stderr')->info(json_encode($response));
        $this->assertTrue(isset($response) && is_numeric($response->getData()->id));
    }
    public function test_update_post()
    {
        try{
            DB::beginTransaction();
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

            $post->Tematica()->attach(Tematica::inRandomOrder()->take(2)->pluck('idTematica'));

            $response = $this->post('api/post/update',[

                //'userId' => Usuario::inRandomOrder()->take(1)->value('idUsuario'),
                'id'=> $post->idPost,
                'title' => 'TestCreate2',
                'visibility' => 'Private',
                'category' => 'Politica,Ciencia,Deportes',
                'creationDate' => '18-08-2020'
            ]);
            Log::channel('stderr')->info(json_encode($response));
            $this->assertTrue(isset($response) && is_numeric($response->getData()->id));
            DB::rollback();
        }
        catch(Exception $e)
        {
            DB::rollback();
        }
    }
}
