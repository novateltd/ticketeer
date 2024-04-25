<x-layouts.app>
    <div class="container mx-auto my-8">

        <h1 class="text-xl font-bold">Sales to date</h1>

        <table class="w-full my-8">
            <thead>
                <tr class="border-b">
                    <th class="text-left">Date</th>
                    <th class="text-left">Name (if given)</th>
                    <th class="text-left">email (if given)</th>
                    <th class="text-left">Promo</th>
                    <th class="text-center">Adults</th>
                    <th class="text-center">Juniors</th>
                    <th class="text-right">Sale Value</th>
                </tr>
                <tr class="">
                    <th class="text-left"></th>
                    <th class="text-left"></th>
                    <th class="text-left"></th>
                    <th class="text-left"></th>
                    <th class="p-2 text-center border-b-2 border-blue-600">{{ $total['adults'] }}</th>
                    <th class="p-2 text-center border-b-2 border-blue-600">{{ $total['juniors'] }}</th>
                    <th class="p-2 text-right border-b-2 border-blue-600">&pound;{{ number_format($total['sales']/100,2) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr class="{{ $loop->even ? 'bg-zinc-100' : '' }}">
                    <td class="p-2">{{ $transaction->created_at->format('d/m/y H:i') }}</td>
                    <td class="p-2">{{ $transaction->name }}</td>
                    <td class="p-2">{{ $transaction->email }}</td>
                    <td class="p-2">{{ strtoupper($transaction->promo) }}</td>
                    <td class="p-2 text-center">{{ $transaction->adult_tickets }}</td>
                    <td class="p-2 text-center">{{ $transaction->junior_tickets }}</td>
                    <td class="p-2 text-right">&pound;{{ number_format($transaction->cost/100,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-layouts.app>