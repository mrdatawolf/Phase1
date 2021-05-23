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
            Known Issues
        </div>
        <div class="col-span-3 p-4 bg-red-100">
           5/23/21:
            <ul class="list-decimal">
                <li>
                    Storage is not implemented
                </li>
                <li>
                    Automate does not show the cost to automate.
                </li>
                <li>
                    Automate does not work offline... this may become intended behaviour.
                </li>
            </ul>
        </div>
        <div class="col-span-12">
            Suggestions for current alpha testers:
        </div>
        <div class="col-span-3 p-4 bg-yellow-100">
            <ul class="list-decimal">
                <li>
                    Everything... as I have finished the intial banking setup and have not stress tested any of the other parts.
                </li>
            </ul>
        </div>
        <div class="col-span-12">
            Change Logs
        </div>
        <div class="col-span-12">
            <div class="p-4 grid sm:grid-cols-1 xl:grid-cols-4 gap-4">
                <div class="p-4 bg-blue-100">
                    05/23/21 - Gather:
                    <ul class="list-decimal">
                        <li>
                            Recourse cards had their ui tweaked.
                        </li>
                        <li>
                            Enable and automate now apply the cost to the stored resource amounts.
                        </li>
                        <li>
                            The amount in bank is now prettier.
                        </li>
                    </ul>
                </div>
                <div class="p-4 bg-blue-100">
                    05/21/21 - Gather:
                    <ul class="list-decimal">
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
