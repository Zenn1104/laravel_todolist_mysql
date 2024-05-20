<?php

namespace Tests\Feature;

use Database\Seeders\TodoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TodolistControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from todos");
    }
    public function testTodolist()
    {
        $this->seed([TodoSeeder::class]);

        $this->withSession([
            "user" => "zenn",
        ])->get("/todolist")
            ->assertSeeText("1")
            ->assertSeeText("belajar")
            ->assertSeeText("2")
            ->assertSeeText("coding");
    }

    public function testRemoveTodolist()
    {
        $this->withSession([
            "user" => "zenn",
            "todolist" => [
                [
                    "id" => "1",
                    "todo" => "belajar"
                ],
                [
                    "id" => "2",
                    "todo" => "coding"
                ]
            ]
        ])->post("/todolist/1/delete")
            ->assertRedirect("/todolist");
    }


}