<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class TaskController extends Controller
{
    public function getTasks() {
        $user =  Auth::user();
        Log::debug('Getting task list for User: {id}', ['id' => $user->id]);

        $taskList = TaskList::firstOrCreate(
            ['tenant_id' => 1, 'owner_id'=> $user->id]
        );
        $taskCount = Task::Where('task_list_id', $taskList->id)
            ->count();
        $tasks = Task::Where('task_list_id', $taskList->id)
            ->get();

        return Inertia::render('Task/List', [
            'tasks' => $tasks,
            'count' => $taskCount,
        ]);
    }

    public function newTask(Request $request) {
        $user =  Auth::user();
        $taskList = TaskList::where('tenant_id', 1)
            ->where('owner_id', $user->id)
            ->first();
        $taskCount = Task::Where('task_list_id', $taskList->id)
            ->count();

        $date = new Carbon('first day of next month');

        $task = Task::create([
            'task_list_id' => $taskList->id,
            'order_id' => $taskCount,
            'name' => $request->name,
            'due'=> $date,
            'completed' => $request->completed,
        ]);

        return redirect()->route("tasks")->with('success', 'Added');
    }

    public function listReorder(Request $request) {
        $user =  Auth::user();
        $taskList = TaskList::where('tenant_id', 1)
            ->where('owner_id', $user->id)
            ->first();

        foreach($request->tasks as $taskEntry) {
            $task = Task::find($taskEntry->id);
            $task->order_id = $taskEntry->id;
            $task->save();
        }

        return redirect()->route("tasks")->with('success', 'Added');
    }

    public function updateTask(Request $request) {
        $user =  Auth::user();
        $taskList = TaskList::where('tenant_id', 1)
            ->where('owner_id', $user->id)
            ->first();

        $task = Task::find($request->id);
        $task->name = $request->name;
        $task->due = $request->due;
        $task->completed = $request->completed;
        $task->save();

        return redirect()->route("tasks")->with('success', 'Updated');
    }

    public function deleteTask(Request $request) {
        $user =  Auth::user();
        $taskList = TaskList::where('tenant_id', 1)
            ->where('owner_id', $user->id)
            ->first();

        $task = Task::find($request->id);
        $task->destroy();

        return redirect()->route("tasks")->with('success', 'Deleted');
    }
}
