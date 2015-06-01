<?php
namespace neTpyceB\TMCms\Modules\EmailTemplates;

use neTpyceB\TMCms\Cache\Cacher;
use neTpyceB\TMCms\Config\Settings;
use neTpyceB\TMCms\Modules\CommonObject;
use neTpyceB\TMCms\Modules\IModule;
use neTpyceB\TMCms\Network\Mailer;

defined('INC') or exit;

class ModuleEmailTemplates implements IModule {
	public static $tables = [
		'templates' => 'm_email_templates'
	];

	/** @var $this */
	private static $instance;

	public static function getInstance() {
		if (!self::$instance) self::$instance = new self;
		return self::$instance;
	}


    public static function get($key, $data = array()) {
        $cache_key = 'module_email_templates_'. $key .'_'. LNG;
        $cacher = Cacher::getInstance()->getDefaultCacher();

        $content = $cacher->get($cache_key);
        if (!$content) {
            $content = q_value('
SELECT
    `d1`.`'. LNG .'` AS `template_content`
FROM `'. self::$tables['templates'] .'` AS `t`
JOIN `cms_translations` AS `d1` ON `d1`.`id` = `t`.`content`
WHERE `t`.`key` = "'. $key .'"
            ');

            $cacher->set($cache_key, $content);
        }
        foreach ($data as $k => $v) {
            $content = str_replace('{%'. $k .'%}', $v, $content);
        }
        return $content;
    }

    public static function send($key, $data, $subject, $to) {
        $body = self::get($key, $data);
        if (!$body) return;
        Mailer::getInstance()->setSubject($subject)->setSender(Settings::get('common_email'))->setRecipient($to)->setMessage($body)->send();
    }
}