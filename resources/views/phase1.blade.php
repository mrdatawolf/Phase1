<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phase1 Game') }}
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
                @livewire('gather', ['resourceId' => 1, 'resourceName' => 'Stone', 'enabled' => true, 'allowed' => true, 'canEnable' => true])
                @livewire('gather', ['resourceId' => 2, 'resourceName' => 'Water', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 3, 'resourceName' => 'Wood', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 4, 'resourceName' => 'Grain', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 5, 'resourceName' => 'Livestock', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 6, 'resourceName' => 'Clay', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 7, 'resourceName' => 'Silver', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 8, 'resourceName' => 'Gold', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 9, 'resourceName' => 'Copper', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 10, 'resourceName' => 'Tin', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 11, 'resourceName' => 'Iron', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
                @livewire('gather', ['resourceId' => 12, 'resourceName' => 'Aluminum', 'enabled' => false, 'allowed' => true, 'canEnable' => false])
            </div>
        </div>
    </div>
</x-app-layout>
