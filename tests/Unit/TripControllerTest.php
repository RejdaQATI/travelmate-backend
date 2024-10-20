<?php

use Tests\TestCase;
use App\Models\User; 
use App\Models\Trip; 
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_trips()
    {
        Trip::factory()->count(3)->create();
        $response = $this->getJson('/api/trips');
        $response->assertStatus(200)
                ->assertJsonStructure([
                'trips' => [
                '*' => ['id', 'title', 'destination'],
                ],
            ]);
    }

    public function test_admin_can_create_trip()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);
    $data = [
        'title' => 'Voyage à Paris',
        'description' => 'Une belle aventure en Europe.',
        'pack_type' => 'standard',
        'destination' => 'Europe',
        'duration' => 7,
    ];

    $response = $this->postJson('/api/trips', $data);
    $response->assertStatus(201)
            ->assertJson([
                'trip' => [
                'title' => 'Voyage à Paris',
                'destination' => 'Europe',
                ],
            ]);
    $this->assertDatabaseHas('trips', ['title' => 'Voyage à Paris']);
}

public function test_non_admin_cannot_create_trip()
{
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $data = [
        'title' => 'Voyage à Paris',
        'description' => 'Une belle aventure en Europe.',
        'pack_type' => 'standard',
        'destination' => 'Europe',
        'duration' => 7,
    ];

    $response = $this->postJson('/api/trips', $data);
    $response->assertStatus(403)
            ->assertJson(['error' => 'Accès refusé. Vous devez être administrateur.']);
}


public function test_admin_can_update_trip()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $trip = Trip::factory()->create([
        'title' => 'Voyage à Paris',
        'destination' => 'Europe',
    ]);
    $this->actingAs($admin);
    $data = [
        'title' => 'Voyage à Tokyo',
        'destination' => 'Asie',
    ];

    $response = $this->putJson('/api/trips/' . $trip->id, $data);
    $response->assertStatus(200)
            ->assertJson([
                'trip' => [
                'title' => 'Voyage à Tokyo',
                'destination' => 'Asie',
                ],
            ]);
    $this->assertDatabaseHas('trips', ['title' => 'Voyage à Tokyo']);
}

public function test_non_admin_cannot_update_trip()
{
    $user = User::factory()->create(['role' => 'user']);
    $trip = Trip::factory()->create([
        'title' => 'Voyage à Paris',
        'destination' => 'Europe',
    ]);
    $this->actingAs($user);

    $data = [
        'title' => 'Voyage à Tokyo',
    ];

    $response = $this->putJson('/api/trips/' . $trip->id, $data);
    $response->assertStatus(403)
            ->assertJson(['error' => 'Accès refusé. Vous devez être administrateur.']);
}
public function test_admin_can_delete_trip()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $trip = Trip::factory()->create([
        'title' => 'Voyage à Paris',
        'destination' => 'Europe',
    ]);
    $this->actingAs($admin);
    $response = $this->deleteJson('/api/trips/' . $trip->id);
    $response->assertStatus(200)
            ->assertJson(['message' => 'Voyage supprimé avec succès']);
    $this->assertDatabaseMissing('trips', ['id' => $trip->id]);
}

public function test_non_admin_cannot_delete_trip()
{
    $user = User::factory()->create(['role' => 'user']);
    $trip = Trip::factory()->create([
        'title' => 'Voyage à Paris',
        'destination' => 'Europe',
    ]);
    $this->actingAs($user);

    $response = $this->deleteJson('/api/trips/' . $trip->id);
    $response->assertStatus(403)
            ->assertJson(['error' => 'Accès refusé. Vous devez être administrateur.']);
}

}
