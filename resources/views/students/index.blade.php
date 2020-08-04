@extends('layouts.app')

@section('subtitle', ' | Students')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="lead"><b>Students</b></span>
                        <span class="float-right">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add-student-modal">
                                Add New
                            </button>
                        </span>
                    </div>

                    <div class="card-body">
                        @component('components.flash', ['flash' => 'success'])@endcomponent
                        @component('components.flash', ['flash' => 'error'])@endcomponent
                        @component('components.flash', ['flash' => 'warning'])@endcomponent

                        @if($students->isEmpty())
                            @component('components.alert', ['type' => 'warning', 'text' => 'No student found!'])@endcomponent
                        @else
                            <table class="table datatable table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Student ID</th>
                                    <th scope="col">Class & Section</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th SCOPE="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 0)
                                @foreach($students as $student)
                                    @php($count++)
                                    <tr>
                                        <th scope="row">{{ $count }}</th>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->student_id }}</td>
                                        <td>
                                            @if($student->assigned_student)
                                                {{ $student->assigned_student->section->class->name }} ({{ $student->assigned_student->section->name }})
                                            @endif
                                        </td>
                                        <td>{{ date('F j, Y', strtotime($student->created_at)) }}</td>
                                        <td>{{ date('F j, Y', strtotime($student->updated_at)) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#student-assign-modal-{{ $student->id }}">
                                                Assign
                                            </button>
                                            &nbsp;
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-student-modal-{{ $student->id }}">
                                                Edit
                                            </button>
                                            &nbsp;
                                            <form style="display: inline;" action="{{ route('admin.students.destroy', $student->id) }}" method="post"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

{{--                                    assign student modal--}}
                                    @component('components.modal-start', [
                                        'id' => 'student-assign-modal-' . $student->id,
                                        'title' => 'Assign Student',
                                        'form' => [
                                            'action' => route('admin.students.assign', $student->id),
                                        ],
                                    ]);
                                    @endcomponent
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="section_id">Class & Section:</label>
                                                <select id="section_id" name="section_id" class="form-control @error('section_id') is-invalid @enderror">
                                                    <option value="">Select Class & Section</option>
                                                    @if(!($classes)->isEmpty())
                                                        @foreach($classes as $class)
                                                            @if(!($class->sections)->isEmpty())
                                                                @foreach($class->sections as $section)
                                                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}
                                                                        {{ ($student->assigned_student && $student->assigned_student->section_id == $section->id) ? 'selected' : '' }}>
                                                                        {{ $class->name }} ({{ $section->name }})</option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @component('components.error', ['field' => 'section_id'])@endcomponent
                                            </div>
                                        </div>
                                    </div>
                                    @component('components.modal-end', [
                                        'form' => true
                                    ]);
                                    @endcomponent

{{--                                        edit student modal--}}
                                    @component('components.modal-start', [
                                        'id' => 'edit-student-modal-' . $student->id,
                                        'title' => 'Edit Student',
                                        'form' => [
                                            'action' => route('admin.students.update', $student->id),
                                        ],
                                        'method' => 'PATCH',
                                    ]);
                                    @endcomponent
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name-{{ $student->id }}">Name:</label>
                                                <input id="name-{{ $student->id }}" type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror"
                                                       name="name" value="{{ old('name') ? old('name') : $student->name }}" required autocomplete="name">
                                                @component('components.error', ['field' => 'name'])@endcomponent
                                            </div>
                                            <div class="form-group">
                                                <label for="email-{{ $student->id }}">Email:</label>
                                                <input id="email-{{ $student->id }}" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror"
                                                       name="email" value="{{ old('email') ? old('email') : $student->email }}" required autocomplete="email">
                                                @component('components.error', ['field' => 'email'])@endcomponent
                                            </div>
                                            <div class="form-group">
                                                <label for="student_id-{{ $student->id }}">Student ID:</label>
                                                <input id="student_id-{{ $student->id }}" type="text" placeholder="Student ID" class="form-control @error('student_id') is-invalid @enderror"
                                                       name="student_id" value="{{ old('student_id') ? old('student_id') : $student->student_id }}" required autocomplete="student_id">
                                                @component('components.error', ['field' => 'student_id'])@endcomponent
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

    {{--    add student modal--}}
    @component('components.modal-start', [
        'id' => 'add-student-modal',
        'title' => 'Add Student',
        'form' => [
            'action' => route('admin.students.store'),
        ],
    ]);
    @endcomponent
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Name:</label>
                <input id="name" type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name">
                @component('components.error', ['field' => 'name'])@endcomponent
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email">
                @component('components.error', ['field' => 'email'])@endcomponent
            </div>
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input id="student_id" type="text" placeholder="Student ID" class="form-control @error('student_id') is-invalid @enderror"
                       name="student_id" value="{{ old('student_id') }}" required autocomplete="student_id">
                @component('components.error', ['field' => 'student_id'])@endcomponent
            </div>
            <div class="form-group">
                <label for="section_id">Class & Section:</label>
                <select id="section_id" name="section_id" class="form-control @error('section_id') is-invalid @enderror">
                    <option value="">Select Class & Section</option>
                    @if(!($classes)->isEmpty())
                        @foreach($classes as $class)
                            @if(!($class->sections)->isEmpty())
                                @foreach($class->sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $class->name }} ({{ $section->name }})</option>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </select>
                @component('components.error', ['field' => 'section_id'])@endcomponent
            </div>
        </div>
    </div>
    @component('components.modal-end', [
        'form' => true
    ]);
    @endcomponent
@endsection
