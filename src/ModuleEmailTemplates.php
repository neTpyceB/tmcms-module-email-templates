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

    /**
     * @param string $key
     * @param array $data
     * @param array $to
     * @param string $from_name
     * @param array $attached_file_pat
     */
    public static function send(string $key, array $data = [], array $to = [], string $from_name = '', array $attached_file_paths = [])
    {
        $template = self::get($key, $data);

        $mailer = Mailer::getInstance()
            ->setSubject($template['subject'])
            ->setSender(Settings::getCommonEmail(), (string)$from_name)
            ->setMessage($template['body']);

        foreach ($to as $to_email) {
            $mailer->setRecipient($to_email);
        }

        foreach ($attached_file_paths as $file) {
            $mailer->addAttachment($file);
        }

        $mailer->send();
    }

    public static function get($key, $data = [])
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
                ModuleEmailTemplates::createNewTemplate($key, $data);

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

    private static function createNewTemplate($key, $data)
    {
        $template = new EmailTemplateEntity();
        $template->setKey($key);
        if ($data) {
            $template->setDescription('Possible keys are: ' . implode(', ', array_keys($data)));
        }
        $template->save();
    }
}
