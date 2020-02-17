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
        'analysis' => [
            'analyzer' => [
                'tsconvert' => [
                    'tokenizer' => 'tsconvert',
                ],
            ],
            'tokenizer' => [
                'tsconvert' => [
                    'type' => 'stconvert',
                    'delimiter' => '#',
                    'keep_both' => false,
                    'convert_type' => 't2s',
                ],
            ],
            'filter' => [
                'tsconvert' => [
                    'type' => 'stconvert',
                    'delimiter' => '#',
                    'keep_both' => false,
                    'convert_type' => 't2s',
                ],
            ],
            'char_filter' => [
                'tsconvert' => [
                    'type' => 'stconvert',
                    'convert_type' => 't2s',
                ],
            ],
        ],
    ];
}
