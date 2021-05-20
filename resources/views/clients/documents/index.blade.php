<!-- info card start -->
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0 f-w-600">{{ __('Documents') }}</h5>
        @can('task-create')
            <button data-toggle="modal"
                    data-target="#exampleModalCenter"
                    class="btn btn-primary">{{ __('Add Documents') }} <i class="icon-plus"></i>
            </button>
        @endcan
    </div>
    <div class="card-block">
        @if($clientDocuments->count() > 0)
            <div class="table-responsive">
                <div class="dt-responsive table-responsive">
                    <table id="res-config" class="table table-bordered nowrap">
                        <thead>
                        <tr>
                            <th width="20%">{{ __('File') }}</th>
                            <th width="60%">{{ __('Title') }}</th>
                            <th width="10%">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clientDocuments as $file)
                            <tr data-id="{{ $file->id }}">
                                <td class="img-pro text-center">
                                    <a href="{{ asset('storage/' . $file->full) }}" data-lightbox="{{ $file->id }}"
                                       data-title="{{ $file->title }}">
                                        <img src="{{ asset('storage/' . $file->full) }}" alt=""
                                             class="img-fluid img-thumbnail img-fluid d-inline-block img-70">
                                    </a>
                                </td>
                                <td class="pro-name">
                                    <h6>{{ $file->title }}</h6>
                                    <span
                                        class="text-muted f-12">{{ $file->excerpt }}</span>
                                </td>
                                <td class="action-icon text-center">
                                    <!--<a href="" class="m-r-15 text-muted f-18"><i class="icofont icofont-ui-edit"></i></a>-->
                                    @can('task-delete')
                                        <a href="#" class="text-muted f-18 delete"><i
                                                class="icofont icofont-delete-alt"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- info card end -->
<!-- Create modal start -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('New document') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span
                        aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ $client->getCreateDocumentEndpoint() }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input class="form-control" name="title">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Excerpt') }}</label>
                        <input type="text" name="excerpt" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('File') }}</label>
                        <input type="file" name="full" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }} <i class="icon-save"></i></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create modal end -->
