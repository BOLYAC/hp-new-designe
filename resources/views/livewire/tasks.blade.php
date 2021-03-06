@if($mode === 'show')
    <div class="card">
        <div class="card-header b-t-primary b-b-primary p-2 d-flex justify-content-between">
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
                            <ul id="todo-list">
                                @if($mode = 'show')
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
@if($mode === 'create')
    <div class="card">
        <div class="card-header b-t-primary b-b-primary p-2 d-flex justify-content-between">
            <h6 class="mb-0 f-w-600">{{ __('Add task') }}</h6>
            <button wire:click="updateMode('show')" class="btn btn-outline-primary btn-sm"><i
                    class="icon-arrow-left"></i> {{ __('Return to tasks list') }}
            </button>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="createTask">
                @can('department-task')
                    <div class="form-group mb-4 m-checkbox-inline mb-0 custom-radio-ml">
                        <div class="radio radio-primary">
                            <input wire:model="task_entry" id="task_entry" type="radio"
                                   name="task_entry"
                                   value="inbound">
                            <label class="mb-0" for="task_entry">{{ __('Inbound') }}</label>
                        </div>
                        <div class="radio radio-primary">
                            <input wire:model="task_entry" type="radio" name="task_entry"
                                   id="radioinline2" value="outbound">
                            <label class="mb-0" for="radioinline2">{{ __('Outbound') }}</label>
                        </div>
                    </div>
                @endcan
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input wire:model="title" id="title" name="title" class="form-control form-control-sm" type="text"/>
                    @error('title')
                    <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @can('department-task')
                    <div class="form-group">
                        <label for="body">{{ __('Text') }}</label>
                        <textarea wire:model="body" id="body" name="body" class="form-control form-control-sm"
                                  type="text"></textarea>
                        @error('body')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title">{{ __('How') }}</label>
                        <select wire:model="contact_type" id="contact_type" name="contact_type"
                                class="form-control form-control-sm" type="text">
                            <option value=""> -- --</option>
                            <option value="1">{{ __('Phone number') }}</option>
                            <option value="2">{{ __('Mail') }}</option>
                            <option value="3">{{ __('Whatsapp') }}</option>
                            <option value="4">{{ __('Social media') }}</option>
                            <option value="5">{{ __('Hatr...Manuek') }}</option>
                        </select>
                        @error('contact_type')
                        <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endcan
                <div class="form-group">
                    <label for="title">{{ __('Date') }}</label>
                    <input wire:model="date" id="dropper-format" class="form-control form-control-sm" name="date"
                           type="datetime-local"
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
