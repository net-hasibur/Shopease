<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

interface OrderItem {
    id: number;
    product_name: string;
    product_slug?: string | null;
    image?: string | null;
    unit_price: number;
    quantity: number;
    line_total: number;
}

interface OrderDetails {
    id: number;
    order_number: string;
    customer_name: string;
    phone: string;
    email: string;
    district: string;
    area: string;
    address: string;
    notes?: string;
    subtotal: number;
    delivery_charge: number;
    discount_amount: number;
    total: number;
    payment_method: string;
    payment_status: string;
    status: string;
    status_raw: string;
    placed_at: string;
    items: OrderItem[];
}

interface QuickOrder {
    id: number;
    order_number: string;
    total: number;
    status: string;
    placed_at: string;
}

const props = defineProps<{
    order?: OrderDetails | null;
    userRecentOrders?: QuickOrder[];
    searchedQuery?: string;
    errorMessage?: string | null;
}>();

const page = usePage();
const searchInput = ref(props.searchedQuery || '');
const copiedNotice = ref(false);

watch(
    () => props.searchedQuery || props.order?.order_number,
    (newVal) => {
        if (newVal) searchInput.value = newVal;
    },
    { immediate: true }
);

const handleSearch = () => {
    if (!searchInput.value.trim()) return;
    router.get('/track-order', { search: searchInput.value.trim() }, { preserveState: false });
};

const selectRecentOrder = (orderNum: string) => {
    searchInput.value = orderNum;
    router.get('/track-order', { order_number: orderNum }, { preserveState: false });
};

const copyOrderNumber = () => {
    if (!props.order?.order_number) return;
    navigator.clipboard.writeText(props.order.order_number);
    copiedNotice.value = true;
    setTimeout(() => {
        copiedNotice.value = false;
    }, 2000);
};

const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

const getItemImg = (img?: string | object | null) => {
    let imgPath = '';
    if (typeof img === 'object' && img !== null) {
        imgPath = (img as any).image_path || (img as any).url || '';
    } else if (typeof img === 'string') {
        imgPath = img;
    }
    if (!imgPath) return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=120&q=80';
    if (imgPath.startsWith('http') || imgPath.startsWith('/storage/')) return imgPath;
    return `https://images.unsplash.com/${imgPath}?auto=format&fit=crop&w=120&q=80`;
};

// Status progression steps calculation
const getStepStatus = (step: string) => {
    const current = props.order?.status_raw || 'pending';
    const orderSteps = ['pending', 'processing', 'shipped', 'delivered'];
    const currentIndex = orderSteps.indexOf(current);
    const stepIndex = orderSteps.indexOf(step);

    if (current === 'cancelled') return 'cancelled';
    if (stepIndex < currentIndex) return 'completed';
    if (stepIndex === currentIndex) return 'current';
    return 'upcoming';
};

// Get status color helper
const getStatusColor = (status: string) => {
    const colors: Record<string, string> = {
        pending: 'bg-amber-50 text-amber-700 border-amber-200',
        processing: 'bg-blue-50 text-blue-700 border-blue-200',
        shipped: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        delivered: 'bg-green-50 text-green-700 border-green-200',
        cancelled: 'bg-red-50 text-red-700 border-red-200',
    };
    return colors[status] || 'bg-gray-50 text-gray-700 border-gray-200';
};

// Get payment status icon
const getPaymentStatusIcon = (status: string) => {
    if (status === 'paid') {
        return '✓';
    } else if (status === 'pending') {
        return '⏳';
    }
    return '💵';
};
</script>

