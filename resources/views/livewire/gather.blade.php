@if($allowed)
    <div id="level{{ $resourceId }}" class="text-center p-4 overflow-hidden shadow-xl {{($canEnable || $enabled) ? 'bg-blue-200' : 'bg-gray-900'}} rounded-b-lg ">
        <div class="title">
            <h2 class="p-1 font-semibold text-xl text-left {{($canEnable || $enabled) ? 'text-black' : 'text-white'}} leading-tight">{{ $resourceName }}</h2>
            <div class="p-2 grid grid-cols-6 gap-2 sm:font-mono text-xs">
                <span class="rounded-full {{ ($allowed) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Allowed</span>
                <span class="rounded-full {{ ($canEnable) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Can Enable</span>
                <span class="rounded-full {{ ($enabled) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Enabled</span>
                <span class="rounded-full {{ ($automated) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Automated</span>
                <span class="rounded-full {{ ($canImprove) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Can Improve</span>
            </div>
        </div>
        <div class="body p-2 grid grid-cols-6 gap-2  text-white font-bold">
            @if(! $automated)
                <div wire:click="gather" class="gather {{($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} rounded-full">Gather <span class="gather_amount_to_add">{{ $gatherAmount }}</span></div>
            @endif
            <div wire:click="improve" class="gather_improve text-center {{($enabled && $canImprove) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} rounded-full">Improve</div>
            @if(! $enabled)
                <div wire:click="enable" class="{{ ($canEnable) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full">Enable</div>
            @endif
            @if(! $automated)
                <div wire:click="automate" class="{{ ($canAutomate) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full">Automate</div>
            @endif
        </div>
    </div>
@endif

