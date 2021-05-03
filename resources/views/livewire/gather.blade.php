@if($this->isAllowed())
    <div id="level{{ $resourceId }}" class="text-center p-4 overflow-hidden shadow-xl {{($canEnable || $enabled) ? 'bg-blue-200' : 'bg-gray-900'}} rounded-b-lg">
        <div class="title grid grid-cols-12">
            <div class="col-span-6">
                <h2 class="p-1 font-semibold text-xl text-left {{($canEnable || $enabled) ? 'text-black' : 'text-white'}} leading-tight">{{ $resourceName }} @if($enabled)
                        <span title="Amount gathered">+ {{ $gatherAmount }}</span>@endif</h2>
            </div>
            <div class="col-span-6">
                @if(! $enabled)
                    <div wire:key="enable_{{ $resourceId }}" wire:click="enable" class="">
                        <span class="material-icons {{ ($canEnable) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="enable">lightbulb</span>
                    </div>
                @else
                    @if(! $automated)
                        <div wire:key="automate_{{ $resourceId }}" wire:click="automate">
                            <span class="material-icons {{ ($canAutomate) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="automate">autorenew</span>
                        </div>
                    @else
                        <div>&nbsp;</div>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-span-12 debug">
            @if($debug)
                <div class="p-2 grid grid-cols-4 gap-2 sm:font-mono text-xs">
                    <div class="rounded-full {{ ($canEnable) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can Enable
                    </div>
                    <div class="rounded-full {{ ($enabled) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Enabled
                    </div>
                    <div class="rounded-full {{ ($automated) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Automated
                    </div>
                    <div class="rounded-full {{ ($canImprove) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can Improve
                    </div>
                </div>
            @else
                <div>&nbsp;</div>
            @endif
        </div>
        <div class="col-span-12">
            <div class="grid grid-cols-6">
                <div class="col-span-4 row-span-5">
                    <div class="grid grid-cols-2">
                        @if(! $automated && $enabled)
                            <div wire:key="gather_{{ $resourceId }}" wire:click="gather" class="align-left">
                                <span class="material-icons {{ ($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="gather">trending_up</span>
                            </div>
                        @else
                            <div>&nbsp;</div>
                        @endif
                        @if($enabled)
                            <div wire:key="improve_{{ $resourceId }}" wire:click="improve" class="align-right">
                                <span class="material-icons {{ ($enabled && $canImprove) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="improve">upgrade</span>{{ $improveResourceRequired }}
                            </div>
                        @else
                            <div>&nbsp;</div>
                        @endif
                    </div>
                    <div>
                        @if($enabled)
                            <div class="p-2 grid grid-cols-4 gap-2 sm:font-mono text-xs">
                                <div>
                                    <span class="material-icons {{ ($canSell) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="sell">point_of_sale</span>
                                </div>
                                <div>
                                    <span class="material-icons {{ ($canSendToStorage) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="My Storage">local_shipping</span>
                                </div>
                                <div>
                                    <span class="material-icons {{ ($canSendToTeamStorage) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="Team Storage">local_post_office</span>
                                </div>
                            </div>
                        @else
                            <div>&nbsp;</div>
                        @endif
                    </div>
                </div>
                <div class="col-span-2 row-span-5">
                    <div>
                        @if(! $enabled)
                            <div class="header text-right text-white"><span class="material-icons bg-gray-500 hover:bg-gray-700 rounded-full" title="enable cost">lightbulb</span> Cost</div>
                            <div class="body text-right text-white">
                                @foreach($resourcesNeededToEnable as $id => $total)
                                    <div class="text-sm font-bold">{{ $this->resources->where('id',$id)->pluck('name')->first() }}
                                        - {{ $total }}</div>
                                @endforeach
                            </div>
                        @else
                            @if(! $automated)
                                <div class="header text-right"><span class="material-icons bg-gray-500 hover:bg-gray-700 rounded-full" title="automate">autorenew</span> Cost</div>
                                <div class="body text-right">
                                    @foreach($resourcesNeededToAutomate as $id => $total)
                                        <div class="text-sm font-bold">{{ $this->resources->where('id',$id)->pluck('name')->first() }}
                                            - {{ $total }}</div>
                                    @endforeach
                                </div>
                            @else
                                <div>&nbsp;</div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
