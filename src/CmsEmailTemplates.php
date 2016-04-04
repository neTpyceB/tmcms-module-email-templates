<?php
namespace TMCms\Modules\EmailTemplates;

use TMCms\Admin\Messages;
use TMCms\HTML\BreadCrumbs;
use TMCms\HTML\Cms\CmsFormHelper;
use TMCms\HTML\Cms\CmsTable;
use TMCms\HTML\Cms\Column\ColumnData;
use TMCms\HTML\Cms\Column\ColumnDelete;
use TMCms\HTML\Cms\Column\ColumnEdit;
use TMCms\HTML\Cms\Filter\Text;
use TMCms\HTML\Cms\FilterForm;
use TMCms\Log\App;
use TMCms\Modules\EmailTemplates\Object\EmailTemplateEntity;
use TMCms\Modules\EmailTemplates\Object\EmailTemplateEntityRepository;

defined('INC') or exit;


class CmsEmailTemplates
{

    public function _default() {
        echo BreadCrumbs::getInstance()
            ->addCrumb('Email templates')
        ;

        $templates = new EmailTemplateEntityRepository();

        echo CmsTable::getInstance()
            ->addData($templates)
            ->addColumn(ColumnEdit::getInstance('key')
                ->setHref('?p='. P .'&do=edit&id={%id%}')
            )
            ->addColumn(ColumnData::getInstance('description'))
            ->addColumn(ColumnDelete::getInstance())
            ->attachFilterForm(
                FilterForm::getInstance()
                    ->setWidth('100%')
                    ->setCaption('<a class="btn btn-success" href="?p='. P .'&do=add">Add Template</a>')
                    ->addFilter('Key', Text::getInstance('key')
                        ->actAs('like')
                    )
            )
        ;
    }

    private static function __add_edit_form($data = []) {
        return CmsFormHelper::outputForm(ModuleEmailTemplates::$tables['templates'], [
            'action' => '?p='. P .'&do=_add',
            'button' => 'Add Template',
            'fields' => [
                'key',
                'description' => [
                    'hint' => 'Visible only in Admin panel'
                ],
                'subject' => [
                    'translation' => true,
                    'help' => 'Use {%params%} replaces',
                ],
                'content' => [
                    'translation' => true,
                    'type' => 'textarea',
                    'edit' => 'wysiwyg',
                    'rows' => 15,
                    'help' => 'Use {%params%} replaces',
                ],
            ],
            'data' => $data
        ]);
    }

    public static function add() {
        echo BreadCrumbs::getInstance()
            ->addCrumb('Email templates')
            ->addCrumb('Add template')
        ;

        echo self::__add_edit_form();
    }

    public static function edit() {
        $id = (int)$_GET['id'];
        if (!$id) return;

        $template = new EmailTemplateEntity($id);

        echo BreadCrumbs::getInstance()
            ->addCrumb('Email templates')
            ->addCrumb('Update template')
            ->addCrumb($template->getKey())
        ;

        echo self::__add_edit_form($template->getAsArray())
            ->setAction('?p='. P .'&do=_edit&id='. $id)
            ->setSubmitButton('Update Template')
        ;
    }

    public static function _add() {
        $template = new EmailTemplateEntity();
        $template->loadDataFromArray($_POST);
        $template->save();

        App::add('Template '. $template->getKey() .' created');

        Messages::sendMessage('Template created');

        go('?p='. P .'&highlight='. $template->getId());
    }

    public static function _edit() {
        $id = (int)$_GET['id'];
        if (!$id) return;

        $template = new EmailTemplateEntity($id);
        $template->loadDataFromArray($_POST);
        $template->save();

        App::add('Template '. $template->getKey() .' created');

        Messages::sendMessage('Template created');

        go('?p='. P .'&highlight='. $template->getId());
    }

    public static function _delete() {
        $id = (int)$_GET['id'];
        if (!$id) return;

        $template = new EmailTemplateEntity($id);
        $template->deleteObject();

        App::add('Template '. $template->getKey() .'  deleted');

        Messages::sendMessage('Template deleted');

        back();
    }
}