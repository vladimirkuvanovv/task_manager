<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Label::factory()->count(2)->make();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testIndex()
    {
        $response = $this->get(route('labels.index'));

        $response->assertOk();
    }

    public function testCreate()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('labels.create'));

        $response->assertOk();
    }

    public function testEdit()
    {
        $label = Label::factory()->create();
        $response = $this->get(route('labels.edit', [$label]));

        $response->assertOk();
    }

    public function testStore()
    {
        $label = Label::factory()->make()->toArray();
        $data = Arr::only($label, [
            'name',
            'description'
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('labels.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdate()
    {
        $label = Label::factory()->create();
        $data = Label::factory()->make()->toArray();

        $response = $this->patch(route('labels.update', $label), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('labels', $data);
    }

    public function testDelete()
    {
        $label = Label::factory()->create();
        $response = $this->delete(route('labels.destroy', [$label]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }
}
