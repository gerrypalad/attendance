<div x-data="{
        show: false,
        message: '',
        type: 'success'
     }"
     x-init="
        @if(session()->has('success'))
            message = '{{ session('success') }}';
            type = 'success';
            show = true;
        @elseif(session()->has('error'))
            message = '{{ session('error') }}';
            type = 'error';
            show = true;
        @endif
        if(show) { setTimeout(() => show = false, 4000); }
     "
     x-show="show"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
     style="display: none;">

    <div class="p-4">
        <div class="flex items-start">
            <!-- Icon Dynamic Container -->
            <div class="flex-shrink-0">
                <!-- Success Icon -->
                <template x-if="type === 'success'">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </template>
                <!-- Error Icon -->
                <template x-if="type === 'error'">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </template>
            </div>

            <!-- Message Text -->
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p x-text="message" class="text-sm font-medium text-gray-900"></p>
            </div>

            <!-- Close Button -->
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
