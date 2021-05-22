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
                <div class="bg-blue-100">
                    Change Log for 05/21/21:
                    Gather:
                    <ol>
                        <li>
                            Recourse cards had their ui tweaked.
                        </li>
                        <li>
                            The gather rate, worker count, tool count and foreman count are now stored.
                        </li>
                        <li>
                            it now remembers if a resource is automated and or enabled.
                        </li>
                        <li>
                            You can change a resource into gold.
                        </li>
                        <li>
                            A basic exchange rate has been applied.
                        </li>
                    </ol>
                </div>
                <div class="bg-blue-100">
                    Alpha tester focus:
                    <ol>
                        <li>
                            Everything as I have finished the intial banking setup and have not stress tested any of the other parts.
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
