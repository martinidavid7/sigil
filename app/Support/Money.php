<?php

namespace App\Support;

/**
 * Conversão entre valores monetários no formato brasileiro ("1.430.000,00")
 * e o formato numérico armazenado no banco ("1430000.00").
 */
class Money
{
    public const PATTERN = '/^\d{1,3}(\.\d{3})*(,\d{1,2})?$|^\d+(,\d{1,2})?$/';

    public static function parse(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalized = str_replace(['.', ','], ['', '.'], trim($value));

        return number_format((float) $normalized, 2, '.', '');
    }

    public static function format(int|float|string|null $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return number_format((float) $value, 2, ',', '.');
    }

    public static function display(int|float|string|null $value, string $empty = '—'): string
    {
        return $value === null ? $empty : 'R$ '.static::format($value);
    }

    /**
     * Descreve uma faixa de valores: "até R$ 17.600,00", "R$ 17.600,01 a
     * R$ 176.000,00", "a partir de R$ 1.430.000,01" ou "—" quando não há faixa.
     */
    public static function range(int|float|string|null $min, int|float|string|null $max): string
    {
        $hasMin = $min !== null && (float) $min > 0;

        return match (true) {
            $max === null && ! $hasMin => '—',
            $max === null => 'a partir de '.static::display($min),
            ! $hasMin => 'até '.static::display($max),
            default => static::display($min).' a '.static::display($max),
        };
    }
}
