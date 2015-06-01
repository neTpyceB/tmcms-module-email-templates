<?php
namespace neTpyceB\TMCms\Modules\EmailTemplates\Object;

use neTpyceB\TMCms\Modules\CommonObject;

class EmailTemplate extends CommonObject {
    protected $db_table = 'm_email_templates';

    protected $multi_lng_fields = ['content'];
}