<?php

namespace TMCms\Modules\EmailTemplates;

use TMCms\Cache\Cacher;
use TMCms\Config\Settings;
use TMCms\Modules\EmailTemplates\Object\EmailTemplate;
use TMCms\Modules\EmailTemplates\Object\EmailTemplateCollection;
use TMCms\Modules\IModule;
use TMCms\Network\Mailer;
use TMCms\Traits\singletonInstanceTrait;

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
                $cacher->set($cache_key, $content, 60);
            } else {
                // Create empty with this ket
                ModuleEmailTemplates::createNewTemplate($key);

                dump('Email template with key "'. $key .'" not found and was auto-created. Please send email again.');
            }

        }
        foreach ($data as $k => $v) {
            $content = str_replace('{%' . $k . '%}', $v, $content);
        }

        return $content;
    }

    public static function send($key, $data, $subject, $to)
    {
        $to = (array)$to;

        $body = self::get($key, $data);
        if (!$body) {
            return;
        }

        $mailer = Mailer::getInstance()
            ->setSubject($subject)
            ->setSender(Settings::getCommonEmail())
            ->setMessage($body)
        ;

        foreach ($to as $to_email) {
            $mailer->setRecipient($to_email);
        }

        $mailer->send();
    }

    private static function createNewTemplate($key)
    {
        $template = new EmailTemplate();
        $template->setKey($key);
        $template->save();
    }
}