<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Audio>
 */
class AudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'Url'=> '/media/yeraycampossimon/Yeray/DatosTest',
            //'Server'=>'local',
            'ShortUrl' => Str::random(1).'-'.Str::random(1).'-'.Str::random(1),
            'FechaCreacion'=>now(),
            'Tamano'=> 3000,
            'ClaveDesbloqueo' => 'xnslcgwoeyofiehwvie',
            'idVisibilidad' => 1
            //
        ];
    }
}
