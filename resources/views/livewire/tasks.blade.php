@if($mode === 'create')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 f-w-600">{{ __('Add task') }}</h6>
            <button wire:click="updateMode('show')" class="btn btn-outline-primary btn-sm"><i
                    class="icon-arrow-left"></i> {{ __('Return to tasks list') }}
            </button>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="createTask">
                <div class="form-group">
                    <label class="col-form-label pt-0" for="title">{{ __('Title') }}</label>
                    <input wire:model="title" id="title" name="title" class="form-control" type="text"/>
                    @error('title')
                    <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="sub-title">{{ __('Date') }}</label>
                    <input wire:model="date" id="dropper-format" class="form-control" name="date" type="datetime-local"
                           required/>
                    @error('date')
                    <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <input type="hidden" name="client_id" value="{{ $client }}">
                <button type="submit" class="btn btn-outline-primary">
                    Submit
                </button>
            </form>
        </div>
    </div>
@endif
@if($mode === 'show')
    <div class="card">
        <div class="card-header b-t-primary d-flex justify-content-between">
            <h6 class="mb-0 f-w-600">{{ __('Tasks') }}</h6>
            @can('task-create')
                <button wire:click="updateMode('create')" class="btn btn-outline-primary btn-sm">
                    {{ __('Add new task') }} <i class="icon-plus"></i>
                </button>
            @endcan
        </div>
        <div class="card-body">
            <div class="todo">
                <div class="todo-list-wrapper">
                    <div class="todo-list-container">
                        <div class="todo-list-body">
                            <ul id="todo-list" wire:poll.keep-alive>
                                @if($mode = 'show3')
                                    @foreach($tasks as $task)
                                        <li class="{{ $task->archive === true ? 'completed' : '' }} task">
                                            <div class="task-container">
                                                <h4 class="task-label">{{ $task->title }} </h4>
                                                <div class="float-left mt-2">
                                                <span
                                                    class="badge badge-light-success badge-lg">{{ $task->user->name}}
                                                </span>
                                                    <a href="#"
                                                       id="assign_task"
                                                       data-id="{{ $task->id }}"><i
                                                            class="icofont icofont-plus f-w-600"></i>
                                                    </a>
                                                    <span
                                                        class="text-muted ml-2 f-w-600">{{ Carbon\Carbon::parse($task->date)->format('d-m-Y H:i') }}</span>
                                                </div>
                                                <span class="task-action-btn">
                                                <span wire:click="deleteTask({{ $task->id }})"
                                                      class="action-box large" title="Delete Task">
                                                    <i class="icon"><i class="icon-trash"></i></i>
                                                </span>
                                                <span wire:click="archive({{ $task->id }})"
                                                      class="action-box large"
                                                      title="{{ $task->archive === true ? 'Mark Complete' : 'Mark Incomplete' }}">
                                                    <i class="icon"><i class="icon-check"></i></i>
                                                </span>
                                            </span>

                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
