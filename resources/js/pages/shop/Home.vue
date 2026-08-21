<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';
import { useToast } from '@/composables/useToast';

interface HeroSlide {
    image: string;
    link: string | null;
    title?: string;
    subtitle?: string;
    buttonText?: string;
}

interface Category {
    name: string;
    slug: string;
    image: string | null;
    productCount?: number;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    oldPrice?: number | null;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    tag?: string;
    category?: string;
}

const props = defineProps<{
    heroSlides: HeroSlide[];
    categories: Category[];
    bestSelling: Product[];
    newCollection: Product[];
}>();

// Composables
const { addToCart, buyNow } = useCart();
const { toggleWish, hasWish } = useWishlist();
const { showToast } = useToast();

// Carousel Logic — driven by heroSlides prop
const currentSlide = ref(0);
let autoTimer: ReturnType<typeof setInterval> | null = null;
const AUTO_MS = 6000;

const slideCount = computed(() => props.heroSlides.length || 1);

const goToSlide = (index: number) => {
    currentSlide.value = ((index % slideCount.value) + slideCount.value) % slideCount.value;
};

const nextSlide = () => goToSlide(currentSlide.value + 1);
const prevSlide = () => goToSlide(currentSlide.value - 1);

const startAutoPlay = () => {
    stopAutoPlay();
    autoTimer = setInterval(nextSlide, AUTO_MS);
};

const stopAutoPlay = () => {
    if (autoTimer) {
        clearInterval(autoTimer);
        autoTimer = null;
    }
};

// Touch swipe logic
let touchStartX = 0;
const handleTouchStart = (e: TouchEvent) => {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoPlay();
};
const handleTouchEnd = (e: TouchEvent) => {
    const dx = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(dx) > 40) {
        if (dx < 0) {
            nextSlide();
        } else {
            prevSlide();
        }
    }
    startAutoPlay();
};

// Wishlist interaction
const handleWishlistToggle = (product: Product) => {
    const isAdded = toggleWish(product);
    if (isAdded) {
        showToast(`Added to wishlist: ${product.name} ❤️`);
    } else {
        showToast(`Removed from wishlist: ${product.name}`);
    }
};

// Cart interactions
const handleAddToCart = (product: Product) => {
    addToCart(product.name, product.price, product.img);
    showToast(`Added to cart: ${product.name} 🛒`);
};

const handleBuyNow = (product: Product) => {
    buyNow(product.name, product.price, product.img);
    showToast(`Added to cart: ${product.name}. Proceeding to checkout... 💳`);
};

// Image URL helper — Unsplash photo codes stored in DB are raw codes like "photo-xxx"
const productImageUrl = (img: string) => {
    if (!img) {
        return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=600&q=80';
    }
    if (img.startsWith('http') || img.startsWith('/storage/')) {
        return img;
    }
    return `https://images.unsplash.com/${img}?auto=format&fit=crop&w=600&q=80`;
};

const categoryImageUrl = (img: string) => {
    if (!img) {
        return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=400&q=80';
    }
    if (img.startsWith('http') || img.startsWith('/storage/')) {
        return img;
    }
    return `https://images.unsplash.com/${img}?auto=format&fit=crop&w=400&q=80`;
};

const heroImageUrl = (img: string) => {
    if (!img) {
        return '';
    }
    if (img.startsWith('http') || img.startsWith('/storage/')) {
        return img;
    }
    return `https://images.unsplash.com/${img}?auto=format&fit=crop&w=1920&q=80`;
};

// Newsletter Form
const newsletterEmail = ref('');
const handleNewsletterSubmit = () => {
    if (newsletterEmail.value) {
        showToast('Subscribed! Check your inbox 🎉');
        newsletterEmail.value = '';
    }
};

// Currency helper
const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

// Helper for rating stars
const getStarRating = (rating: number) => {
    return Math.floor(rating);
};

onMounted(() => {
    startAutoPlay();
});

onUnmounted(() => {
    stopAutoPlay();
});
</script>

