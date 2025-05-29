<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if (session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-800/30 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-800/30 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
                <div class="flex h-full flex-col items-center justify-center">
                    <h3 class="text-lg font-medium text-neutral-600 dark:text-neutral-300">
                        Palavrões Hoje
                    </h3>
                    <p class="mt-2 text-4xl font-bold" style="color: #fd1d1d"
                    >
                        {{ app(\App\Http\Controllers\ContagemController::class)->getDashboardStats()['total_hoje'] }}
                    </p>
                </div>
            </div>

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                <div x-data="{dados: {{ json_encode(app(\App\Http\Controllers\ContagemController::class)->getDashboardStats()['ultimos_dias']) }},
                        formatarData(dataStr) {
                            const data = new Date(dataStr);
                            return data.toLocaleDateString('pt-BR', {
                                day: '2-digit',
                                month: '2-digit'
                            });
                        },
                        init() {
                            setTimeout(() => {
                                if (typeof Chart === 'undefined') {
                                    console.error('Chart.js não está carregado');
                                    return;
                                }

                                const ctx = this.$refs.chart.getContext('2d');
                                new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: this.dados.map(d => this.formatarData(d.data)),
                                        datasets: [{
                                            label: 'Palavrões por Dia',
                                            data: this.dados.map(d => d.total),
                                            borderColor: '#fd1d1d',
                                            tension: 0.4,
                                            fill: true,
                                            backgroundColor: '#833ab4'
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: false
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1,
                                                    color: 'rgb(156, 163, 175)'
                                                },
                                                grid: {
                                                    color: 'rgba(156, 163, 175, 0.1)'
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    color: 'rgb(156, 163, 175)'
                                                },
                                                grid: {
                                                    color: 'rgba(156, 163, 175, 0.1)'
                                                }
                                            }
                                        }
                                    }
                                });
                            }, 100);
                        }
                    }">
                    <canvas x-ref="chart" class="h-full w-full"></canvas>
                </div>
            </div>
        </div>
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div x-data="{
                    showModal: false,
                    currentPerson: null,
                    pessoas: [
                        {id: 1, nome: 'Joana'},
                        {id: 2, nome: 'Sara'},
                        {id: 3, nome: 'Afonso'},
                        {id: 4, nome: 'Ivan'},
                        {id: 5, nome: 'Fábio'},
                        {id: 6, nome: 'Tiago'},
                        {id: 7, nome: 'Xavier'},
                        {id: 8, nome: 'Jota'}
                    ],
                    palavroes: [
                        {id: 1, nome: 'Puta', valor: 2.00},
                        {id: 2, nome: 'Filho da Puta', valor: 3.00},
                        {id: 3, nome: 'Foda-se', valor: 1.00},
                        {id: 4, nome: 'Fodido', valor: 1.00},
                        {id: 5, nome: 'Merda', valor: 0.50},
                        {id: 6, nome: 'Cabrão', valor: 1.00},
                        {id: 7, nome: 'Caralho', valor: 0.50},
                        {id: 8, nome: 'Kenga', valor: 1.00},
                        {id: 9, nome: 'Galderia', valor: 1.00}
                    ],
                    openModal(pessoa) {
                        this.currentPerson = pessoa;
                        this.showModal = true;
                    }
                }"
                class="h-full">
                <!-- Grid de pessoas -->
                <div class="grid h-full grid-cols-4 grid-rows-2">
                    <template x-for="pessoa in pessoas" :key="pessoa.id">
                        <button type="button" @click="openModal(pessoa)" class="flex h-full w-full items-center justify-center border border-neutral-200 bg-white/50 p-8 text-2xl font-medium transition-all hover:text-white dark:border-neutral-700 dark:bg-neutral-800/50 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity bg-gradient-to-r from-[#833ab4] to-[#fd1d1d]"></div>
                            <span class="relative z-10" x-text="pessoa.nome"></span>
                        </button>
                    </template>
                </div>

                <!-- Modal -->
                <div x-show="showModal"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                    @click.self="showModal = false">
                    <div class="w-full max-w-4xl rounded-xl bg-white p-8 dark:bg-neutral-800">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-3xl font-semibold">
                                Escolher palavrão para: <span x-text="currentPerson?.nome" style="color: #ff3838"></span>
                            </h3>
                            <button type="button" @click="showModal = false" class="rounded-lg p-2 text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-700 dark:hover:bg-white">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <template x-for="palavrao in palavroes" :key="palavrao.id">
                                <form method="POST" action="{{ route('contagem.increment') }}">
                                    @csrf
                                    <input type="hidden" name="pessoa_id" x-bind:value="currentPerson?.id">
                                    <input type="hidden" name="palavra_id" x-bind:value="palavrao.id">
                                    <button type="submit" class="group relative w-full rounded-xl border border-neutral-200 bg-white/50 p-6 text-xl font-medium transition-colors hover:bg-[#ff3838] hover:text-white dark:border-neutral-700 dark:bg-neutral-800/50">
                                        <span x-text="palavrao.nome"></span>
                                        <span class="absolute bottom-2 right-2 rounded-full bg-green-100 px-2 py-0.5 text-sm font-medium text-green-800 transition-colors group-hover:bg-white/20 group-hover:text-white dark:bg-green-900/30 dark:text-green-300"
                                            x-text="palavrao.valor.toFixed(2) + '€'">
                                        </span>
                                    </button>
                                </form>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            [x-cloak] { display: none !important; }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</x-layouts.app>