<template>
    <Head title="Track Order — ShopEase" />

    <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen py-8 md:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-6">
                <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500">
                    <li><Link href="/" class="transition-colors hover:text-primary-600">Home</Link></li>
                    <li aria-hidden="true" class="text-gray-300">/</li>
                    <li aria-current="page" class="font-semibold text-gray-900">Track Order</li>
                </ol>
            </nav>

            <!-- Search Header Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-lg shadow-gray-100 mb-8">
                <div class="text-center max-w-xl mx-auto mb-6">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-600 shadow-md">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h1 class="font-display text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Track Your Order
                    </h1>
                    <p class="mt-3 text-base text-gray-600">
                        Enter your Order Number or phone number to check live delivery status
                    </p>
                </div>

                <form @submit.prevent="handleSearch" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                            </svg>
                        </span>
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Order Number or Phone / Email"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 pl-10 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 transition-all focus:border-primary-600 focus:ring-2 focus:ring-primary-100 focus:outline-none"
                        />
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:from-primary-700 hover:to-primary-600 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary-600 active:scale-95"
                    >
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                        Track Status
                    </button>
                </form>

                <!-- Error message -->
                <div v-if="errorMessage" class="mt-4 max-w-xl mx-auto rounded-xl bg-red-50 p-4 text-sm text-red-700 border-2 border-red-200 text-center flex items-center justify-center gap-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ errorMessage }}
                </div>

                <!-- Recent Orders Selector for Logged-In User -->
                <div v-if="userRecentOrders && userRecentOrders.length > 0" class="mt-6 border-t-2 border-gray-100 pt-5">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 text-center">Your Recent Orders</p>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <button
                            v-for="rec in userRecentOrders"
                            :key="rec.id"
                            @click="selectRecentOrder(rec.order_number)"
                            class="inline-flex items-center gap-2 rounded-xl border-2 px-4 py-2 text-xs font-medium transition-all duration-300 hover:shadow-md"
                            :class="order?.order_number === rec.order_number 
                                ? 'border-primary-600 bg-primary-50 text-primary-700 font-bold shadow-md' 
                                : 'border-gray-200 bg-white text-gray-700 hover:border-primary-300 hover:bg-primary-50'"
                        >
                            <span>{{ rec.order_number }}</span>
                            <span class="text-gray-400">•</span>
                            <span>{{ formatPrice(rec.total) }}</span>
                            <span
                                class="rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                :class="getStatusColor(rec.status)"
                            >
                                {{ rec.status }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Details View -->
            <div v-if="order" class="space-y-6">
                <!-- Status Progress Card -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-lg shadow-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-gray-100 pb-6 gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="font-display text-xl font-bold text-gray-900">Order {{ order.order_number }}</h2>
                                <button
                                    @click="copyOrderNumber"
                                    type="button"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1 text-xs font-medium text-gray-600 transition-all hover:border-primary-600 hover:text-primary-600 hover:shadow-sm"
                                >
                                    <span v-if="copiedNotice" class="text-green-600 font-semibold">✓ Copied!</span>
                                    <span v-else class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Copy
                                    </span>
                                </button>
                                <span
                                    class="inline-flex items-center rounded-full border-2 px-3 py-1 text-xs font-bold"
                                    :class="getStatusColor(order.status_raw)"
                                >
                                    {{ order.status }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center gap-1.5">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Placed on {{ order.placed_at }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Amount</span>
                            <p class="font-display text-2xl font-bold text-primary-600">{{ formatPrice(order.total) }}</p>
                            <span class="mt-1 inline-flex items-center gap-1 text-xs text-gray-500">
                                <span>{{ getPaymentStatusIcon(order.payment_status) }}</span>
                                {{ order.payment_method }} — {{ order.payment_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar (Timeline) -->
                    <div class="mt-8">
                        <div class="relative">
                            <!-- Progress line -->
                            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full">
                                <div
                                    class="h-full bg-gradient-to-r from-primary-600 to-green-500 rounded-full transition-all duration-500"
                                    :style="{
                                        width: order.status_raw === 'delivered' ? '100%' : 
                                               order.status_raw === 'shipped' ? '66%' : 
                                               order.status_raw === 'processing' ? '33%' : '0%'
                                    }"
                                ></div>
                            </div>
                            
                            <div class="relative grid grid-cols-4 gap-2 text-center text-xs font-semibold text-gray-600">
                                <div>
                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300"
                                        :class="{
                                            'bg-primary-600 text-white ring-4 ring-primary-100 shadow-lg': getStepStatus('pending') === 'current',
                                            'bg-green-500 text-white shadow-md': getStepStatus('pending') === 'completed',
                                            'bg-gray-100 text-gray-400': getStepStatus('pending') === 'upcoming'
                                        }">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 block">Order Placed</span>
                                </div>
                                <div>
                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300"
                                        :class="{
                                            'bg-primary-600 text-white ring-4 ring-primary-100 shadow-lg animate-pulse': getStepStatus('processing') === 'current',
                                            'bg-green-500 text-white shadow-md': getStepStatus('processing') === 'completed',
                                            'bg-gray-100 text-gray-400': getStepStatus('processing') === 'upcoming'
                                        }">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 block">Processing</span>
                                </div>
                                <div>
                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300"
                                        :class="{
                                            'bg-primary-600 text-white ring-4 ring-primary-100 shadow-lg animate-pulse': getStepStatus('shipped') === 'current',
                                            'bg-green-500 text-white shadow-md': getStepStatus('shipped') === 'completed',
                                            'bg-gray-100 text-gray-400': getStepStatus('shipped') === 'upcoming'
                                        }">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H3m10 11h2.5a1 1 0 001-.7l1.4-4.2a1 1 0 00-.95-1.3H13" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 block">Shipped</span>
                                </div>
                                <div>
                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300"
                                        :class="{
                                            'bg-green-600 text-white ring-4 ring-green-100 shadow-lg': getStepStatus('delivered') === 'current' || getStepStatus('delivered') === 'completed',
                                            'bg-gray-100 text-gray-400': getStepStatus('delivered') === 'upcoming'
                                        }">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 block">Delivered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer & Shipping Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg shadow-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Customer Details</h3>
                        </div>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Name</dt>
                                <dd class="font-semibold text-gray-900">{{ order.customer_name }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Phone</dt>
                                <dd class="font-semibold text-gray-900">{{ order.phone }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Email</dt>
                                <dd class="font-semibold text-gray-900">{{ order.email }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg shadow-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Delivery Address</h3>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ order.address }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ order.area }}, {{ order.district }}</p>
                        <p v-if="order.notes" class="mt-3 text-xs italic text-gray-500 bg-gray-50 rounded-lg p-3">
                            📝 Note: {{ order.notes }}
                        </p>
                    </div>
                </div>

                <!-- Items Summary -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-lg shadow-gray-100">
                    <h3 class="font-display text-lg font-bold text-gray-900 mb-4">Ordered Items</h3>
                    <div class="divide-y divide-gray-100">
                        <div v-for="item in order.items" :key="item.id" class="py-4 flex items-center justify-between gap-4 text-sm">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="getItemImg(item.image)"
                                    :alt="item.product_name"
                                    class="h-14 w-14 rounded-xl object-cover border-2 border-gray-100 shadow-sm shrink-0"
                                />
                                <div>
                                    <Link
                                        v-if="item.product_slug"
                                        :href="`/product-details/${item.product_slug}`"
                                        class="font-semibold text-gray-900 transition-colors hover:text-primary-600"
                                    >
                                        {{ item.product_name }}
                                    </Link>
                                    <p v-else class="font-semibold text-gray-900">{{ item.product_name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Qty: {{ item.quantity }} × {{ formatPrice(item.unit_price) }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-gray-900 shrink-0">{{ formatPrice(item.line_total) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 border-t-2 border-gray-100 pt-4 space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">{{ formatPrice(order.subtotal) }}</span>
                        </div>
                        <div v-if="order.discount_amount > 0" class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span class="font-semibold">- {{ formatPrice(order.discount_amount) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery Charge</span>
                            <span class="font-semibold">{{ formatPrice(order.delivery_charge) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t-2 border-gray-100">
                            <span>Total</span>
                            <span class="text-primary-600">{{ formatPrice(order.total) }}</span>
                        </div>
                    </div>
                </div>

                <!-- LOGIN / ACCOUNT BANNER -->
                <div 
                    v-if="!page.props.auth?.user" 
                    class="rounded-2xl border-2 border-primary-200 bg-gradient-to-r from-primary-50 to-white p-6 sm:p-8 shadow-lg shadow-primary-100"
                >
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="text-center sm:text-left">
                            <div class="flex items-center gap-2 text-primary-900 font-bold text-lg mb-2 justify-center sm:justify-start">
                                <span class="text-2xl">🔑</span> 
                                Want to manage & track all your orders?
                            </div>
                            <p class="text-sm text-primary-700">
                                Log in or create an account with email <span class="font-semibold">{{ order.email }}</span> to link your orders to your personal profile.
                            </p>
                        </div>
                        <div class="flex gap-3 shrink-0">
                            <Link 
                                href="/login" 
                                class="rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:from-primary-700 hover:to-primary-600 hover:shadow-xl"
                            >
                                Log In
                            </Link>
                            <Link 
                                href="/register" 
                                class="rounded-xl border-2 border-primary-300 bg-white px-6 py-3 text-sm font-semibold text-primary-700 transition-all hover:bg-primary-50 hover:shadow-md"
                            >
                                Register
                            </Link>
                        </div>
                    </div>
                </div>

                <div 
                    v-else 
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg shadow-gray-100"
                >
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Logged in as {{ (page.props.auth as any).user?.name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">You can view all your orders anytime in your account dashboard.</p>
                            </div>
                        </div>
                        <Link 
                            href="/dashboard/orders" 
                            class="rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800 hover:shadow-lg"
                        >
                            My Account Orders
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty Initial State -->
            <div 
                v-else-if="!searchedQuery" 
                class="rounded-2xl border-2 border-dashed border-gray-300 bg-white p-12 text-center shadow-sm"
            >
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-700">Ready to track your package?</p>
                <p class="text-sm text-gray-500 mt-1">Enter your Order Number or phone number in the search bar above.</p>
            </div>
        </div>
    </div>
</template>