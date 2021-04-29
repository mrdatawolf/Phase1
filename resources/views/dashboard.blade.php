<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phase1 Game') }}
        </h2>
    </x-slot>

    <style>
        .offline {
            background-color: #2d3748;
        }
        .totals {
            font-family: sans-serif, Tahoma;
            font-size: 8pt;
        }

        .total_amounts, .levels  {
            padding: .25em;
            margin: .25em;
            border: 1px solid black;

            min-width: 8em;
        }
        .levels {
            min-width: 20em;
            min-height: 10em;
        }
        .total_amounts {
            float: left;
        }
        .gather {
            float: right;
        }
        .gather_improve {
            float: left;
            clear: both;
        }
        .red {
            background-color: darkred;
        }
        .green {
            background-color: green;
        }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @livewire('total-resources')
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"  id="level1">
            @livewire('gather', ['resourceId' => 1, 'resourceName' => 'Clay', 'enabled' => 1, 'allowed' => 1, 'canEnable' => 1])
        </div>
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"  id="level2">
            @livewire('gather', ['resourceId' => 2, 'resourceName' => 'Water', 'enabled' => 0, 'allowed' => 1, 'canEnable' => 0])
        </div>
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"  id="level2">
            @livewire('gather', ['id' => 3, 'name' => 'Wood', 'enabled' => 0, 'allowed' => 0, 'canEnable' => 0])
        </div>
    </div>
</x-app-layout>
