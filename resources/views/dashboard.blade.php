<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phase1 Game') }}
        </h2>
    </x-slot>

    <style>
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
        .total_amounts {
            float: left;
        }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="totals">
                    <div class="total_amounts"><label>Clay:</label><text class="totalClay">0</text></div>
                    <div class="total_amounts"><label>Water:</label><text class="totalWater">0</text></div>
                    <div class="total_amounts"><label>Wood:</label><text class="totalWood">0</text></div>
                    <div class="total_amounts"><label>Wheat:</label><text class="totalWheat">0</text></div>
                    <div class="total_amounts"><label>Sheep:</label><text class="totalSheep">0</text></div>
                    <div class="total_amounts"><label>Stone:</label><text class="totalStone">0</text></div>
                    <div class="total_amounts"><label>Silver:</label><text class="totalSilver">0</text></div>
                    <div class="total_amounts"><label>Gold:</label><text class="totalGold">0</text></div>
                    <div class="total_amounts"><label>Copper:</label><text class="totalCopper">0</text></div>
                    <div class="total_amounts"><label>Tin:</label><text class="totalTin">0</text></div>
                    <div class="total_amounts"><label>Bronze:</label><text class="totalBronze">0</text></div>
                    <div class="total_amounts"><label>Iron:</label><text class="totalStone">0</text></div>
                    <div class="total_amounts"><label>Aluminum:</label><text class="totalAluminum">0</text></div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
               <div class="levels" id="level1">
                   <button class="improve bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" id="clay_plus">Gather Clay</button>
                   <text class="clayPerClick">1</text>
               </div>
                <div class="levels" id="level2">
                    <button class="improve bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full" id="clay_plus" disabled>Gather Water</button>
                    <text class="waterPerClick">1</text>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
