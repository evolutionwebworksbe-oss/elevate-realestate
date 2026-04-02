@props([
    'path',
    'alt' => '',
    'sizes' => '100vw',
    'loading' => 'lazy',
    'decoding' => 'async',
])

@php
    use App\Services\ImageService;

    $relativePath = ltrim($path, '/');
    $src = asset($relativePath);
    $srcsetEntries = [];

    foreach (ImageService::existingResponsiveVariantPaths($relativePath) as $width => $variantRelativePath) {
        $srcsetEntries[] = asset($variantRelativePath) . ' ' . $width . 'w';
    }

    $srcset = implode(', ', $srcsetEntries);
@endphp

<img
    src="{{ $src }}"
    @if($srcset) srcset="{{ $srcset }}" @endif
    sizes="{{ $sizes }}"
    alt="{{ $alt }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    {{ $attributes }}
>
