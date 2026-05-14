<?php

namespace Tests\Feature;

use App\Enums\CategoryStatus;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test la creation d'une categorie
     */
    public function test_can_create_category(): void
    {
        // Preparation des données
        $payload = [
            'name' => 'name',
            'image' => 'https://example.com/image.jpg',
            'status' => CategoryStatus::ONLINE->value,
        ];

        // Execution de la requete
        $response = $this->postJson('/api/categories', $payload);

        // Verifications
        $response->assertStatus(201)
            ->assertJsonPath('name', 'name');

        // Verifie que les données sont bien dans la base de données
        $this->assertDatabaseHas('categories', [
            'name' => 'name',
            'image' => 'https://example.com/image.jpg',
            'status' => CategoryStatus::ONLINE->value,
        ]);
    }

    /**
     * TEST : GET (une seule categorie)
     */
    public function test_can_get_single_category(): void
    {
        // On cree une categorie
        $category = Category::factory()->create(['name' => 'name']);

        // On envoie la requete
        $response = $this->getJson("/api/categories/{$category->id}");

        // On verifie que les données sont bien créées
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'name');
    }


    /**
     * TEST : GET (liste de categories)
     */
    public function test_can_get_all_categories(): void
    {
        // On cree 3 categories
        Category::factory()->count(3)->create();

        // On envoie la requete
        $response = $this->getJson('/api/categories');

        // On verifie que les données sont bien créées
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * TEST : UPDATE
     */
    public function test_can_update_category(): void
    {
        // On cree une categorie
        $category = Category::factory()->create(['name' => 'name']);

        // On ajoute un nouveau nom a notre categorie
        $payload = [
            'name' => 'new name',
            'status' => CategoryStatus::DISABLED->value,
        ];

        // On envoie la requete
        $response = $this->putJson("/api/categories/{$category->id}", $payload);

        // On verifie si le nouveau nom est bien ajouté
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'new name',
            'status' => CategoryStatus::DISABLED->value
        ]);
    }

    /**
     * TEST : DELETE
     */
    public function test_can_delete_category(): void
    {
        // On cree une categorie
        $category = Category::factory()->create();

        // On envoie la requete
        $response = $this->deleteJson("/api/categories/{$category->id}");

        // On verifie que la categorie est bien supprimée
        $response->assertStatus(204); // No Content
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test la validation lors de la creation
     */
    public function test_create_category_validation_fails_without_name(): void
    {
        // On envoie la requete vide
        $response = $this->postJson('/api/categories', []);

        // On attend une erreur
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test que la creation echoue avec un statut invalide
     */
    public function test_create_category_fails_with_invalid_status(): void
    {
        // On prepare les données avec un faux status
        $payload = [
            'name' => 'Test',
            'status' => 'not-a-status',
        ];

        // On envoie la requete
        $response = $this->postJson('/api/categories', $payload);

        // On attend une erreur
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
