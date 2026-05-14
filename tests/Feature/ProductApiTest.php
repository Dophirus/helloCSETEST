<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test la creation d'un produit
     */
    public function test_can_create_product(): void
    {
        // On crée une category
        $category = Category::factory()->create();

        // On prepare les données de notre produit
        $payload = [
            'category_id' => $category->id,
            'name' => 'product',
            'price' => 99900,
            'status' => ProductStatus::ONLINE->value,
        ];

        // On envoie la requete
        $response = $this->postJson('/api/products', $payload);

        // On verifie que les données sont bien dans la base de données
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'product');
    }

    /**
     * TEST : GET (liste de produits)
     */
    public function test_can_get_all_products(): void
    {
        // On crée 5 produits
        Product::factory()->count(5)->create();

        // On envoie la requete
        $response = $this->getJson('/api/products');

        // On verifie que les données sont bien créées
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /**
     * TEST : UPDATE
     */
    public function test_can_update_product(): void
    {
        // On crée un produit
        $product = Product::factory()->create(['name' => 'name']);

        // On envoie la requete en modifiant son nom
        $response = $this->putJson("/api/products/{$product->id}", [
            'category_id' => $product->category_id,
            'name' => 'new name',
            'price' => 500,
            'status' => ProductStatus::DISABLED->value,
        ]);

        // On verifie son nouveau nom
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'new name');
    }

    /**
     * TEST : DELETE
     */
    public function test_can_delete_product(): void
    {
        // On crée un produit
        $product = Product::factory()->create();

        // On envoie la requete
        $this->deleteJson("/api/products/{$product->id}")->assertStatus(204);

        // On verifie que le produit est bien supprimé
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_can_filter_products_by_category(): void
    {
        // On crée 2 categories
        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();

        // On crée 2 produits
        Product::factory()->create(['category_id' => $cat1->id]);
        Product::factory()->create(['category_id' => $cat2->id]);

        // On demande uniquement la catégorie 1
        $response = $this->getJson("/api/products?category_id={$cat1->id}");

        // On verifie que les données sont bien créées
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.category_id', $cat1->id);
    }
}
