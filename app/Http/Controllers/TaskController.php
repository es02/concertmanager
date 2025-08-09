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
use App\Http\Controllers\Utils;

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
            ->orderBy('order_id', 'asc')
            ->get();

        foreach($tasks as &$task) {
            $overdue = false;
            if (Carbon::parse($task->due)->isPast() && $task->completed !== "completed" && $task->completed !== "deleted") {
                $overdue = true;
            }
            $task->overdue = $overdue;
        }

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
        $tasks = $request->tasks;

        foreach($tasks as $taskEntry) {
            Log::debug('Attempting to find task: {entry}', ['entry' => $taskEntry]);
            $task = Task::find($taskEntry['id']);
            $task->order_id = $taskEntry['task_list_id'];
            $task->save();
        }

        return redirect()->route("tasks")->with('success', 'Reordered');
    }

    public function updateTask(Request $request) {
        $user =  Auth::user();
        $taskList = TaskList::where('tenant_id', 1)
            ->where('owner_id', $user->id)
            ->first();
        $name = '.';
        if($request->name !== '') {
            $name = $request->name;
        }

        $task = Task::find($request->id);
        Log::debug('Attempting to find task: {id}', ['id' => $request->id]);
        Log::debug('Updating task data; id: {id}, name: {name}, due: {due}, status: {status}', ['id' => $request->id, 'name' => $request->name, 'due' => $request->due, 'status' => $request->completed]);
        $task->name = $name;
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

    public function exportCSV() {
        $filename = 'tasks.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'X-Accel-Buffering' => 'no'
        ];
        Log::info('Exporting task CSV');

        $columns = [
            'Name *',
            'Due by *',
            'Status *',
            'Order *',
        ];

        $callback = function() use ($filename, $columns) {
            $user =  Auth::user();
            $handle = fopen('php://output', 'w');
            Log::debug('Exporting task CSV: {name}', ['name' => $filename]);

            $taskList = TaskList::where('tenant_id', 1)
            ->where('owner_id', $user->id)
            ->first();

            $count = Task::where('task_list_id', $taskList->id)->count();
            Log::debug('Total taks to export: {count}', ['count' => $count]);

            fputcsv($handle, $columns);

            // Fetch and process data in chunks
            Task::where('task_list_id', $taskList->id)->chunk(25, function ($tasks) use ($handle) {
                Log::debug('Exporting chunk: {chunk}', ['chunk' => $tasks]);
                foreach ($tasks as $task) {
                    Log::debug('Exporting task: {name}', ['name' => $task->name]);

                    $data = [
                        isset($task->name)?             $task->name             : '',
                        isset($task->due)?              $task->due              : '',
                        isset($task->completed)?        $task->completed        : '',
                        isset($task->order_id)?         $task->order_id         : '',
                    ];

                    Log::debug('Exporting data: {data}', ['data' => $data]);
                    Log::debug('Exporting handle: {handle}', ['handle' => $handle]);

                    // Write data to a CSV file.
                    fputcsv($handle, $data);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers)->send();
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'import_csv' => 'required|mimes:csv',
        ]);
        //read csv file and skip data
        Utils::importCSV($request, 'task');

        return redirect()->route('tasks')->with('success', 'Data has been added successfully.');
    }
}
