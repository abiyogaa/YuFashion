@extends('layout')

@section('title', 'Welcome to Yufashion')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-semibold text-center mb-8">Available Costumes</h1>

    <!-- Search Box -->
    <div class="flex justify-center mb-8">
        <form action="{{ route('user.dashboard') }}" method="GET" class="w-full max-w-lg">
            <div class="flex items-center border-b border-teal-500 py-2">
                <input name="search" class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Search costumes or categories..." value="{{ request('search') }}">
                <button type="submit" class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded">
                    Search
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($clothingItems as $item)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Stock Status Badge -->
                <div class="relative">
                    @if($item->stock == 0)
                        <div class="absolute top-0 right-0 bg-red-500 text-white px-4 py-1 rounded-bl z-10">
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
                    <h5 class="text-xl font-bold mb-2">{{ $item->name }}</h5>
                    <p class="text-gray-700 mb-4">{{ $item->description }}</p>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-semibold">Price: Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-gray-500 {{ $item->stock == 0 ? 'text-red-500 font-semibold' : '' }}">
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
                               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block">
                                Rent Now
                            </a>
                        @else
                            <button 
                                class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed w-full"
                                onclick="alert('Sorry, this costume is currently not available.')">
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