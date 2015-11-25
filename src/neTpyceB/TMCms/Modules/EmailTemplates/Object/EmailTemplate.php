<?php
namespace neTpyceB\TMCms\Modules\EmailTemplates\Object;

use neTpyceB\TMCms\Orm\Entity;

class EmailTemplate extends Entity {
    protected $db_table = 'm_email_templates';
    protected $translation_fields = ['content'];
}