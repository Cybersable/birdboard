@if($errors->{ $bag ?? 'default' }->any())
    <div class="mb-3">
        @foreach($errors->{ $bag ?? 'dafault' }->all() as $error)
            <div class="text-danger">
                {{ $error }}
            </div>
        @endforeach
    </div>
@endif
