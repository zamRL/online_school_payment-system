@if(session($flash))
    @if($flash == 'success')
        @php($alert_type = 'success')
    @elseif($flash == 'error')
        @php($alert_type = 'danger')
    @elseif($flash == 'warning')
        @php($alert_type = 'warning')
    @else
        @php($alert_type = 'success')
    @endif

    <div class="alert alert-{{ $alert_type }} alert-dismissible fade show" role="alert">
        {{ session($flash) }}

        <a href="#" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </a>
    </div>
@endif
