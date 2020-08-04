@extends('layouts.app')

@section('subtitle', ' | Classes')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="lead"><b>Classes</b></span>
                        <span class="float-right">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add-class-modal">
                                Add New
                            </button>
                        </span>
                    </div>

                    <div class="card-body">
                        @component('components.flash', ['flash' => 'success'])@endcomponent
                        @component('components.flash', ['flash' => 'error'])@endcomponent
                        @component('components.flash', ['flash' => 'warning'])@endcomponent

                        @if($classes->isEmpty())
                            @component('components.alert', ['type' => 'warning', 'text' => 'No class found!'])@endcomponent
                        @else
                        <table class="table datatable table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th SCOPE="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($classes as $class)
                                    @php($count++)
                                    <tr>
                                        <th scope="row">{{ $count }}</th>
                                        <td>{{ $class->name }}</td>
                                        <td>{{ Str::limit($class->description, 20, ' (...)') }}</td>
                                        <td>{{ date('F j, Y', strtotime($class->created_at)) }}</td>
                                        <td>{{ date('F j, Y', strtotime($class->updated_at)) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-class-modal-{{ $class->id }}">
                                                Edit
                                            </button>
                                            &nbsp;
                                            <form style="display: inline;" action="{{ route('admin.classes.destroy', $class->id) }}" method="post"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{--    edit class modal--}}
                                    @component('components.modal-start', [
                                        'id' => 'edit-class-modal-' . $class->id,
                                        'title' => 'Edit Class',
                                        'form' => [
                                            'action' => route('admin.classes.update', $class->id),
                                        ],
                                        'method' => 'PATCH',
                                    ]);
                                    @endcomponent
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name-{{ $class->id }}">Name:</label>
                                                <input id="name-{{ $class->id }}" type="text" placeholder="Class Name" class="form-control @error('name') is-invalid @enderror"
                                                       name="name" value="{{ old('name') ? old('name') : $class->name }}" required autocomplete="name">
                                                @component('components.error', ['field' => 'name'])@endcomponent
                                            </div>
                                            <div class="form-group">
                                                <label for="description-{{ $class->id }}">Description:</label>
                                                <textarea id="description-{{ $class->id }}" name="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="8"
                                                          placeholder="Class Description">{{ old('description') ? old('description') : $class->description }}</textarea>
                                                @component('components.error', ['field' => 'description'])@endcomponent
                                            </div>
                                        </div>
                                    </div>
                                    @component('components.modal-end', [
                                        'form' => true
                                    ]);
                                    @endcomponent
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    add class modal--}}
    @component('components.modal-start', [
        'id' => 'add-class-modal',
        'title' => 'Add Class',
        'form' => [
            'action' => route('admin.classes.store'),
        ],
    ]);
    @endcomponent
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Name:</label>
                <input id="name" type="text" placeholder="Class Name" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name">
                @component('components.error', ['field' => 'name'])@endcomponent
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="8"
                          placeholder="Class Description">{{ old('description') }}</textarea>
                @component('components.error', ['field' => 'description'])@endcomponent
            </div>
        </div>
    </div>
    @component('components.modal-end', [
        'form' => true
    ]);
    @endcomponent
@endsection
