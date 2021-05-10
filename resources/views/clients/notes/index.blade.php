<div class="card-block note-card">
    <div class="note-box-wrapper row">
        <div class="note-box-aside col-lg-12 col-xl-3">
            <div class="row">
                <div class="col-sm-3">
                    <h5><i class="icofont icofont-file-text m-r-5"></i>Notes
                    </h5>
                </div>
            </div>
            <div class="notes-list">
                <ul id="Note-list" class="Note-list list-group">
                    @foreach($notes as $note)
                        <li class="list-group-item">
                            @can('note-delete')
                                <form
                                    action="{{ route('notes.destroy', $note) }}"
                                    method="post" role="form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="Note-delete">
                                        x
                                    </button>
                                </form>
                            @endcan
                            <div class="Note">
                                <a onclick="showNote({!! $note->id !!});"
                                   class="btn btn-sm btn-outline-success"><i
                                        class="ti-eye"></i> show</a>
                                <div class="Note__name"></div>
                                <div class="Note__desc">
                                    {!! $note->body ?? '' !!}
                                </div>
                                <span
                                    class="Note__date">
                                    {{ Carbon\Carbon::parse($note->created_at)->format('Y-m-d H:i') }}
                                    By: {{ $note->user->name }}

                                                                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="note-box-content col-lg-12 col-xl-9">
            @can('note-create')
                <form action="{{ route('notes.store') }}" method="post"
                      role="form">
                    @csrf
                    <input type="hidden" name="client_id"
                           value="{{ $client }}">
                    <div class="Note-header">
                        <div class="Note-created f-right">
                                                                    <span
                                                                        class="Note-created__on"></span>
                            <span class="Note-created__date"
                                  id="Note-created__date"></span>
                        </div>
                        <button type="submit"
                                class="btn btn-md btn-primary hidden-xs Note-add">
                            Add
                            New +
                        </button>
                    </div>
                    <div class="note-body">
                                                                <textarea name="body">
                                                                </textarea>
                    </div>
                </form>
            @endcan
        </div>
    </div>
</div>
