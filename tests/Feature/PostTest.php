<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testPostCreatedSuccesfully()
    {
        Storage::fake('uploads');

        $user = User::factory()->make();
        $this->actingAs($user, 'api');
        $postData = [
            "title" => "Nuevo Titulo",
            "content" => "Nuevo contenido",
            "cover" => $file = UploadedFile::fake()->image('image.jpg', 600, 600)
        ];


        $this->json('POST', 'api/posts', $postData, ["Accept" => "application/json", "Content-Type" => "application/x-www-form-urlencoded"])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    "title" => "Nuevo Titulo",
                    "content" => "Nuevo contenido",
                ],
                "msj" => "Post created Successfully"
            ]);
    }
}
