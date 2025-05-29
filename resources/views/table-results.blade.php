
<x-layouts.app :title="__('Pontuações')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="mb-4">
                <h2 class="text-2xl font-bold">Tabela de Pontuações</h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Ranking de palavrões por pessoa</p>
            </div>

            <!-- Pódio para 1º, 2º e 3º lugares -->
            @if(count($resultados) >= 3)
                <div class="mb-8 grid grid-cols-3 gap-4">

                    <!-- 2º Lugar (esquerda) -->
                    <div class="flex flex-col items-center justify-end">
                        <div class="relative h-[120px] w-full bg-gradient-to-r from-[#833ab4] to-[#fd1d1d] rounded-t-lg">
                            <div class="absolute inset-0 flex items-center justify-center text-4xl">🥈</div>
                        </div>
                        <div class="w-full bg-gray-50 dark:bg-neutral-700/20 p-4 text-center rounded-b-lg">
                            <p class="text-xl font-bold">{{ $resultados[1]['nome'] }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">{{ $resultados[1]['total'] }} palavrões</p>
                            <p class="text-green-600 dark:text-green-400 font-medium mt-1">
                                {{ number_format($resultados[1]['valor_total'], 2) }}€
                            </p>
                        </div>
                    </div>

                    <!-- 1º Lugar (centro) -->
                    <div class="flex flex-col items-center justify-end -mt-8">
                        <div class="relative h-[160px] w-full bg-gradient-to-r from-[#833ab4] to-[#fd1d1d] rounded-t-lg">
                            <div class="absolute inset-0 flex items-center justify-center text-4xl">🏆</div>
                        </div>
                        <div class="w-full bg-yellow-50 dark:bg-yellow-900/20 p-4 text-center rounded-b-lg">
                            <p class="text-2xl font-bold">{{ $resultados[0]['nome'] }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">{{ $resultados[0]['total'] }} palavrões</p>
                            <p class="text-green-600 dark:text-green-400 font-medium mt-1">
                                {{ number_format($resultados[0]['valor_total'], 2) }}€
                            </p>
                        </div>
                    </div>

                    <!-- 3º Lugar (direita) -->
                    <div class="flex flex-col items-center justify-end">
                        <div class="relative h-[100px] w-full bg-gradient-to-r from-[#833ab4] to-[#fd1d1d] rounded-t-lg">
                            <div class="absolute inset-0 flex items-center justify-center text-4xl">🥉</div>
                        </div>
                        <div class="w-full bg-orange-50 dark:bg-orange-900/20 p-4 text-center rounded-b-lg">
                            <p class="text-xl font-bold">{{ $resultados[2]['nome'] }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">{{ $resultados[2]['total'] }} palavrões</p>
                            <p class="text-green-600 dark:text-green-400 font-medium mt-1">
                                {{ number_format($resultados[2]['valor_total'], 2) }}€
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabela para os demais lugares -->
            <div class="relative overflow-x-auto">
                <table class="w-full text-lg">
                    <thead class="border-b border-neutral-200 text-xs uppercase dark:border-neutral-700">
                    <tr class="text-left">
                        <th scope="col" class="px-6 py-3">Posição</th>
                        <th scope="col" class="px-6 py-3">Nome</th>
                        <th scope="col" class="px-6 py-3">Total de Palavrões</th>
                        <th scope="col" class="px-6 py-3">Valor a Pagar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($resultados as $index => $resultado)
                        @if($index > 2)
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <td class="px-6 py-4">{{ $index + 1 }}º</td>
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
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
