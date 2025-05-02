<?php

namespace App\Enums;

use ArchTech\Enums\From;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;
use ArchTech\Enums\Options;
use ArchTech\Enums\InvokableCases;

enum GenerateNumberType: string
{
    use Names;
    use Values;
    use Options;
    use From;
    use InvokableCases;

    case INVOICE_NUMBER = 'invoice_number';
    case TRANSACTION_NUMBER = 'transaction_number';
    case VIRTUAL_NUMBER = 'virtual_number';
    case TRANSACTIONAL_NUMBER = 'transactional_number';

    public function label(): string
    {
        return ucwords(strtolower(str_replace(['_'], [' '], $this->name)));
    }

    public function uniqueGenerateNumberTemplate(): string
    {
        return match ($this) {
            self::INVOICE_NUMBER => 'INV-{month_year}#########',
            self::VIRTUAL_NUMBER => '{tenant_bcn}{month_year}#####',
            self::TRANSACTIONAL_NUMBER => '{type}{month_year}#####',
            self::TRANSACTION_NUMBER => '{trx_type}{month_year}#########',
        };
    }
}
