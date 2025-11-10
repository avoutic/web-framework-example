<?php

return [
    'up' => [
        'actions' => [
            [
                'type' => 'create_table',
                'table_name' => 'posts',
                'fields' => [
                    [
                        'name' => 'title',
                        'type' => 'varchar',
                        'size' => 255,
                    ],
                    [
                        'name' => 'content',
                        'type' => 'text',
                    ],
                    [
                        'name' => 'user_id',
                        'type' => 'foreign_key',
                        'foreign_table' => 'users',
                        'foreign_field' => 'id',
                        'on_delete' => 'SET NULL',
                        'on_update' => 'CASCADE',
                        'null' => true,
                    ],
                    [
                        'name' => 'created_at',
                        'type' => 'int',
                        'default' => ['function' => 'UNIX_TIMESTAMP()'],
                    ],
                    [
                        'name' => 'updated_at',
                        'type' => 'int',
                    ],
                ],
                'constraints' => [
                    [
                        'type' => 'unique',
                        'values' => ['title'],
                    ],
                ],
            ],
        ],
    ],
    'down' => [
        'actions' => [
            [
                'type' => 'raw_query',
                'query' => 'DROP TABLE IF EXISTS `posts`',
                'params' => [],
            ],
        ],
    ],
];

