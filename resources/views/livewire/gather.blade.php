@if($this->isAllowed())
    <div id="level{{ $resourceId }}" class="text-center p-4 overflow-hidden shadow-xl {{($allowEnable || $enabled) ? 'bg-blue-200' : 'bg-gray-900'}} rounded-b-lg">
        <div class="title grid grid-cols-12">
            <div class="col-span-6">
                <h2 class="p-1 font-semibold text-xl text-left {{($allowEnable || $enabled) ? 'text-black' : 'text-white'}} leading-tight">{{ $resourceName }}</h2>
            </div>
            <div class="col-span-6">
                @if(! $enabled)
                    <div wire:key="enable_{{ $resourceId }}" wire:click="enable">
                        <span class="material-icons {{ ($allowEnable) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="Enable at a cost of:
                        @foreach($resourcesNeededToEnable as $id => $total)
                            {{ $this->resources->where('id',$id)->pluck('name')->first() }}- {{ $total }},
                        @endforeach
                        ">lightbulb</span>
                    </div>
                @else
                    @if(! $automated)
                        <div wire:key="automate_{{ $resourceId }}" wire:click="automate">
                            <span class="material-icons {{ ($allowAutomate) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="Automate at a cost of:
                            @foreach($resourcesNeededToAutomate as $id => $total)
                                {{ $this->resources->where('id',$id)->pluck('name')->first() }} - {{ $total }},
                            @endforeach
                            ">autorenew</span>
                        </div>
                    @else
                        <div>&nbsp;</div>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-span-12 debug">
            @if($debug)
                <div class="p-2 grid grid-cols-7 gap-2 sm:font-mono text-xs">
                    <div class="rounded-full {{ ($allowEnable) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can <span class="material-icons">lightbulb</span>
                    </div>
                    <div class="rounded-full {{ ($enabled) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        <span class="material-icons">lightbulb</span>
                    </div>
                    <div class="rounded-full {{ ($allowAutomate) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can <span class="material-icons">autorenew</span>
                    </div>
                    <div class="rounded-full {{ ($automated) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        <span class="material-icons">autorenew</span>
                    </div>
                    <div class="rounded-full {{ ($allowAddWorker) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can <span class="material-icons">group</span>
                    </div>
                    <div class="rounded-full {{ ($allowAddTool) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can <span class="material-icons">construction</span>
                    </div>
                    <div class="rounded-full {{ ($allowAddForeman) ? 'bg-green-500 hover:bg-green-300' : 'bg-red-500 hover:bg-red-300' }}">
                        Can <span class="material-icons">person</span>
                    </div>
                </div>
            @else
                <div>&nbsp;</div>
            @endif
        </div>
        <div class="col-span-12">
            <div class="grid grid-cols-6">
                <div class="col-span-4 grid grid-cols-3">
                    @if(! $automated && $enabled)
                        <div>&nbsp;</div>
                        <div wire:key="gather_{{ $resourceId }}" wire:click="gather" class="grid gird-cols-1 align-left {{ ($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full">
                            <div><span class="material-icons" title="{{__('Gather ' . $totalGatherAmount . ' ' .  $resourceName)}}">add</span>
                            </div><div><span title="Gather Amount">{{ $totalGatherAmount }}</span></div>
                        </div>
                        <div>&nbsp;</div>
                    @elseif($automated)
                        <div>&nbsp;</div>
                        <span title="Gather Amount"> {{ $totalGatherAmount }}</span>
                        <div>&nbsp;</div>
                    @else
                        <div class="col-span-3">&nbsp;</div>
                    @endif
                </div>
                <div class="col-span-2 row-span-5">
                    <div>
                        &nbsp;
                    </div>
                </div>
                <div class="col-span-4">
                    <div class="p-2 grid grid-cols-9">
                        @if($enabled)
                            <div class="align-right col-span-3 grid grid-cols-2">
                                <div><span title="{{ __('total workers') }}">{{ $totalWorkers }}</span></div>
                                <div>
                                    <span wire:key="improve_{{ $resourceId }}" wire:click="addWorker" class="material-icons {{ ($allowAddWorker) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="{{ __('Add a worker') }}">group</span>
                                </div>
                                <div class="col-span-2">
                                    <span title="{{ __($resourceName . ' required to add another worker') }}">{{ $resourcesRequiredToAddWorker }}</span>
                                </div>
                            </div>
                            <div class="align-right col-span-3 grid grid-cols-2">
                                <div><span title="{{ __('total tools') }}">{{ $totalTools }}</span></div>
                                <div>
                                    <span wire:key="add_tools_{{ $resourceId }}" wire:click="addTool" class="material-icons {{ ($allowAddTool) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="{{ __('Add a tool') }}">construction</span>
                                </div>
                                <div class="col-span-2">
                                    <span title="{{ __($resourceName . ' required to add another tool') }}">{{ $resourcesRequiredToAddTool }}</span>
                                </div>
                            </div>
                            <div class="align-right col-span-3 grid grid-cols-2">
                                <div><span title="{{ __('total foremen') }}">{{ $totalForemen }}</span></div>
                                <div>
                                    <span wire:key="add_foreman_{{ $resourceId }}" wire:click="addForeman" class="material-icons {{ ($allowAddForeman) ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="{{ __('Add a foreman') }}">person</span>
                                </div>
                                <div class="col-span-2">
                                    <span title="{{ __($resourceName . ' required to add another foreman') }}">{{ $resourcesRequiredToAddForeman }}</span>
                                </div>
                            </div>
                        @else
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                        @endif
                    </div>
                </div>
                <div class="col-span-4">
                    @if($enabled)
                        <div class="p-2 grid grid-cols-5 gap-2 sm:font-mono text-xs">
                            <div>&nbsp;</div>
                            <div>
                                <span wire:click="sellRequest" class="material-icons {{ ($enabled) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="sell">point_of_sale</span>
                            </div>
                            <div>
                                <span class="material-icons {{ ($allowSendToStorage) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="My Storage">local_shipping</span>
                            </div>
                            <div>
                                <span class="material-icons {{ ($allowSendToTeamStorage) ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 hover:bg-gray-700' }} rounded-full" title="Team Storage">local_post_office</span>
                            </div>
                            <div>&nbsp;</div>
                        </div>
                    @else
                        <div>&nbsp;</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
