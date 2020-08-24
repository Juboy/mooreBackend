<?php

namespace App\ServiceImpl;

use Illuminate\Http\Request;

use App\Task;
use App\Utils\Response;
use App\Utils\Utils;
use App\Contracts\TaskService;
use Illuminate\Support\Facades\Auth;

class TaskServiceImpl implements TaskService
{

    // public function __construct(){
    //     Utils::hasRole("ROLE_ADMIN");
    // }

    public function store(Request $request)
    {

        $response = new Response();
        $user = Auth::user();
        $task = new Task([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
        ]);

        if ($user->tasks()->save($task)) {
            $response->setMessage("Task created successfully");
            $response->setStatus(Response::$success);
            return $response->toArray();
        }
        $response->setMessage("Task creation Failed");
        $response->setStatus(Response::$failed);
        return $response->toArray();
    }

    public function edit(Request $request, $id)
    {

        $response = new Response();
        $user = Auth::user();
        $task = Task::find($id);
        if ($user->id != $task->user_id) {
            $response->setMessage("Unauthorized User");
            $response->setStatus(Response::$failed);
            return $response->toArray();
        }


        $task->title = $request->input('title');
        $task->description = $request->input('description');


        if ($task->save()) {
            $response->setMessage("Task Edited");
            $response->setStatus(Response::$success);
            return $response->toArray();
        }
        $response->setMessage("Failed to Edit");
        $response->setStatus(Response::$failed);
        return $response->toArray();
    }

    public function getTasks(Request $request)
    {
        $limit = Utils::$pageLimit;
        $response = new Response();
        $user = Auth::user();
        $tasks = Task::where('user_id', $user->id)->orderBy('id', 'DESC');
        foreach ($request->all() as $key => $value) {
            if ($key == "page") {
                continue;
            }
            if ($key == "search") {
                $tasks = $tasks->where(function ($query)  use (&$value) {
                    $query->where('title', 'like', '%' . $value . '%')
                        ->orWhere('description', 'like', '%' . $value . '%');
                });
                continue;
            }
            $tasks = $tasks->where($key, $value);
        }
        if ($request->has('page')) {
            $count = $tasks->get()->count();
            $value = $request->input('page');
            $tasks = $tasks->limit($limit)->offset(($value - 1) * $limit);
        }
        $tasks = $tasks->get();

        if ($tasks->count() == 0) {
            $response->setMessage("Empty");
            $response->setStatus(Response::$failed);
            return $response->toArray();
        }

        $response->setMessage("Tasks Retrieved");
        $response->setStatus(Response::$success);
        $responseArray = $response->toArray();
        $responseArray['data'] = $tasks;
        $responseArray['count'] = isset($count) ? $count :  $tasks->count();
        if (isset($count)) $responseArray['last_page'] = ($count % $limit) > 0 ? (int) ($count / $limit) + 1 : (int) ($count / $limit);
        return $responseArray;
    }

    public function deleteTask($id)
    {
        $response = new Response();
        $user = Auth::user();
        $task = Task::find($id);

        if ($user->id == $task->user_id && $task->delete($task)) {
            $response->setMessage("Task deleted");
            $response->setStatus(Response::$success);
            return $response->toArray();
        }
        $response->setMessage("Could't delete");
        $response->setStatus(Response::$failed);
        return $response->toArray();
    }
}
