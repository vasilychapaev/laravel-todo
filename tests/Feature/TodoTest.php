<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private array $todoData;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->todoData = [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'due_date' => now()->addDay()->format('Y-m-d\TH:i'),
            'status' => 'pending'
        ];
    }

    public function test_user_can_view_todos_list(): void
    {
        $todos = Todo::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('todos.index'));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertSee($todos[0]->title)
            ->assertSee($todos[1]->title)
            ->assertSee($todos[2]->title);
    }

    public function test_user_can_create_todo(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('todos.store'), $this->todoData);

        $response->assertRedirect(route('todos.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('todos', [
            'title' => $this->todoData['title'],
            'description' => $this->todoData['description'],
            'user_id' => $this->user->id
        ]);
    }

    public function test_user_can_update_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $updatedData = [
            'title' => 'Updated Todo',
            'description' => 'Updated Description',
            'due_date' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'status' => 'in_progress'
        ];

        $response = $this->actingAs($this->user)
            ->put(route('todos.update', $todo), $updatedData);

        $response->assertRedirect(route('todos.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description']
        ]);
    }

    public function test_user_can_delete_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('todos.destroy', $todo));

        $response->assertRedirect(route('todos.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('todos', ['id' => $todo->id]);
    }

    public function test_user_cannot_access_other_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get(route('todos.edit', $todo));

        $response->assertStatus(403);
    }

    public function test_todo_validation_rules(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('todos.store'), [
                'title' => '', // Empty title should fail
                'status' => 'invalid_status' // Invalid status should fail
            ]);

        $response->assertSessionHasErrors(['title', 'status']);
    }
}
