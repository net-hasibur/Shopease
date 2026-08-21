e<script setup lang="ts">
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useToast } from '@/composables/useToast';
import { useWishlist, type WishlistItem } from '@/composables/useWishlist';

const props = defineProps<{
    products?: WishlistItem[];
}>();

const { addToCart } = useCart();
const { showToast } = useToast();
const { wishlist, removeFromWishlist, setWishlist, clearWishlist } = useWishlist();

onMounted(() => {
    if (props.products && props.products.length > 0) {
        setWishlist(props.products);
    }
});

const formatPrice = (price?: number) => {
    if (price === undefined || price === null) return '';
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

const getImageUrl = (img?: string) => {
    if (!img) return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=60';
    if (img.startsWith('http') || img.startsWith('/storage/')) return img;
    return `https://images.unsplash.com/${img}?w=500&auto=format&fit=crop&q=60`;
};

const handleAddToCart = (product: WishlistItem) => {
    addToCart(product.name, product.price || 0, product.img || '', 1);
    showToast('Added to cart successfully!');
};

const handleRemove = (product: WishlistItem) => {
    removeFromWishlist(product.id || product.name);
    showToast(`Removed from wishlist: ${product.name}`);
};

const handleClear = () => {
    clearWishlist();
    showToast('Wishlist cleared');
};
</script>

<template>
    <Head title="My Wishlist | ShopEase" />

    <main id="main" class="flex-1 bg-gradient-to-b from-gray-50 to-white min-h-screen">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Breadcrumbs -->
            <nav class="mb-8 flex" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2 text-sm">
                    <li class="inline-flex items-center">
                        <Link
                            href="/"
                            class="inline-flex items-center font-medium text-gray-500 hover:text-primary-600 transition-colors"
                        >
                            <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Home
                        </Link>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <Link href="/shop" class="font-medium text-gray-500 hover:text-primary-600 transition-colors">Shop</Link>
                    </li>
                    <li class="flex items-center" aria-current="page">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold text-gray-900">Wishlist</span>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                            <span class="relative">
                                My Wishlist
                                <span class="absolute -bottom-2 left-0 h-1 w-20 bg-gradient-to-r from-primary-600 to-primary-400 rounded-full"></span>
                            </span>
                            <span class="inline-flex items-center justify-center h-10 w-10 bg-red-50 rounded-full">
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </span>
                        </h1>
                        <p class="mt-3 text-gray-600 text-lg">Save your favorite items for later</p>
                    </div>

                    <div v-if="wishlist.length > 0" class="flex items-center gap-4">
                        <span class="px-4 py-2 bg-gradient-to-r from-primary-50 to-primary-100 text-primary-700 rounded-full text-sm font-semibold shadow-sm">
                            {{ wishlist.length }} {{ wishlist.length === 1 ? 'Item' : 'Items' }}
                        </span>
                        <button
                            @click="handleClear"
                            type="button"
                            class="group inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-semibold border border-gray-200 hover:border-red-200 hover:bg-red-50 hover:text-red-600 transition-all shadow-sm hover:shadow"
                        >
                            <svg class="h-4 w-4 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Clear All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Wishlist Grid -->
            <div v-if="wishlist.length > 0">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <article
                        v-for="p in wishlist"
                        :key="p.id || p.name"
                        class="group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                    >
                        <!-- Image Section -->
                        <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                            <img
                                :src="getImageUrl(p.img)"
                                :alt="p.name"
                                class="h-full w-full object-cover object-center transition duration-700 group-hover:scale-110"
                                loading="lazy"
                            />

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Tag Badge -->
                            <div v-if="p.tag" class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-primary-600 to-primary-500 text-white text-xs font-bold tracking-wide uppercase shadow-lg">
                                    {{ p.tag }}
                                </span>
                            </div>

                            <!-- Stock Badge -->
                            <div v-if="p.inStock === false" class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-red-500 text-white text-xs font-bold tracking-wide uppercase shadow-lg">
                                    Out of Stock
                                </span>
                            </div>

                            <!-- Remove Button -->
                            <button
                                @click="handleRemove(p)"
                                type="button"
                                aria-label="Remove from wishlist"
                                class="absolute top-4 right-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 backdrop-blur-sm text-gray-400 shadow-lg transition-all hover:bg-red-500 hover:text-white hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Product Info -->
                        <div class="flex flex-1 flex-col p-5">
                            <div v-if="p.category" class="flex items-center gap-2 mb-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-primary-400"></span>
                                <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    {{ p.category }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-gray-900 line-clamp-2 min-h-[48px] leading-snug">
                                <Link
                                    :href="p.slug ? `/product-details/${p.slug}` : '/shop'"
                                    class="hover:text-primary-600 transition-colors"
                                >
                                    {{ p.name }}
                                </Link>
                            </h3>

                            <!-- Rating -->
                            <div v-if="p.rating" class="mt-3 flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    <svg
                                        v-for="star in 5"
                                        :key="star"
                                        class="h-4 w-4"
                                        :class="star <= Math.round(p.rating || 5) ? 'text-amber-400 fill-current' : 'text-gray-300'"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.908c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.908a1 1 0 00.95-.69l1.519-4.674z"
                                        />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-500">({{ p.reviews || 0 }} reviews)</span>
                            </div>

                            <!-- Price -->
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-xl font-bold text-primary-600">
                                    {{ formatPrice(p.price) }}
                                </span>
                                <span v-if="p.oldPrice" class="text-sm text-gray-400 line-through">
                                    {{ formatPrice(p.oldPrice) }}
                                </span>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto pt-4">
                                <button
                                    v-if="p.inStock !== false"
                                    @click="handleAddToCart(p)"
                                    type="button"
                                    class="relative inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-4 py-3 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg hover:from-primary-700 hover:to-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-95"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="w-full cursor-not-allowed rounded-xl bg-gray-100 px-4 py-3 text-sm font-semibold text-gray-400 border border-gray-200"
                                >
                                    Out of Stock
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="relative overflow-hidden rounded-3xl bg-white py-20 px-6 text-center shadow-sm border border-gray-100"
            >
                <!-- Decorative Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-red-50 opacity-50"></div>
                <div class="absolute top-0 right-0 h-64 w-64 bg-primary-100 rounded-full filter blur-3xl opacity-20"></div>
                <div class="absolute bottom-0 left-0 h-64 w-64 bg-red-100 rounded-full filter blur-3xl opacity-20"></div>

                <div class="relative">
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-red-50 to-red-100 shadow-lg">
                        <svg class="h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Your wishlist is empty</h3>
                    <p class="mx-auto mt-2 max-w-md text-gray-600">
                        Discover products you love and save them here for quick access later.
                    </p>
                    <div class="mt-8 flex justify-center gap-4">
                        <Link
                            href="/shop"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-8 py-4 text-sm font-bold text-white shadow-lg hover:shadow-xl hover:from-primary-700 hover:to-primary-600 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Explore Catalog
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';

export default {
    layout: ShopLayout,
};
</script>
