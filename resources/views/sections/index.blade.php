@extends('layouts.app')

@section('subtitle', ' | Sections')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="lead"><b>Sections</b></span>
                        <span class="float-right">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add-section-modal">
                                Add New
                            </button>
                        </span>
                    </div>

                    <div class="card-body">
                        @component('components.flash', ['flash' => 'success'])@endcomponent
                        @component('components.flash', ['flash' => 'error'])@endcomponent
                        @component('components.flash', ['flash' => 'warning'])@endcomponent

                        @if($sections->isEmpty())
                            @component('components.alert', ['type' => 'warning', 'text' => 'No section found!'])@endcomponent
                        @else
                            <table class="table datatable table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th SCOPE="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 0)
                                @foreach($sections as $section)
                                    @php($count++)
                                    <tr>
                                        <th scope="row">{{ $count }}</th>
                                        <td>{{ $section->class->name }}</td>
                                        <td>{{ $section->name }}</td>
                                        <td>{{ Str::limit($section->description, 20, ' (...)') }}</td>
                                        <td>{{ date('F j, Y', strtotime($section->created_at)) }}</td>
                                        <td>{{ date('F j, Y', strtotime($section->updated_at)) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-section-modal-{{ $section->id }}">
                                                Edit
                                            </button>
                                            &nbsp;
                                            <form style="display: inline;" action="{{ route('admin.sections.destroy', $section->id) }}" method="post"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{--    edit class modal--}}
                                    @component('components.modal-start', [
                                        'id' => 'edit-section-modal-' . $section->id,
                                        'title' => 'Edit Section',
                                        'form' => [
                                            'action' => route('admin.sections.update', $section->id),
                                        ],
                                        'method' => 'PATCH',
                                    ]);
                                    @endcomponent
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="class_id-{{ $section->id }}">Class:</label>
                                                <select id="class_id-{{ $section->id }}" name="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                                                    <option value="">Select Class</option>
                                                    @if(!($classes)->isEmpty())
                                                        @foreach($classes as $class)
                                                            <option value="{{ $class->id }}" {{ (old('class_id') && old('class_id') == $class->id) ? 'selected' :
                                                                        (($section->class_id == $class->id) ? 'selected' : '') }}>{{ $class->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @component('components.error', ['field' => 'class_id'])@endcomponent
                                            </div>
                                            <div class="form-group">
                                                <label for="name-{{ $section->id }}">Name:</label>
                                                <input id="name-{{ $section->id }}" type="text" placeholder="Class Name" class="form-control @error('name') is-invalid @enderror"
                                                       name="name" value="{{ old('name') ? old('name') : $section->name }}" required autocomplete="name">
                                                @component('components.error', ['field' => 'name'])@endcomponent
                                            </div>
                                            <div class="form-group">
                                                <label for="description-{{ $section->id }}">Description:</label>
                                                <textarea id="description-{{ $section->id }}" name="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="8"
                                                          placeholder="Class Description">{{ old('description') ? old('description') : $section->description }}</textarea>
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
        'id' => 'add-section-modal',
        'title' => 'Add Section',
        'form' => [
            'action' => route('admin.sections.store'),
        ],
    ]);
    @endcomponent
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="class_id">Class:</label>
                <select id="class_id" name="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                    <option value="">Select Class</option>
                    @if(!($classes)->isEmpty())
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    @endif
                </select>
                @component('components.error', ['field' => 'class_id'])@endcomponent
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input id="name" type="text" placeholder="Section Name" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name">
                @component('components.error', ['field' => 'name'])@endcomponent
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="8"
                          placeholder="Section Description">{{ old('description') }}</textarea>
                @component('components.error', ['field' => 'description'])@endcomponent
            </div>
        </div>
    </div>
    @component('components.modal-end', [
        'form' => true
    ]);
    @endcomponent
@endsection
