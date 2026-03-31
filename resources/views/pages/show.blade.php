<x-app-layout>
    <x-slot name="title">{{ $page->meta_title ?: $page->getTranslatedTitle() }} - Elevate Real Estate</x-slot>

    <!-- Hero -->
    <div class="bg-gradient-to-br from-primary-dark to-primary text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold">{{ $page->getTranslatedTitle() }}</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="prose prose-lg max-w-none">
                {!! $page->getTranslatedContent() !!}
            </div>
        </div>
    </div>

</x-app-layout>
