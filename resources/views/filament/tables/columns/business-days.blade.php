@php
    $days = [];
    $dayNames = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    
    foreach ($dayNames as $index => $day) {
        if ($record->$day) {
            $days[] = $dayLabels[$index];
        }
    }
@endphp

<div class="flex flex-wrap gap-2">
    @forelse ($days as $day)
        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
            {{ $day }}
        </span>
    @empty
        <span class="text-gray-400">No operating days</span>
    @endforelse
</div>
