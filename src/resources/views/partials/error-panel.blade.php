@if ($errors->any())
    <div class="panel error-panel">
        <div class="error-title">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

