<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from users");
    }

   public function testLoginPage()
   {
       $this->get('/login')
           ->assertSeeText("Login Todolist Zenn");
   }

    public function testLoginPageForMember()
    {
        $this->withSession([
            "user" => "zenn"
        ])->get("/login")
            ->assertRedirect("/");
    }


    public function testLoginSuccess()
   {
        $this->seed([UserSeeder::class]);
       $this->post('/login', [
           "user" => "basoalif@zen.com",
           "password" => "rahasia"
       ])
           ->assertRedirect("/")
            ->assertSessionHas("user", "basoalif@zen.com");
   }

    public function testLoginForUserAlreadyExist()
    {
        $this->withSession([
            "user" => "zenn"
        ])
            ->post('/login', [
            "user" => "zenn",
            "password" => "rahasia"
        ])
            ->assertRedirect("/");
    }


    public function testLoginValidationError()
   {
       $this->post("/login", [])
           ->assertSeeText("User or Password is required!");
   }

   public function testLoginFailed()
   {
       $this->post("/login", [
           "user" => "wrong",
           "password" => "wrong"
       ])
           ->assertSeeText("User or Password is wrong!");
   }

    public function testLogout()
    {
        $this->withSession([
            "user" => "zenn"
        ])->post("/logout")
            ->assertRedirect("/")
            ->assertSessionMissing("user");
    }

    public function testLogoutGuest()
    {
        $this->post("/logout")
            ->assertRedirect("/");
    }

    public function testAddTodoFailed()
    {
        $this->withSession([
            "user" => "zenn"
        ])->post("/todolist", [])
            ->assertSeeText("Todo is required!");
    }

    public function testAddTodoSuccess()
    {
        $this->withSession([
            "user" => "zenn"
        ])->post("/todolist", [
            "todo" => "belajar"
        ])->assertRedirect("/todolist");
    }


}