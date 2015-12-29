<?php
namespace TMCms\Modules\EmailTemplates\Object;

use TMCms\Orm\EntityRepository;

class EmailTemplateCollection extends EntityRepository {
    protected $db_table = 'm_email_templates';
    protected $translation_fields = ['content'];
}