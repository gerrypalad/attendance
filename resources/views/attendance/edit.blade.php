<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 lead-tight">
            Modify Log Entry: {{ $attendance->user->name }} ({{ $attendance->work_date->format('M d, Y') }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg border border-slate-200">
                <form method="POST" action="{{ route('attendance.update', $attendance->id) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time In</label>
                            <input type="time" name="time_in" value="{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '' }}" class="w-full rounded border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Break Out</label>
                            <input type="time" name="break_out" value="{{ $attendance->break_out ? \Carbon\Carbon::parse($attendance->break_out)->format('H:i') : '' }}" class="w-full rounded border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Break In</label>
                            <input type="time" name="break_in" value="{{ $attendance->break_in ? \Carbon\Carbon::parse($attendance->break_in)->format('H:i') : '' }}" class="w-full rounded border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time Out</label>
                            <input type="time" name="time_out" value="{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '' }}" class="w-full rounded border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Log Modification Audit Remarks</label>
                        <textarea name="remarks" rows="3" required class="w-full rounded border-gray-300 shadow-sm">{{ $attendance->remarks }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 border-t pt-4">
                        <a href="{{ route('attendance.records') }}" class="px-4 py-2 border rounded text-sm text-gray-600 hover:bg-gray-50 font-semibold">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold shadow">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
