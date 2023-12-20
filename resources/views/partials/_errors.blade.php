
<div class="row">


    <div class="col-lg-6 col-lg-offset-3">

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

    </div>
</div>
