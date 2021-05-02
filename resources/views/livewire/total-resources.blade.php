<div wire:poll.keep-alive="runTimedChecks" class="p-4 gap-4 grid grid-cols-12 text-xs font-mono ">
    @foreach($resources as $resource)
    <div class="rounded text-xs bg-gray-300"><label>{{ ucfirst($resource->name) }}:</label><text>{{ $totals[$resource->id] }}</text></div>
    @endforeach
</div>
