@if($errors->any())
    <div class="form-group">
        <ul>
            @foreach($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
