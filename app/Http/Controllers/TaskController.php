<?php

namespace App\Http\Controllers;

use App\Contracts\TaskService;
use Illuminate\Http\Request;
use App\Exceptions\TaskException;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required',
            'description' => 'bail|required',
        ]);

        if ($validator->fails()) {
            throw new TaskException($validator->errors()->first());
        }

        return $this->taskService->store($request);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required',
            'description' => 'bail|required',
        ]);

        if ($validator->fails()) {
            throw new TaskException($validator->errors()->first());
        }

        return $this->taskService->edit($request, $id);
    }

    public function index(Request $request)
    {
        return $this->taskService->getTasks($request);
    }

    public function delete(Request $request, $id)
    {
        return $this->taskService->deleteTask($id);
    }
}
