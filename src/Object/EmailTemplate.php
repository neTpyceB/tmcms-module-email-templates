<?php
namespace TMCms\Modules\EmailTemplates\Object;

use TMCms\Orm\Entity;

/**
 * Class EmailTemplate
 * @package TMCms\Modules\EmailTemplates\Object
 *
 * @method string getContent()
 */
class EmailTemplate extends Entity {
    protected $db_table = 'm_email_templates';
    protected $translation_fields = ['content'];
}