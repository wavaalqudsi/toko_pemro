@props(['aktif' => false])

<a {{ $attributes->merge(['class' => 'nav-link ' . ($aktif ? 'active fw-semibold text-white' : 'text-white-50')]) }}
   aria-current="{{ $aktif ? 'page' : 'false' }}">
    {{ $slot }}
</a>
