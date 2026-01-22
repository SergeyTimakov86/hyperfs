@php $navbarId = 'nb-tg'; @endphp

<nav class="navbar navbar-expand-md sticky-top bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">MMOrket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" aria-expanded="true"
                data-bs-target="#{{ $navbarId }}"
                aria-controls="{{ $navbarId }}"
                aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="{{ $navbarId }}">
            <div class="navbar-nav">
                @foreach ($links as $path => $label)
                    @php $active = $path === current_path(); @endphp
                    <a class="nav-link {{ $active ? 'active' : '' }}"
                       {{ $active ? ' aria-current="page" ' : ' ' }}
                       href="{{ $path }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</nav>
