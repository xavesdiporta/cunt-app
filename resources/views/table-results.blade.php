
<x-layouts.app :title="__('Pontuações')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="mb-4">
                <h2 class="text-2xl font-bold">Tabela de Pontuações</h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Ranking de palavrões por pessoa</p>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-left text-lg">
                    <thead class="border-b border-neutral-200 text-xs uppercase dark:border-neutral-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">Posição</th>
                        <th scope="col" class="px-6 py-3">Nome</th>
                        <th scope="col" class="px-6 py-3">Total de Palavrões</th>
                        <th scope="col" class="px-6 py-3">Valor a Pagar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($resultados as $index => $resultado)
                        <tr @class([
                                'border-b border-neutral-200 dark:border-neutral-700',
                                'bg-yellow-50 dark:bg-yellow-900/20' => $index === 0,
                                'bg-gray-50 dark:bg-neutral-700/20' => $index === 1,
                                'bg-orange-50 dark:bg-orange-900/20' => $index === 2,
                            ])>
                            <td class="px-6 py-4">
                                @if($index === 0)
                                    🥇
                                @elseif($index === 1)
                                    🥈
                                @elseif($index === 2)
                                    🥉
                                @else
                                    {{ $index + 1 }}º
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $resultado['nome'] }}</td>
                            <td class="px-6 py-4">
                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $resultado['total'] }}
                                    </span>
                            </td>
                            <td class="px-6 py-4">
                                    <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        {{ number_format($resultado['valor_total'], 2) }}€
                                    </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
