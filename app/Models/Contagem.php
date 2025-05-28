<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contagem extends Model
{
    use HasFactory;

    protected $table = 'contagems';

    protected $fillable = [
        'pessoa_id',
        'palavra_id',
        'valor'
    ];

    private static array $valores = [
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

    public static function registrarPalavrao($pessoaId, $palavraId)
    {
        return self::create([
            'pessoa_id' => $pessoaId,
            'palavra_id' => $palavraId,
            'valor' => self::$valores[$palavraId]
        ]);
    }

    public static function totalPorPessoa($pessoaId)
    {
        return self::where('pessoa_id', $pessoaId)->count();
    }

    public static function valorTotalPorPessoa($pessoaId)
    {
        return self::where('pessoa_id', $pessoaId)->sum('valor');
    }

    public static function totalPorPalavra($palavraId)
    {
        return self::where('palavra_id', $palavraId)->count();
    }

    public static function getValor(int $palavraId): float
    {
        return self::$valores[$palavraId] ?? 0;
    }

    public static function totalHoje()
    {
        return self::whereDate('created_at', now()->toDateString())->count();
    }

    public static function ultimaSemana()
    {
        $datas = collect();
        for ($i = 6; $i >= 0; $i--) {
            $data = now()->subDays($i)->toDateString();
            $total = self::whereDate('created_at', $data)->count();
            $datas->push([
                'data' => $data,
                'total' => $total
            ]);
        }
        return $datas;
    }

}
