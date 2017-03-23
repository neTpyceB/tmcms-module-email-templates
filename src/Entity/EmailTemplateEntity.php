<?php
namespace TMCms\Modules\EmailTemplates\Entity;

use TMCms\Orm\Entity;

/**
 * Class EmailTemplate
 * @package TMCms\Modules\EmailTemplates\Entity
 *
 * @method string getContent()
 * @method string getSubject()
 * @method $this setContent(string $content)
 * @method $this setDescription(string $description)
 * @method $this setKey(string $key)
 * @method $this setSubject(string $subject)
 */
class EmailTemplateEntity extends Entity {
    protected $translation_fields = ['content', 'subject'];
}