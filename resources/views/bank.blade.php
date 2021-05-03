<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phase1 Game') }}
        </h2>
    </x-slot>
    <div class="p-4 grid grid-cols-12 main-body">
        <div class="col-span-12">
            @livewire('banking')
        </div>
        <div class="col-span-12">
            <div class="p-4 grid sm:grid-cols-1 xl:grid-cols-4 gap-4">
                what should be on the bank page?
            </div>
        </div>
    </div>
</x-app-layout>
