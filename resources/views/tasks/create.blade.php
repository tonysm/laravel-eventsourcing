@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        New Task
                    </div>

                    <form
                        action="{{ route('tasks.store') }}"
                        method="POST"
                    >
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Woops!</strong> Looks like you got some errors.
                                </div>
                            @endif


                            <div class="form-group">
                                <label for="">Task Name</label>
                                <input
                                    type="text"
                                    class="form-control {{$errors->first('taskName', 'is-invalid')}}"
                                    name="taskName"
                                    placeholder="Example: Get Milk"
                                    value="{{ old('taskName') }}"
                                />

                                @if($errors->has('taskName'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('taskName') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-link" href="{{ route('home') }}">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
