<?php

namespace App\Enums;

/**
 * Natureza do objeto licitado, que define qual faixa de valor das
 * modalidades se aplica.
 */
enum BiddingType: string
{
    case PurchaseServices = 'purchase_services';
    case ConstructionEngineering = 'construction_engineering';

    public function label(): string
    {
        return match ($this) {
            self::PurchaseServices => 'Compras e serviços',
            self::ConstructionEngineering => 'Obras e engenharia',
        };
    }

    public function minimumColumn(): string
    {
        return "{$this->value}_minimum_value";
    }

    public function maximumColumn(): string
    {
        return "{$this->value}_maximum_value";
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }
}
