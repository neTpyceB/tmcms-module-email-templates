<?php
namespace neTpyceB\TMCms\Modules\EmailTemplates\Object;

use neTpyceB\TMCms\Orm\Entity;

/**
 * Class EmailTemplate
 * @package neTpyceB\TMCms\Modules\EmailTemplates\Object
 *
 * @method string getContent()
 */
class EmailTemplate extends Entity {
    protected $db_table = 'm_email_templates';
    protected $translation_fields = ['content'];
}