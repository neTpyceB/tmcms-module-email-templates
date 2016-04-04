<?php
namespace TMCms\Modules\EmailTemplates\Object;

use TMCms\Orm\EntityRepository;

class EmailTemplateEntityRepository extends EntityRepository {
    protected $translation_fields = ['content', 'subject'];
    protected $table_structure = [
        'fields' => [
            'key' => [
                'type' => 'varchar',
            ],
            'description' => [
                'type' => 'varchar',
            ],
            'subject' => [
                'type' => 'translation',
            ],
            'content' => [
                'type' => 'translation',
            ],
        ],
    ];
}