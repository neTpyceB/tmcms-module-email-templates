<?php

namespace neTpyceB\TMCms\Modules\EmailTemplates;

use neTpyceB\TMCms\Cache\Cacher;
use neTpyceB\TMCms\Config\Settings;
use neTpyceB\TMCms\Modules\EmailTemplates\Object\EmailTemplate;
use neTpyceB\TMCms\Modules\EmailTemplates\Object\EmailTemplateCollection;
use neTpyceB\TMCms\Modules\IModule;
use neTpyceB\TMCms\Network\Mailer;
use neTpyceB\TMCms\Traits\singletonInstanceTrait;

defined('INC') or exit;

class ModuleEmailTemplates implements IModule
{
    use singletonInstanceTrait;

    public static $tables = [
        'templates' => 'm_email_templates'
    ];

    public static function get($key, $data = array())
    {
        $cache_key = 'module_email_templates_' . $key . '_' . LNG;
        $cacher = Cacher::getInstance()->getDefaultCacher();

        $content = $cacher->get($cache_key);
        if (!$content) {
            /** @var EmailTemplate $template */
            $template = EmailTemplateCollection::findOneEntityByCriteria(['key' => $key]);
            if ($template) {
                $content = $template->getContent();
                $cacher->set($cache_key, $content);
            }

        }
        foreach ($data as $k => $v) {
            $content = str_replace('{%' . $k . '%}', $v, $content);
        }

        return $content;
    }

    public static function send($key, $data, $subject, $to)
    {
        $body = self::get($key, $data);
        if (!$body) {
            return;
        }

        Mailer::getInstance()
            ->setSubject($subject)
            ->setSender(Settings::getCommonEmail())
            ->setRecipient($to)
            ->setMessage($body)
            ->send();
    }
}