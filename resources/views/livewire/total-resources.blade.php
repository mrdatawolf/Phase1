<div wire:poll.keep-alive="runTimedChecks" class="p-4 gap-4 grid grid-cols-12 text-xs font-mono ">
    @foreach([1 => 'stone',2 => 'water',3 => 'wood',4 => 'grain',5 => 'livestock',6 => 'clay',7 => 'silver',8 => 'gold',9 => 'copper',10 => 'tin',11 => 'iron',12 => 'aluminum'] as $id => $name)
    <div class="rounded text-xs bg-gray-300"><label>{{ ucfirst($name) }}:</label><text>{{ $totals[$id] }} / {{ $resourceIncrementAmount[$id] }} / {{ $resourcesNeededToImprove[$id] }}</text></div>
    @endforeach
</div>
