<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Order</h1>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Product Catalog --}}
            <div class="lg:col-span-2">

                {{-- Category Filter --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    <button
                        wire:click="$set('selectedCategory', null)"
                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors
                            {{ $selectedCategory === null ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}"
                    >
                        All
                    </button>
                    @foreach ($this->categories as $category)
                        <button
                            wire:click="$set('selectedCategory', {{ $category->id }})"
                            class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors
                                {{ $selectedCategory === $category->id ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}"
                        >
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                {{-- Product Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @forelse ($this->products as $product)
                        @php $cartQty = $this->cart[(string) $product->id] ?? 0; @endphp
                        <div class="bg-white rounded-lg border p-4 flex flex-col gap-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                                @if ($product->description)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-lg font-bold text-gray-900">${{ $product->price }}</span>
                                <span class="text-xs text-gray-400">Stock: {{ $product->stock }}</span>
                            </div>
                            @if ($product->stock > 0)
                                @if ($cartQty > 0)
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="flex items-center gap-2">
                                            <button
                                                wire:click="decrementCart({{ $product->id }})"
                                                class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 font-bold"
                                            >−</button>
                                            <span class="w-6 text-center font-medium">{{ $cartQty }}</span>
                                            <button
                                                wire:click="addToCart({{ $product->id }})"
                                                class="w-7 h-7 rounded-full bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold"
                                            >+</button>
                                        </div>
                                        <span class="text-xs text-indigo-600 font-medium">In cart</span>
                                    </div>
                                @else
                                    <button
                                        wire:click="addToCart({{ $product->id }})"
                                        class="mt-1 w-full py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors"
                                    >
                                        Add to Cart
                                    </button>
                                @endif
                            @else
                                <span class="mt-1 text-center text-sm text-gray-400">Out of stock</span>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-400">
                            No products found.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Cart + Form --}}
            <div class="flex flex-col gap-4">

                {{-- Cart --}}
                <div class="bg-white rounded-lg border p-4">
                    <h2 class="font-semibold text-gray-900 mb-3">Cart</h2>

                    @error('cart')
                        <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                    @enderror

                    @forelse ($this->cartItems as $item)
                        <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <button
                                        wire:click="decrementCart({{ $item['product']->id }})"
                                        class="w-5 h-5 rounded bg-gray-100 hover:bg-gray-200 text-xs flex items-center justify-center"
                                    >−</button>
                                    <span class="text-sm">{{ $item['quantity'] }}</span>
                                    <button
                                        wire:click="addToCart({{ $item['product']->id }})"
                                        class="w-5 h-5 rounded bg-gray-100 hover:bg-gray-200 text-xs flex items-center justify-center"
                                    >+</button>
                                </div>
                            </div>
                            <div class="ml-3 flex flex-col items-end gap-1">
                                <span class="text-sm font-semibold">${{ number_format($item['subtotal'], 2) }}</span>
                                <button
                                    wire:click="removeFromCart({{ $item['product']->id }})"
                                    class="text-xs text-red-500 hover:text-red-700"
                                >Remove</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Your cart is empty.</p>
                    @endforelse

                    @if ($this->cartItems->isNotEmpty())
                        <div class="flex justify-between font-semibold pt-3 mt-1">
                            <span>Total</span>
                            <span>${{ number_format($this->cartItems->sum(fn($i) => $i['subtotal']), 2) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Customer Info --}}
                <div class="bg-white rounded-lg border p-4">
                    <h2 class="font-semibold text-gray-900 mb-3">Customer Info</h2>
                    <form wire:submit="submit" class="flex flex-col gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input
                                type="text"
                                wire:model="customerName"
                                class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('customerName') border-red-400 @enderror"
                                placeholder="John Doe"
                            >
                            @error('customerName')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input
                                type="email"
                                wire:model="customerEmail"
                                class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('customerEmail') border-red-400 @enderror"
                                placeholder="john@example.com"
                            >
                            @error('customerEmail')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium rounded-md transition-colors"
                        >
                            <span wire:loading.remove>Place Order</span>
                            <span wire:loading>Placing...</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
