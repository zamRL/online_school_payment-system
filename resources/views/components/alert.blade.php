<div class="alert alert-{{ $type }} alert-{{ (isset($dismiss) && $dismiss) ? 'dismissible fade show' : '' }}" role="alert">
    {!! $text !!}

    @if(isset($dismiss) && $dismiss)
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    @endif
</div>
