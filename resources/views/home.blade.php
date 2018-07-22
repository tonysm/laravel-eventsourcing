@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        Tasks TO-DO
                    </span>

                    <a href="{{ route('tasks.create') }}" class="btn btn-link">New Task</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th width="70%">Task name</th>
                                <th>Create at</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-info" href="{{ route('tasks.destroy', $task) }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('tasks-destroy-form-{{ $task->id }}').submit();">
                                            {{ __('Done') }}
                                        </a>

                                        <form id="tasks-destroy-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No tasks yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span>
                        Tasks Done
                    </span>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th width="70%">Task name</th>
                            <th>Completed at</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($completedTasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ optional($task->completed_at)->diffForHumans() ?? 'No' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-info" href="{{ route('tasks.update', $task) }}"
                                       onclick="event.preventDefault();
                                           document.getElementById('tasks-destroy-form-{{ $task->id }}').submit();">
                                        {{ __('Undo') }}
                                    </a>

                                    <form id="tasks-destroy-form-{{ $task->id }}" action="{{ route('tasks.update', $task) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No tasks yet.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
