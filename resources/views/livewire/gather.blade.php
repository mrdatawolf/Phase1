@if($this->isAllowed())
    <div id="level{{ $resourceId }}" class="text-center p-4 overflow-hidden shadow-xl {{($canEnable || $enabled) ? 'bg-blue-200' : 'bg-gray-900'}} rounded-b-lg ">
        <div class="debug">
            <div class="hidden p-2 grid grid-cols-6 gap-2 sm:font-mono text-xs">
                <span class="rounded-full {{ ($canEnable) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Can Enable</span>
                <span class="rounded-full {{ ($enabled) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Enabled</span>
                <span class="rounded-full {{ ($automated) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Automated</span>
                <span class="rounded-full {{ ($canImprove) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">Can Improve</span>
            </div>
        </div>
        <div class="title grid grid-cols-2">
            <div class="align-left">
                <h2 class="p-1 font-semibold text-xl text-left {{($canEnable || $enabled) ? 'text-black' : 'text-white'}} leading-tight">{{ $resourceName }}</h2>
            </div>
            @if(! $automated && $enabled)
                <div wire:key="automate_{{ $resourceId }}" wire:click="automate" class="align-right"><span class="material-icons md-18 {{ ($canAutomate) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="automate">autorenew</span></div>
            @else
                <div></div>
            @endif
        </div>
        <div class="body p-2 grid grid-cols-6 gap-2 text-white font-bold">
            @if(! $automated && $enabled)
                <div wire:key="gather_{{ $resourceId }}" wire:click="gather" class="gather {{($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} rounded-full">Gather <br><span class="text-xs w-10">{{ $gatherAmount }}</span></div>
            @else
                <div></div>
            @endif
            @if($enabled)
                <div wire:key="improve_{{ $resourceId }}" wire:click="improve" class="gather_improve text-center {{($enabled && $canImprove) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700'}} rounded-full">Improve<br><span class="text-xs w-10">{{ $improveResourceRequired }}</span></div>
            @else
                <div></div>
            @endif
            @if(! $enabled)
                <div wire:key="enable_{{ $resourceId }}" wire:click="enable" class="{{ ($canEnable) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full">Enable</div>
            @else
                <div></div>
            @endif
            <div class="col-span-3 row-span-3">
                @if(! $enabled)
                    <div class="header text-right">To Enable</div>
                    <div class="body text-right">
                        @foreach($resourcesNeededToEnable as $id => $total)
                            <div class="text-sm">{{ $this->resources->where('id',$id)->pluck('name')->first() }} - {{ $total }}</div>
                        @endforeach
                    </div>
                @else
                    <div class="header text-right">To Automate</div>
                    <div class="body text-right">
                        @foreach($resourcesNeededToAutomate as $id => $total)
                            <div class="text-sm">{{ $this->resources->where('id',$id)->pluck('name')->first() }} - {{ $total }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

