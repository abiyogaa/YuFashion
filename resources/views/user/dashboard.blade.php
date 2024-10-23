@extends('layout')

@section('title', 'Welcome to Yufashion')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-zinc-800 bg-clip-text text-transparent">
            Available Costumes
        </h1>
    </div>

    <!-- Search Box -->
    <div class="flex justify-center mb-8">
        <form action="{{ route('user.dashboard') }}" method="GET" class="w-full max-w-lg">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input name="search" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           type="text" 
                           placeholder="Search costumes or categories..." 
                           value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('user.dashboard') }}" 
                           class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-slate-800 to-zinc-800 text-white rounded-lg hover:from-slate-700 hover:to-zinc-700 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($clothingItems as $item)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                <!-- Stock Status Badge -->
                <div class="relative">
                    @if($item->stock == 0)
                        <div class="absolute top-0 right-0 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-1 rounded-bl z-10">
                            Out of Stock
                        </div>
                    @endif
                    
                    <!-- Image Carousel -->
                    <div class="relative carousel-container h-48" data-item-id="{{ $item->id }}">
                        <div class="carousel-slides h-full flex transition-transform duration-300">
                            @foreach($item->images as $image)
                                <div class="carousel-slide w-full h-full flex-shrink-0">
                                <img class="w-full h-full object-cover {{ $item->stock == 0 ? 'opacity-70' : '' }}" 
                                    src="{{ asset('storage/' . $image->image_path) }}" 
                                    alt="{{ $item->name }}">
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Navigation Arrows -->
                        @if($item->images->count() > 1)
                            <button class="carousel-prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-2 py-4 hover:bg-opacity-75">
                                &#10094;
                            </button>
                            <button class="carousel-next absolute right-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-2 py-4 hover:bg-opacity-75">
                                &#10095;
                            </button>

                            <!-- Dots -->
                            <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-2">
                                @foreach($item->images as $key => $image)
                                    <button class="carousel-dot w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition-all {{ $key === 0 ? 'bg-opacity-100' : '' }}"
                                            data-slide="{{ $key }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <h5 class="text-xl font-bold mb-2 bg-gradient-to-r from-slate-800 to-zinc-800 bg-clip-text text-transparent">{{ $item->name }}</h5>
                    <p class="text-gray-700 mb-4">{{ $item->description }}</p>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-semibold">Price: Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-gray-500 {{ $item->stock == 0 ? 'text-red-600 font-semibold' : '' }}">
                            Stock: {{ $item->stock }}
                        </span>
                    </div>

                    <p class="mt-2 text-gray-500 text-sm">
                        Category:
                        @foreach($item->categories as $category)
                            <span class="text-gray-700">{{ $category->name }}@if(!$loop->last), @endif</span>
                        @endforeach
                    </p>

                    <!-- Button to Rent with Availability Check -->
                    <div class="mt-4">
                        @if($item->stock > 0)
                            <a href="{{ route('rent.form', ['clothing_item_id' => $item->id]) }}" 
                               class="bg-gradient-to-r from-slate-800 to-zinc-800 text-white px-4 py-2 rounded hover:from-slate-700 hover:to-zinc-700 transition-all duration-300 transform hover:scale-105 inline-block">
                                Rent Now
                            </a>
                        @else
                            <button 
                                class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed w-full"
                                onclick="alert('Sorry, this costume is currently not available.')"
                            >
                                Not Available
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">No costumes found.</p>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.carousel-container').forEach(container => {
        let currentSlide = 0;
        const slides = container.querySelector('.carousel-slides');
        const slideCount = container.querySelectorAll('.carousel-slide').length;
        const dots = container.querySelectorAll('.carousel-dot');
        
        if (slideCount <= 1) return; // Skip if only one or no slides
        
        // Initialize autoplay
        let autoplayInterval = setInterval(() => moveSlide(1), 5000);
        
        // Reset autoplay on user interaction
        function resetAutoplay() {
            clearInterval(autoplayInterval);
            autoplayInterval = setInterval(() => moveSlide(1), 5000);
        }
        
        function moveSlide(direction) {
            currentSlide = (currentSlide + direction + slideCount) % slideCount;
            updateCarousel();
            resetAutoplay();
        }
        
        function jumpToSlide(index) {
            currentSlide = index;
            updateCarousel();
            resetAutoplay();
        }
        
        function updateCarousel() {
            slides.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-opacity-100', index === currentSlide);
                dot.classList.toggle('bg-opacity-50', index !== currentSlide);
            });
        }
        
        // Event Listeners
        container.querySelector('.carousel-prev')?.addEventListener('click', () => moveSlide(-1));
        container.querySelector('.carousel-next')?.addEventListener('click', () => moveSlide(1));
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => jumpToSlide(index));
        });
        
        // Mouse enter/leave for autoplay
        container.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
        container.addEventListener('mouseleave', () => {
            autoplayInterval = setInterval(() => moveSlide(1), 5000);
        });
        
        // Touch support
        let touchStartX = 0;
        let touchEndX = 0;
        
        container.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        
        container.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
        
        function handleSwipe() {
            const difference = touchStartX - touchEndX;
            if (Math.abs(difference) > 50) { // Minimum swipe distance
                moveSlide(difference > 0 ? 1 : -1);
            }
        }
    });
});
</script>
@endsection