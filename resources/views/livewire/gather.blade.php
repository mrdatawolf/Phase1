@if($allowed)
    <div class="{{($canEnable) ? '' : 'offline '}}levels bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <h1>{{ $resourceName }}: <span class="{{ ($allowed) ? 'green' : 'red' }}">Allowed</span> _
            <span class="{{ ($canEnable) ? 'green' : 'red' }}">Can Enable</span> _
            <span class="{{ ($enabled) ? 'green' : 'red' }}">Enabled</span>
        </h1>
        <div class="gather">
            <div wire:click="gather" class="{{($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} text-white font-bold py-2 px-4 rounded-full">Gather <span class="gather_amount_to_add">{{ $gatherAmount }}</span></div>
        </div>
        <div class="gather_improve">
            <div wire:click="improveGather" class="improveGather {{($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} text-white font-bold py-2 px-4 rounded-full">Improve</div>
        </div>
        <div class="activateType">
            @if($canEnable && ! $enabled)
                <div wire:click="enable" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Enable</div>
            @endif
        </div>
    </div>
@endif

