<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gather Game') }}
        </h2>
    </x-slot>
    <div class="p-4 grid grid-cols-12 main-body">
        <div class="col-span-12">
            @livewire('total-resources')
        </div>
        <div class="col-span-12">
            @livewire('banking')
        </div>
        <div class="col-span-12">
            <div class="p-4 grid sm:grid-cols-1 xl:grid-cols-4 gap-4">
                @livewire('gather', ['resourceId' => 1, 'resourceName' => 'Stone', 'allowed' => true, 'canEnable' => true])
                @livewire('gather', ['resourceId' => 2, 'resourceName' => 'Water', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 3, 'resourceName' => 'Wood', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 4, 'resourceName' => 'Grain', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 5, 'resourceName' => 'Livestock', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 6, 'resourceName' => 'Clay', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 7, 'resourceName' => 'Silver', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 8, 'resourceName' => 'Gold', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 9, 'resourceName' => 'Copper', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 10, 'resourceName' => 'Tin', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 11, 'resourceName' => 'Iron', 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 12, 'resourceName' => 'Aluminum', 'allowed' => true, 'canEnable' => false])
            </div>
        </div>
    </div>
</x-app-layout>
