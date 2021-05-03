<div wire:poll.keep-alive.5000ms="runTimedChecks" class="p-4 gap-4 grid grid-cols-12 text-xs font-mono">
    <div class="col-span-12">Onsite Storage</div>
    @foreach($resources as $resource)
    <div class="rounded bg-gray-300 grid grid-cols-1 min-w-20">
        <div><label>{{ ucfirst($resource->name) }}:</label></div>
        <div>{{ $totals[$resource->id] }}</div>
    </div>
    @endforeach
</div>
