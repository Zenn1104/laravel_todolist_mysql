<?php

namespace App\Http\Controllers;

use App\Services\TodolistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodolistController extends Controller
{
    private TodolistService $todolistService;

    /**
     * @param TodolistService $todolistService
     */
    public function __construct(TodolistService $todolistService)
    {
        $this->todolistService = $todolistService;
    }


    public function todoList(Request $request): Response
    {
        $todolist = $this->todolistService->getTodolist();
        return response()->view("todolist.todolist", [
            "title" => "Todolist",
            "todolist" => $todolist
        ]);
    }

    public function addTodo(Request $request): Response|RedirectResponse
    {
        $todo = $request->input("todo");

        if(empty($todo)){
            $todolist = $this->todolistService->getTodolist();
            return response()->view("todolist.todolist", [
                "title" => "Todolist",
                "todolist" => $todolist,
                "error" => "Todo is required!"
            ]);
        }

        $this->todolistService->saveTodo(uniqid(), $todo);
        return redirect()->action([TodolistController::class, 'todoList']);
    }

    public function removeTodo(Request $request, string $id): RedirectResponse
    {
        $this->todolistService->removeTodo($id);
        return redirect()->action([TodolistController::class, 'todoList']);
    }
}
