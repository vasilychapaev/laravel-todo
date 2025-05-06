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

    public function test_user_can_filter_todos_by_status(): void
    {
        // Create todos with different statuses
        $pendingTodo = Todo::factory()->pending()->create(['user_id' => $this->user->id]);
        $inProgressTodo = Todo::factory()->inProgress()->create(['user_id' => $this->user->id]);
        $completedTodo = Todo::factory()->completed()->create(['user_id' => $this->user->id]);

        // Test pending filter
        $response = $this->actingAs($this->user)
            ->get(route('todos.index', ['status' => Todo::STATUS_PENDING]));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertSee($pendingTodo->title)
            ->assertDontSee($inProgressTodo->title)
            ->assertDontSee($completedTodo->title);

        // Test in_progress filter
        $response = $this->actingAs($this->user)
            ->get(route('todos.index', ['status' => Todo::STATUS_IN_PROGRESS]));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertDontSee($pendingTodo->title)
            ->assertSee($inProgressTodo->title)
            ->assertDontSee($completedTodo->title);

        // Test completed filter
        $response = $this->actingAs($this->user)
            ->get(route('todos.index', ['status' => Todo::STATUS_COMPLETED]));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertDontSee($pendingTodo->title)
            ->assertDontSee($inProgressTodo->title)
            ->assertSee($completedTodo->title);
    }

    public function test_user_can_filter_todos_by_date(): void
    {
        // Create todos with different due dates
        $todayTodo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now(),
            'status' => Todo::STATUS_PENDING
        ]);

        $overdueTodo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now()->subDay(),
            'status' => Todo::STATUS_PENDING
        ]);

        $upcomingTodo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now()->addDay(),
            'status' => Todo::STATUS_PENDING
        ]);

        // Test today filter
        $response = $this->actingAs($this->user)
            ->get(route('todos.index', ['date_filter' => 'today']));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertSee($todayTodo->title)
            ->assertDontSee($overdueTodo->title)
            ->assertDontSee($upcomingTodo->title);

        // Test overdue filter
        $response = $this->actingAs($this->user)
            ->get(route('todos.index', ['date_filter' => 'overdue']));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertDontSee($todayTodo->title)
            ->assertSee($overdueTodo->title)
            ->assertDontSee($upcomingTodo->title);

        // Test upcoming filter
        $response = $this->actingAs($this->user)
            ->get(route('todos.index', ['date_filter' => 'upcoming']));

        $response->assertStatus(200)
            ->assertViewIs('todos.index')
            ->assertViewHas('todos')
            ->assertDontSee($todayTodo->title)
            ->assertDontSee($overdueTodo->title)
            ->assertSee($upcomingTodo->title);
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

    public function test_user_cannot_create_todo_with_invalid_data(): void
    {
        $invalidData = [
            'title' => '', // Empty title
            'status' => 'invalid_status' // Invalid status
        ];

        $response = $this->actingAs($this->user)
            ->post(route('todos.store'), $invalidData);

        $response->assertSessionHasErrors(['title', 'status']);
        $this->assertDatabaseMissing('todos', $invalidData);
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

    public function test_user_cannot_update_nonexistent_todo(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('todos.update', 999), $this->todoData);

        $response->assertStatus(404);
    }

    public function test_user_cannot_update_todo_with_invalid_data(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $invalidData = [
            'title' => '', // Empty title
            'status' => 'invalid_status' // Invalid status
        ];

        $response = $this->actingAs($this->user)
            ->put(route('todos.update', $todo), $invalidData);

        $response->assertSessionHasErrors(['title', 'status']);
        $this->assertDatabaseMissing('todos', $invalidData);
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

    public function test_user_cannot_delete_nonexistent_todo(): void
    {
        $response = $this->actingAs($this->user)
            ->delete(route('todos.destroy', 999));

        $response->assertStatus(404);
    }

    public function test_user_cannot_access_other_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get(route('todos.edit', $todo));

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_other_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->put(route('todos.update', $todo), $this->todoData);

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_other_users_todo(): void
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('todos.destroy', $todo));

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

    public function test_todo_scopes(): void
    {

        // Create todos with different statuses
        $pendingTodo = Todo::factory()->pending()->create(['user_id' => $this->user->id]);
        $inProgressTodo = Todo::factory()->inProgress()->create(['user_id' => $this->user->id]);
        $completedTodo = Todo::factory()->completed()->create(['user_id' => $this->user->id]);
        
        // Test status scopes
        $this->assertEquals(1, Todo::pending()->count());
        $this->assertEquals(1, Todo::inProgress()->count());
        $this->assertEquals(1, Todo::completed()->count());

        // Create todos with different due dates
        $todayTodo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now(),
            'status' => Todo::STATUS_PENDING
        ]);

        $overdueTodo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now()->subDay(),
            'status' => Todo::STATUS_PENDING
        ]);

        $upcomingTodo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'due_date' => now()->addDay(),
            'status' => Todo::STATUS_PENDING
        ]);

        // Test date scopes
        $this->assertEquals(1, Todo::dueToday()->count());
        $this->assertEquals(1, Todo::overdue()->count());
        $this->assertEquals(3, Todo::upcoming()->count());
    }
}
