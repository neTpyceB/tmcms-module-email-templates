<?php
namespace TMCms\Modules\EmailTemplates\Entity;

use TMCms\Orm\EntityRepository;
use TMCms\Orm\TableStructure;

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
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            'content' => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
        ],
    ];
}
