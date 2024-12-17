@if(session('error'))
    <div class="alert alert-danger" id="alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success" id="alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        {{ session('success') }}
    </div>
@endif
