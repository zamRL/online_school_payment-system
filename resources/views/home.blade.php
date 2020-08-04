@extends('layouts.app')

@section('subtitle', ' | Student Payments')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="lead"><b>Students</b></span>
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
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#view-payments-modal-{{ $student->id }}">
                                                View Payments
                                            </button>

                                            {{--                                    assign student modal--}}
                                            @component('components.modal-start', [
                                                'id' => 'view-payments-modal-' . $student->id,
                                                'title' => 'Payment History',
                                            ]);
                                            @endcomponent
                                            <p class="text-muted"><i>- Payment history of <b>{{ $student->name }}</b></i></p>

                                            <table class="table datatable table-striped child-table table-bordered">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Month</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" class="text-right">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($count_child = 0)
                                                @foreach($payment_months as $month_val => $month_name)
                                                    @php($count_child++)
                                                    @php($payments = [])
                                                    @if(!($student->payments)->isEmpty())
                                                        @foreach($student->payments as $payment)
                                                            @php($payments[$payment->month]['id'] = $payment->id)
                                                            @php($payments[$payment->month]['status'] = $payment->status)
                                                        @endforeach
                                                    @endif

                                                    <tr>
                                                        <th scope="row">{{ $count_child }}</th>
                                                        <td>{{ ucwords($month_name) }}</td>
                                                        <td class="text-center">
                                                            @if(array_key_exists($month_val, $payments))
                                                                @if($payments[$month_val]['status'] == 'paid')
                                                                    <span class="badge badge-success">Paid</span>
                                                                @else
                                                                    <span class="badge badge-danger">Unpaid</span>
                                                                @endif
                                                            @else
                                                                <span class="badge badge-danger">Unpaid</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            @if(array_key_exists($month_val, $payments))
                                                                @if($payments[$month_val]['status'] == 'paid')
                                                                    <form style="display: inline;" action="{{ route('admin.students.invoice', $payments[$month_val]['id']) }}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="month" value="{{ $month_val }}">
                                                                        <button type="submit" class="btn btn-sm btn-success">Download Invoice</button>
                                                                    </form>
                                                                @else
                                                                    <form style="display: inline;" action="{{ route('admin.students.payment', $student->id) }}" method="post"
                                                                          onsubmit="return confirm('Are you sure?')">
                                                                        @csrf
                                                                        <input type="hidden" name="month" value="{{ $month_val }}">
                                                                        <button type="submit" class="btn btn-sm btn-primary">Pay Fee</button>
                                                                    </form>
                                                                @endif
                                                            @else
                                                                <form style="display: inline;" action="{{ route('admin.students.payment', $student->id) }}" method="post"
                                                                      onsubmit="return confirm('Are you sure?')">
                                                                    @csrf
                                                                    <input type="hidden" name="month" value="{{ $month_val }}">
                                                                    <button type="submit" class="btn btn-sm btn-primary">Pay Fee</button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            @component('components.modal-end');@endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
