<?php

namespace App\Http\Controllers;

use App\Models\Contagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ContagemController extends Controller
{
    public function increment(Request $request): RedirectResponse
    {
        Log::info('Iniciando registro de palavrão', [
            'ip' => $request->ip(),
            'user_id' => auth()->id()
        ]);

        try {
            $validated = $request->validate([
                'pessoa_id' => 'required|integer|min:1',
                'palavra_id' => 'required|integer|min:1',
            ]);

            Log::info('Dados validados', $validated);

            $contagem = Contagem::registrarPalavrao(
                $validated['pessoa_id'],
                $validated['palavra_id']
            );

            Log::info('Palavrão registrado com sucesso', [
                'contagem_id' => $contagem->id,
                'pessoa_id' => $validated['pessoa_id'],
                'palavra_id' => $validated['palavra_id']
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Palavrão registrado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao registrar palavrão', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Erro ao registrar palavrão. Tente novamente.');
        }
    }

    private array $valores = [
        1 => 2.00,    // Puta
        2 => 3.00,    // Filho da Puta
        3 => 1.00,    // Foda-se
        4 => 1.00,    // Fodido
        5 => 0.50,    // Merda
        6 => 1.00,    // Cabrão
        7 => 0.50,    // Caralho
        8 => 1.00,    // Kenga
        9 => 1.00     // Galderia
    ];

    private function calcularValorTotal($contagens): float
    {
        $total = 0;
        foreach ($contagens as $palavraId => $quantidade) {
            $total += $this->valores[$palavraId] * $quantidade;
        }
        return $total;
    }

    public function tableResults()
    {
        $pessoas = [
            1 => 'Joana',
            2 => 'Sara',
            3 => 'Afonso',
            4 => 'Ivan',
            5 => 'Fábio',
            6 => 'Tiago',
            7 => 'Xavier',
            8 => 'Jota'
        ];

        $resultados = [];
        foreach ($pessoas as $id => $nome) {
            $resultados[] = [
                'id' => $id,
                'nome' => $nome,
                'total' => Contagem::totalPorPessoa($id),
                'valor_total' => Contagem::valorTotalPorPessoa($id)
            ];
        }

        // Ordenar por valor total em ordem decrescente
        usort($resultados, function($a, $b) {
            return $b['valor_total'] <=> $a['valor_total'];
        });

        return view('table-results', [
            'resultados' => $resultados
        ]);
    }

    public function getDashboardStats()
    {
        return [
            'total_hoje' => Contagem::totalHoje(),
            'ultimos_dias' => Contagem::ultimaSemana()
        ];
    }

    public function estatisticas(): JsonResponse
    {
        try {
            $estatisticas = [
                'por_pessoa' => [],
                'por_palavra' => []
            ];

            // Pegar contagens por pessoa
            for ($i = 1; $i <= 8; $i++) {
                $estatisticas['por_pessoa'][$i] = Contagem::totalPorPessoa($i);
            }

            // Pegar contagens por palavrão
            for ($i = 1; $i <= 9; $i++) {
                $estatisticas['por_palavra'][$i] = Contagem::totalPorPalavra($i);
            }

            return response()->json([
                'success' => true,
                'data' => $estatisticas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter estatísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
