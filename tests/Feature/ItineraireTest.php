<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Itineraire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItineraireTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->user = User::factory()->create([
            'email' => 'ahmed@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'ahmed@gmail.com',
            'password' => '123456',
        ]);

        $this->token = $response->json('token');
    }

    // Test 1 - Créer un itinéraire
    public function test_user_can_create_itineraire()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson('/api/itineraires', [
            'titre' => 'Voyage Marrakech',
            'categorie' => 'monument',
            'duree' => 3,
            'image' => UploadedFile::fake()->image('test.jpg'),
            'destinations' => [
                [
                    'nom' => 'Marrakech',
                    'logement' => 'Hotel Atlas',
                    'items' => [['nom' => 'Jemaa el-Fna', 'type' => 'endroit']],
                ],
                [
                    'nom' => 'Essaouira',
                    'logement' => 'Riad Mogador',
                    'items' => [['nom' => 'Tagine', 'type' => 'plat']],
                ],
            ],
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'itineraire']);
    }

    // Test 2 - Créer sans token
    public function test_user_cannot_create_itineraire_without_token()
    {
        $newClient = $this->refreshApplication();

        $response = $this->withHeaders(['Authorization' => 'Bearer invalid_token'])->postJson('/api/itineraires', [
            'titre' => 'Voyage Marrakech',
            'categorie' => 'monument',
            'duree' => 3,
            'image' => UploadedFile::fake()->image('test.jpg'),
            'destinations' => [
                [
                    'nom' => 'Marrakech',
                    'logement' => 'Hotel Atlas',
                    'items' => [['nom' => 'Jemaa el-Fna', 'type' => 'endroit']],
                ],
                [
                    'nom' => 'Essaouira',
                    'logement' => 'Riad Mogador',
                    'items' => [['nom' => 'Tagine', 'type' => 'plat']],
                ],
            ],
        ]);

        $response->assertStatus(401);
    }

    // Test 3 - Voir tous les itinéraires
    public function test_user_can_see_all_itineraires()
    {
        $response = $this->getJson('/api/itineraires');
        $response->assertStatus(200);
    }

    // Test 4 - Modifier son itinéraire
    public function test_user_can_update_own_itineraire()
    {
        $itineraire = Itineraire::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->putJson('/api/itineraires/' . $itineraire->id, [
            'titre' => 'Nouveau titre',
            'categorie' => 'plage',
            'duree' => 5,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message', 'itineraire']);
    }

    // Test 5 - Modifier itinéraire d'un autre user
    public function test_user_cannot_update_other_itineraire()
    {
        $otherUser = User::factory()->create();
        $itineraire = Itineraire::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->putJson('/api/itineraires/' . $itineraire->id, [
            'titre' => 'Nouveau titre',
        ]);

        $response->assertStatus(403);
    }

    // Test 6 - Supprimer son itinéraire
    public function test_user_can_delete_own_itineraire()
    {
        $itineraire = Itineraire::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->deleteJson('/api/itineraires/' . $itineraire->id);

        $response->assertStatus(200);
    }

    // Test 7 - Ajouter aux favoris
    public function test_user_can_add_itineraire_to_favoris()
    {
        $itineraire = Itineraire::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson('/api/itineraires/' . $itineraire->id . '/favori');

        $response->assertStatus(200)->assertJson(['message' => 'Ajouté aux favoris']);
    }
}
