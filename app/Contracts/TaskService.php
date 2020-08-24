<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface TaskService
{

    public function store(Request $request);
    public function edit(Request $request, $id);
    public function deleteTask($id);

    public function getTasks(Request $request);
}
