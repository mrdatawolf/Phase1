<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phase1 Game') }}
        </h2>
    </x-slot>
    <div class="p-4 grid grid-cols-12">
        <div class="col-span-12">
            @livewire('total-resources')
        </div>
        <div class="col-span-12">
            <div class="p-4 grid grid-cols-3 gap-4">
                @livewire('gather', ['resourceId' => 1, 'resourceName' => 'Clay', 'enabled' => 1, 'allowed' => 1, 'canEnable' => 1])
                @livewire('gather', ['resourceId' => 2, 'resourceName' => 'Water', 'enabled' => 0, 'allowed' => 1, 'canEnable' => 0])
                @livewire('gather', ['resourceId' => 3, 'resourceName' => 'Wood', 'enabled' => 0, 'allowed' => 1, 'canEnable' => 0])
            </div>
        </div>
    </div>
</x-app-layout>
