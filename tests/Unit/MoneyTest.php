<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public static function brazilianValues(): array
    {
        return [
            'milhar com centavos' => ['1.430.000,00', '1430000.00'],
            'sem separador de milhar' => ['17600,5', '17600.50'],
            'inteiro' => ['33000', '33000.00'],
            'vazio' => ['', null],
            'nulo' => [null, null],
        ];
    }

    #[DataProvider('brazilianValues')]
    public function test_parses_brazilian_format(?string $input, ?string $expected): void
    {
        $this->assertSame($expected, Money::parse($input));
    }

    public function test_formats_and_displays_values(): void
    {
        $this->assertSame('1.430.000,01', Money::format('1430000.01'));
        $this->assertSame('', Money::format(null));
        $this->assertSame('R$ 17.600,00', Money::display(17600));
        $this->assertSame('sem limite', Money::display(null, 'sem limite'));
    }

    public function test_describes_value_ranges(): void
    {
        $this->assertSame('até R$ 17.600,00', Money::range(0, 17600));
        $this->assertSame('R$ 17.600,01 a R$ 176.000,00', Money::range('17600.01', '176000.00'));
        $this->assertSame('a partir de R$ 1.430.000,01', Money::range('1430000.01', null));
        $this->assertSame('—', Money::range(null, null));
    }

    public function test_pattern_accepts_only_valid_amounts(): void
    {
        foreach (['0,00', '176.000,00', '1430000,01', '5'] as $valid) {
            $this->assertMatchesRegularExpression(Money::PATTERN, $valid);
        }

        foreach (['1,2,3', 'abc', '17.60,00', '-5'] as $invalid) {
            $this->assertDoesNotMatchRegularExpression(Money::PATTERN, $invalid);
        }
    }
}
