<?php
namespace TMCms\Modules\EmailTemplates\Object;

use TMCms\Orm\Entity;

/**
 * Class EmailTemplate
 * @package TMCms\Modules\EmailTemplates\Object
 *
 * @method string getContent()
 * @method string getSubject()
 * @method $this setContent(string $content)
 * @method $this setKey(string $key)
 * @method $this setSubject(string $subject)
 */
class EmailTemplateEntity extends Entity {
    protected $translation_fields = ['content', 'subject'];
}