<template>
    <Head title="ShopEase — Quality products, delivered across Bangladesh" />

    <!-- ============================ HERO CAROUSEL ============================ -->
    <section
        class="relative bg-gradient-to-b from-gray-50 to-white"
        aria-roledescription="carousel"
        aria-label="Featured promotions"
    >
        <div
            @mouseenter="stopAutoPlay"
            @mouseleave="startAutoPlay"
            @touchstart="handleTouchStart"
            @touchend="handleTouchEnd"
            class="relative h-[50vh] overflow-hidden sm:h-[60vh] lg:h-[80vh]"
        >
            <!-- Slides -->
            <div
                v-for="(slide, index) in heroSlides"
                :key="index"
                :class="[
                    'absolute inset-0 transition-opacity duration-1000 ease-in-out',
                    { 'opacity-100 z-10': currentSlide === index, 'opacity-0 z-0': currentSlide !== index },
                ]"
                role="group"
                aria-roledescription="slide"
                :aria-label="`${index + 1} of ${heroSlides.length}`"
            >
                <img
                    :src="heroImageUrl(slide.image)"
                    alt="Featured promotion banner"
                    class="h-full w-full object-cover"
                />
                <!-- Gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                
                <!-- Slide content -->
                <div class="absolute inset-0 flex items-center">
                    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="max-w-xl">
                            <div class="animate-slide-up">
                                <span class="inline-block rounded-full bg-white/20 px-4 py-1.5 text-sm font-medium text-white backdrop-blur-sm">
                                    New Season 2026
                                </span>
                                <h2 class="mt-4 font-display text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                                    {{ slide.title || 'Discover Amazing Deals' }}
                                </h2>
                                <p class="mt-4 text-lg text-white/90 sm:text-xl">
                                    {{ slide.subtitle || 'Quality products at unbeatable prices, delivered to your doorstep' }}
                                </p>
                                <div class="mt-8 flex flex-wrap gap-4">
                                    <a
                                        :href="slide.link || '/shop'"
                                        class="inline-flex items-center justify-center rounded-full bg-white px-8 py-3 text-base font-semibold text-gray-900 shadow-lg transition-all duration-300 hover:scale-105 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2"
                                    >
                                        {{ slide.buttonText || 'Shop Now' }}
                                        <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                    <a
                                        href="#categories"
                                        class="inline-flex items-center justify-center rounded-full border-2 border-white/50 px-8 py-3 text-base font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-white/20 hover:border-white"
                                    >
                                        Browse Categories
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prev / Next controls -->
            <button
                @click="prevSlide"
                type="button"
                aria-label="Previous slide"
                class="absolute top-1/2 left-4 z-20 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md transition-all duration-300 hover:bg-white hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-white md:flex"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                @click="nextSlide"
                type="button"
                aria-label="Next slide"
                class="absolute top-1/2 right-4 z-20 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md transition-all duration-300 hover:bg-white hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-white md:flex"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Dot indicators -->
            <div class="absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 items-center gap-3">
                <button
                    v-for="(_, index) in heroSlides"
                    :key="index"
                    @click="goToSlide(index)"
                    type="button"
                    :aria-label="`Go to slide ${index + 1}`"
                    :aria-current="currentSlide === index ? 'true' : 'false'"
                    :class="[
                        'h-2.5 rounded-full transition-all duration-300',
                        currentSlide === index ? 'w-8 bg-white' : 'w-2.5 bg-white/50 hover:bg-white/75',
                    ]"
                ></button>
            </div>
        </div>
    </section>

    <!-- ============================ TRUST STRIP ============================ -->
    <section class="relative z-10 border-b border-gray-200 bg-white shadow-sm" aria-label="Why shop with us">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-4 py-6 md:grid-cols-4 md:gap-8">
                <div class="flex items-center gap-3 group">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H3m10 11h2.5a1 1 0 001-.7l1.4-4.2a1 1 0 00-.95-1.3H13" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Nationwide Delivery</p>
                        <p class="text-xs text-gray-500 mt-0.5">Inside & outside Dhaka</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 group">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-6c1.11 0 2.08.402 2.599 1M12 18c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Cash on Delivery</p>
                        <p class="text-xs text-gray-500 mt-0.5">Pay when you receive</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 group">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Secure Payment</p>
                        <p class="text-xs text-gray-500 mt-0.5">SSLCommerz protected</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 group">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m-3.536-3.536a4 4 0 010-5.656M12 12h.01" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Genuine Products</p>
                        <p class="text-xs text-gray-500 mt-0.5">Quality guaranteed</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ STATS ============================ -->
    <section class="bg-gradient-to-r from-primary-50 via-white to-primary-50 py-12 md:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 text-center sm:grid-cols-4">
                <div class="group">
                    <p class="font-display text-4xl font-bold tracking-tight text-primary-600 transition-transform duration-300 group-hover:scale-110 md:text-5xl">
                        50K+
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-600">Happy Customers</p>
                    <div class="mt-3 h-0.5 w-12 bg-primary-600 mx-auto rounded-full opacity-0 transition-all duration-300 group-hover:opacity-100"></div>
                </div>
                <div class="group">
                    <p class="font-display text-4xl font-bold tracking-tight text-primary-600 transition-transform duration-300 group-hover:scale-110 md:text-5xl">
                        4.8<span class="text-xl text-gray-400">/5</span>
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-600">Average Rating</p>
                    <div class="mt-3 h-0.5 w-12 bg-primary-600 mx-auto rounded-full opacity-0 transition-all duration-300 group-hover:opacity-100"></div>
                </div>
                <div class="group">
                    <p class="font-display text-4xl font-bold tracking-tight text-primary-600 transition-transform duration-300 group-hover:scale-110 md:text-5xl">
                        64
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-600">Districts Covered</p>
                    <div class="mt-3 h-0.5 w-12 bg-primary-600 mx-auto rounded-full opacity-0 transition-all duration-300 group-hover:opacity-100"></div>
                </div>
                <div class="group">
                    <p class="font-display text-4xl font-bold tracking-tight text-primary-600 transition-transform duration-300 group-hover:scale-110 md:text-5xl">
                        24/7
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-600">Customer Support</p>
                    <div class="mt-3 h-0.5 w-12 bg-primary-600 mx-auto rounded-full opacity-0 transition-all duration-300 group-hover:opacity-100"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ CATEGORIES ============================ -->
    <section id="categories" class="bg-white py-12 md:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between md:mb-12">
                <div>
                    <span class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Categories</span>
                    <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
                        Shop by Category
                    </h2>
                    <p class="mt-3 text-base text-gray-600">
                        Find exactly what you're looking for
                    </p>
                </div>
                <Link
                    href="/shop"
                    class="hidden items-center gap-2 rounded-full border-2 border-primary-600 px-6 py-2.5 text-sm font-semibold text-primary-600 transition-all duration-300 hover:bg-primary-600 hover:text-white sm:inline-flex"
                >
                    All Categories
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-6">
                <Link
                    v-for="cat in categories"
                    :key="cat.slug"
                    :href="`/shop?category=${cat.slug}`"
                    class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:shadow-2xl hover:border-primary-600 hover:-translate-y-1"
                >
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img
                            v-if="cat.image"
                            :src="categoryImageUrl(cat.image)"
                            :alt="cat.name"
                            loading="lazy"
                            class="h-full w-full object-cover transition-all duration-500 group-hover:scale-110"
                        />
                        <div
                            v-else
                            class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200"
                        >
                            <span class="text-gray-400 text-sm">No Image</span>
                        </div>
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <!-- Arrow icon on hover -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-all duration-300 group-hover:opacity-100">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-primary-600 transform translate-y-2 transition-transform duration-300 group-hover:translate-y-0">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <span class="block px-3 py-4 text-center text-sm font-semibold text-gray-900 transition-colors group-hover:text-primary-600">
                        {{ cat.name }}
                    </span>
                </Link>
            </div>
        </div>
    </section>

    <!-- ============================ BEST SELLING ============================ -->
    <section id="bestselling" class="bg-gradient-to-b from-gray-50 to-gray-100 py-12 md:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between md:mb-12">
                <div>
                    <span class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Hot Right Now</span>
                    <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
                        Best Selling Products
                    </h2>
                    <p class="mt-3 text-base text-gray-600">
                        Loved most by our customers this month
                    </p>
                </div>
                <Link
                    href="/shop"
                    class="hidden items-center gap-2 rounded-full border-2 border-primary-600 px-6 py-2.5 text-sm font-semibold text-primary-600 transition-all duration-300 hover:bg-primary-600 hover:text-white sm:inline-flex"
                >
                    View All
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>
            
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4">
                <article
                    v-for="p in bestSelling"
                    :key="p.name"
                    class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-2xl hover:border-primary-600 hover:-translate-y-2"
                >
                    <!-- Quick actions -->
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
                        <span
                            v-if="p.inStock"
                            class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 shadow-sm backdrop-blur-sm"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            In Stock
                        </span>
                        <span
                            v-if="p.tag"
                            class="rounded-full bg-gradient-to-r from-accent-600 to-accent-500 px-3 py-1 text-xs font-semibold text-white shadow-sm"
                        >
                            {{ p.tag }}
                        </span>
                    </div>
                    
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <Link :href="`/product-details/${p.slug}`" :aria-label="`View ${p.name}`" class="block h-full w-full">
                            <img
                                :src="productImageUrl(p.img)"
                                :alt="p.name"
                                loading="lazy"
                                :class="[
                                    'h-full w-full object-cover transition-all duration-500 group-hover:scale-110',
                                    { 'grayscale': !p.inStock }
                                ]"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        </Link>
                        
                        <!-- Wishlist button -->
                        <button
                            @click="handleWishlistToggle(p)"
                            type="button"
                            aria-label="Add to wishlist"
                            :aria-pressed="hasWish(p.id || p.name)"
                            :class="[
                                'absolute top-3 right-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 shadow-md backdrop-blur-sm transition-all duration-300 hover:scale-110 active:scale-95',
                                hasWish(p.id || p.name) ? 'text-red-600' : 'text-gray-700 hover:text-red-600'
                            ]"
                        >
                            <svg
                                class="h-5 w-5"
                                :fill="hasWish(p.id || p.name) ? 'currentColor' : 'none'"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        
                        <!-- Stock out overlay -->
                        <div v-if="!p.inStock" class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm">
                            <span class="rounded-lg bg-gray-900/90 px-6 py-2.5 text-sm font-semibold tracking-wide text-white uppercase shadow-lg">
                                Sold Out
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-1 flex-col p-4 md:p-5">
                        <Link :href="`/product-details/${p.slug}`" class="block">
                            <h3 class="line-clamp-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-primary-600 md:text-base">
                                {{ p.name }}
                            </h3>
                        </Link>
                        
                        <!-- Rating -->
                        <div class="mt-2 flex items-center gap-1.5">
                            <div class="flex">
                                <svg
                                    v-for="starIndex in 5"
                                    :key="starIndex"
                                    :class="[
                                        'h-4 w-4',
                                        starIndex <= getStarRating(p.rating) ? 'text-yellow-400' : 'text-gray-300'
                                    ]"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500">({{ p.reviews }})</span>
                        </div>
                        
                        <!-- Price -->
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-lg font-bold text-primary-600 md:text-xl">
                                {{ formatPrice(p.price) }}
                            </span>
                            <span v-if="p.oldPrice" class="text-sm text-gray-400 line-through">
                                {{ formatPrice(p.oldPrice) }}
                            </span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-auto pt-4">
                            <div class="flex flex-col gap-2">
                                <button
                                    v-if="p.inStock"
                                    @click="handleAddToCart(p)"
                                    type="button"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-primary-600 px-4 py-2.5 text-sm font-semibold text-primary-600 transition-all duration-300 hover:bg-primary-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-600 active:scale-95"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-400"
                                >
                                    Out of Stock
                                </button>
                                
                                <button
                                    v-if="p.inStock"
                                    @click="handleBuyNow(p)"
                                    type="button"
                                    class="w-full rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-300 hover:shadow-lg hover:from-primary-700 hover:to-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 active:scale-95"
                                >
                                    Buy Now
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="w-full cursor-not-allowed rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-400"
                                >
                                    Notify Me
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================ NEW COLLECTION ============================ -->
    <section id="newcollection" class="bg-white py-12 md:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between md:mb-12">
                <div>
                    <span class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Just Arrived</span>
                    <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
                        New Collection
                    </h2>
                    <p class="mt-3 text-base text-gray-600">
                        Be the first to grab them
                    </p>
                </div>
                <Link
                    href="/shop"
                    class="hidden items-center gap-2 rounded-full border-2 border-primary-600 px-6 py-2.5 text-sm font-semibold text-primary-600 transition-all duration-300 hover:bg-primary-600 hover:text-white sm:inline-flex"
                >
                    View All
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>
            
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4">
                <article
                    v-for="p in newCollection"
                    :key="p.name"
                    class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-2xl hover:border-primary-600 hover:-translate-y-2"
                >
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
                        <span
                            v-if="p.inStock"
                            class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 shadow-sm backdrop-blur-sm"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            In Stock
                        </span>
                        <span
                            v-if="p.tag"
                            class="rounded-full bg-gradient-to-r from-accent-600 to-accent-500 px-3 py-1 text-xs font-semibold text-white shadow-sm"
                        >
                            {{ p.tag }}
                        </span>
                    </div>
                    
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <Link :href="`/product-details/${p.slug}`" :aria-label="`View ${p.name}`" class="block h-full w-full">
                            <img
                                :src="productImageUrl(p.img)"
                                :alt="p.name"
                                loading="lazy"
                                :class="[
                                    'h-full w-full object-cover transition-all duration-500 group-hover:scale-110',
                                    { 'grayscale': !p.inStock }
                                ]"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        </Link>
                        
                        <button
                            @click="handleWishlistToggle(p)"
                            type="button"
                            aria-label="Add to wishlist"
                            :aria-pressed="hasWish(p.id || p.name)"
                            :class="[
                                'absolute top-3 right-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 shadow-md backdrop-blur-sm transition-all duration-300 hover:scale-110 active:scale-95',
                                hasWish(p.id || p.name) ? 'text-red-600' : 'text-gray-700 hover:text-red-600'
                            ]"
                        >
                            <svg
                                class="h-5 w-5"
                                :fill="hasWish(p.id || p.name) ? 'currentColor' : 'none'"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        
                        <div v-if="!p.inStock" class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm">
                            <span class="rounded-lg bg-gray-900/90 px-6 py-2.5 text-sm font-semibold tracking-wide text-white uppercase shadow-lg">
                                Sold Out
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-1 flex-col p-4 md:p-5">
                        <Link :href="`/product-details/${p.slug}`" class="block">
                            <h3 class="line-clamp-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-primary-600 md:text-base">
                                {{ p.name }}
                            </h3>
                        </Link>
                        
                        <div class="mt-2 flex items-center gap-1.5">
                            <div class="flex">
                                <svg
                                    v-for="starIndex in 5"
                                    :key="starIndex"
                                    :class="[
                                        'h-4 w-4',
                                        starIndex <= getStarRating(p.rating) ? 'text-yellow-400' : 'text-gray-300'
                                    ]"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500">({{ p.reviews }})</span>
                        </div>
                        
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-lg font-bold text-primary-600 md:text-xl">
                                {{ formatPrice(p.price) }}
                            </span>
                            <span v-if="p.oldPrice" class="text-sm text-gray-400 line-through">
                                {{ formatPrice(p.oldPrice) }}
                            </span>
                        </div>
                        
                        <div class="mt-auto pt-4">
                            <div class="flex flex-col gap-2">
                                <button
                                    v-if="p.inStock"
                                    @click="handleAddToCart(p)"
                                    type="button"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-primary-600 px-4 py-2.5 text-sm font-semibold text-primary-600 transition-all duration-300 hover:bg-primary-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-600 active:scale-95"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-400"
                                >
                                    Out of Stock
                                </button>
                                
                                <button
                                    v-if="p.inStock"
                                    @click="handleBuyNow(p)"
                                    type="button"
                                    class="w-full rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-300 hover:shadow-lg hover:from-primary-700 hover:to-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 active:scale-95"
                                >
                                    Buy Now
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="w-full cursor-not-allowed rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-400"
                                >
                                    Notify Me
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================ TESTIMONIALS ============================ -->
    <section class="bg-gradient-to-b from-gray-50 to-white py-12 md:py-16 lg:py-20" aria-label="Customer testimonials">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center md:mb-12">
                <span class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Testimonials</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
                    What Our Customers Say
                </h2>
                <p class="mt-3 text-base text-gray-600">
                    Real reviews from real ShopEase shoppers across Bangladesh
                </p>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-2xl hover:border-primary-200 hover:-translate-y-1 md:p-8">
                    <div class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-primary-600 to-accent-500 transform origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
                    <div class="flex text-yellow-400">
                        <svg v-for="star in 5" :key="star" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-gray-600">
                        "Ordered a phone case and it arrived in two days, well before I expected. Cash on delivery made it an easy first purchase."
                    </p>
                    <div class="mt-6 flex items-center gap-3">
                        <img
                            src="https://placehold.co/80x80/F5F3FF/7C3AED?text=RA"
                            alt="Rina Akter"
                            class="h-12 w-12 rounded-full border-2 border-primary-100 object-cover"
                        />
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Rina Akter</p>
                            <p class="text-xs text-gray-500">Dhaka</p>
                        </div>
                    </div>
                </div>
                
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-2xl hover:border-primary-200 hover:-translate-y-1 md:p-8">
                    <div class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-primary-600 to-accent-500 transform origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
                    <div class="flex text-yellow-400">
                        <svg v-for="star in 5" :key="star" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-gray-600">
                        "Great prices on electronics and the size guide on the clothing pages actually matches what shows up. Will keep shopping here."
                    </p>
                    <div class="mt-6 flex items-center gap-3">
                        <img
                            src="https://placehold.co/80x80/F5F3FF/7C3AED?text=TH"
                            alt="Tanvir Hasan"
                            class="h-12 w-12 rounded-full border-2 border-primary-100 object-cover"
                        />
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Tanvir Hasan</p>
                            <p class="text-xs text-gray-500">Chattogram</p>
                        </div>
                    </div>
                </div>
                
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-2xl hover:border-primary-200 hover:-translate-y-1 md:p-8">
                    <div class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-primary-600 to-accent-500 transform origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
                    <div class="flex text-yellow-400">
                        <svg v-for="star in 4" :key="star" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                        <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-gray-600">
                        "Customer support helped me change my delivery address after the order was placed. Small thing, but it made me trust the shop more."
                    </p>
                    <div class="mt-6 flex items-center gap-3">
                        <img
                            src="https://placehold.co/80x80/F5F3FF/7C3AED?text=NJ"
                            alt="Nusrat Jahan"
                            class="h-12 w-12 rounded-full border-2 border-primary-100 object-cover"
                        />
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Nusrat Jahan</p>
                            <p class="text-xs text-gray-500">Sylhet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ NEWSLETTER ============================ -->
    <section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-primary-700">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-white"></div>
            <div class="absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-white"></div>
        </div>
        
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <div class="flex flex-col items-center gap-8 text-center md:flex-row md:justify-between md:text-left">
                <div class="max-w-lg">
                    <span class="text-sm font-semibold text-white/80 uppercase tracking-wider">Special Offer</span>
                    <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-white md:text-4xl">
                        Get 10% Off Your First Order
                    </h2>
                    <p class="mt-3 text-base text-white/90">
                        Subscribe for new arrivals, exclusive deals, and offers delivered to your inbox.
                    </p>
                </div>
                
                <form @submit.prevent="handleNewsletterSubmit" class="w-full max-w-md">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <label for="newsletterEmail" class="sr-only">Email address</label>
                        <input
                            id="newsletterEmail"
                            v-model="newsletterEmail"
                            type="email"
                            required
                            placeholder="Enter your email"
                            class="w-full rounded-xl border-2 border-transparent bg-white/95 px-5 py-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-lg backdrop-blur-sm transition-all duration-300 focus:border-white focus:outline-none focus:ring-2 focus:ring-white"
                        />
                        <button
                            type="submit"
                            class="shrink-0 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-primary-600 shadow-lg transition-all duration-300 hover:bg-gray-100 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white active:scale-95"
                        >
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';

export default {
    layout: ShopLayout,
};
</script>

<style scoped>
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out forwards;
}
</style>