@if($allowed)
    <div class="{{($canEnable) ? '' : 'offline '}}levels bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <h1>{{ $resourceName }}: <span class="{{ ($allowed) ? 'green' : 'red' }}">Allowed</span> _
            <span class="{{ ($canEnable) ? 'green' : 'red' }}">Can Enable</span> _
            <span class="{{ ($enabled) ? 'green' : 'red' }}">Enabled</span>
        </h1>
        <div class="gather">
            <button wire:click="gather" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Gather <span class="gather_amount_to_add">{{ $gatherAmount }}</span></button>
        </div>
        <div class="gather_improve">
            <button wire:click="improveGather" class="improveGather {{($allowed) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} text-white font-bold py-2 px-4 rounded-full">Improve</button>
        </div>
        <div class="activateType">
            @if($canEnable && ! $enabled)
                <button wire:click="enable" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Enable</button>
            @endif
        </div>
    </div>
@endif

