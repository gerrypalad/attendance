@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<div class="space-y-8">

    <!-- Heading -->

    <div>
        <h1 class="text-3xl font-bold">
            Dashboard
        </h1>

        <p class="text-gray-500">
            Welcome back! Here's what's happening today.
        </p>
    </div>

    <x-attendance-console :attendance="$attendance" :status="$status" />


    <!-- Stats -->

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">

        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Users
                    </p>

                    <h2 class="text-3xl font-bold">
                        1,245
                    </h2>
                </div>

                <i class="bi bi-people text-4xl text-indigo-500"></i>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Orders
                    </p>

                    <h2 class="text-3xl font-bold">
                        382
                    </h2>
                </div>

                <i class="bi bi-cart-check text-4xl text-green-500"></i>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Revenue
                    </p>

                    <h2 class="text-3xl font-bold">
                        $14,250
                    </h2>
                </div>

                <i class="bi bi-currency-dollar text-4xl text-yellow-500"></i>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Growth
                    </p>

                    <h2 class="text-3xl font-bold">
                        +18%
                    </h2>
                </div>

                <i class="bi bi-graph-up-arrow text-4xl text-red-500"></i>

            </div>

        </div>

    </div>

    <!-- Content -->

    <div class="grid lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">

            <h3 class="text-xl font-semibold mb-4">
                Recent Activity
            </h3>

            <div class="divide-y">

                <div class="py-4 flex justify-between">
                    <span>John created an order</span>
                    <span class="text-gray-500">2 mins ago</span>
                </div>

                <div class="py-4 flex justify-between">
                    <span>Sarah updated a product</span>
                    <span class="text-gray-500">10 mins ago</span>
                </div>

                <div class="py-4 flex justify-between">
                    <span>Michael registered</span>
                    <span class="text-gray-500">20 mins ago</span>
                </div>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-xl font-semibold mb-4">
                Quick Actions
            </h3>

            <div class="space-y-3">

                <button class="w-full bg-indigo-600 text-white rounded-lg py-3 hover:bg-indigo-700">
                    <i class="bi bi-plus-circle"></i>
                    New Product
                </button>

                <button class="w-full bg-green-600 text-white rounded-lg py-3 hover:bg-green-700">
                    <i class="bi bi-person-plus"></i>
                    Add User
                </button>

                <button class="w-full bg-yellow-500 text-white rounded-lg py-3 hover:bg-yellow-600">
                    <i class="bi bi-download"></i>
                    Export Report
                </button>

            </div>

        </div>

    </div>

</div>

<!-- Custom Confirmation Modal Wrapper (Triggers Centering via Vanilla JS Classes) -->
<div id="custom-confirm-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">

    <!-- 1. BACKDROP BACKGROUND: Spans the full screen, uses native backdrop-filter styles directly for guaranteed blur rendering -->
    <div class="fixed inset-0 bg-slate-900/40"
         style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
         aria-hidden="true"
         onclick="closeConfirmationModal()"></div>

    <!-- 2. SCROLLABLE INNER LAYOUT CONTAINER: Handles centering of the card view -->
    <div class="flex min-h-screen items-center justify-center p-4 text-center">

        <!-- 3. MODAL DIALOG CARD: Locked at a clean, standard dashboard warning alert card width -->
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl border border-slate-200 transition-all sm:my-8 sm:w-full sm:max-w-md z-10">
            <!-- Core Content Block -->
            <div class="bg-white p-6">
                <div class="flex items-start gap-4">

                    <!-- Icon Accent Container -->
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-blue-50 border border-blue-100">
                        <i class="bi bi-question-circle text-blue-600 text-lg"></i>
                    </div>

                    <!-- Typography Headings -->
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-slate-900 tracking-tight" id="modal-title">
                            Confirm Action
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm leading-relaxed text-slate-500" id="modal-description">
                                Are you sure you want to proceed with this entry?
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Action Interactive Buttons Footer Row -->
            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button"
                        onclick="closeConfirmationModal()"
                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 focus:outline-none transition">
                    Cancel
                </button>
                <button type="button"
                    id="modal-confirm-btn"
                    class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none transition">
                    Confirm
                </button>
            </div>

        </div>

    </div>
</div>


@endsection
