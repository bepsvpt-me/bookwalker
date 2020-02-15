<?php

namespace App\Indexes;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

final class BookIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        //
    ];
}
