<?php

namespace TMCms\Modules\EmailTemplates;

use TMCms\Cache\Cacher;
use TMCms\Config\Settings;
use TMCms\Modules\EmailTemplates\Entity\EmailTemplateEntity;
use TMCms\Modules\EmailTemplates\Entity\EmailTemplateEntityRepository;
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

    public static function send($key, $data, $to, $from_name = NULL, $attached_file_paths = [])
    {
        $to = (array)$to;

        $template = self::get($key, $data);
        if (!$template) {
            return;
        }

        $mailer = Mailer::getInstance()
            ->setSubject($template['subject'])
            ->setSender(Settings::getCommonEmail(), $from_name)
            ->setMessage($template['body']);

        foreach ($to as $to_email) {
            $mailer->setRecipient($to_email);
        }

        foreach ($attached_file_paths as $file) {
            $mailer->addAttachment(basename($file), file_get_contents($file));
        }

        $mailer->send();
    }

    public static function get($key, $data = array())
    {
        $cache_key = 'module_email_templates_' . $key . '_' . LNG;
        $cacher = Cacher::getInstance()->getDefaultCacher();

        $template = $cacher->get($cache_key);
        if (!$template) {
            /** @var EmailTemplateEntity $template */
            $template = EmailTemplateEntityRepository::findOneEntityByCriteria(['key' => $key]);
            if ($template) {
                $cacher->set($cache_key, $template, 600);
            } else {
                // Create empty with this ket
                ModuleEmailTemplates::createNewTemplate($key);

                dump('Email template with key "'. $key .'" not found and was auto-created. Please send email again.');
            }

        }

        $content = $template->getContent();
        $subject = $template->getSubject();

        foreach ($data as $k => $v) {
            $content = str_replace('{%' . $k . '%}', $v, $content);
            $subject = str_replace('{%' . $k . '%}', $v, $subject);
        }

        return [
            'body' => $content,
            'subject' => $subject,
        ];
    }

    private static function createNewTemplate($key)
    {
        $template = new EmailTemplateEntity();
        $template->setKey($key);
        $template->save();
    }
}