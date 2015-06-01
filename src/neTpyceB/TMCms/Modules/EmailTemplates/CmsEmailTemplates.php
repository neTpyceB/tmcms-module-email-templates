<?php
namespace neTpyceB\TMCms\Modules\EmailTemplates;

use neTpyceB\TMCms\Admin\Messages;
use neTpyceB\TMCms\HTML\BreadCrumbs;
use neTpyceB\TMCms\HTML\Cms\CmsFormHelper;
use neTpyceB\TMCms\HTML\Cms\CmsTable;
use neTpyceB\TMCms\HTML\Cms\Column\ColumnData;
use neTpyceB\TMCms\HTML\Cms\Column\ColumnDelete;
use neTpyceB\TMCms\HTML\Cms\Column\ColumnEdit;
use neTpyceB\TMCms\HTML\Cms\Filter\Text;
use neTpyceB\TMCms\HTML\Cms\FilterForm;
use neTpyceB\TMCms\Log\App;
use neTpyceB\TMCms\Modules\EmailTemplates\Object\EmailTemplate;

defined('INC') or exit;


class CmsEmailTemplates
{

    public function _default() {
        echo BreadCrumbs::getInstance()
            ->addCrumb('Email templates')
        ;

        echo CmsTable::getInstance()
            ->addDataSql('SELECT `id`, `key`, `description` FROM `'. ModuleEmailTemplates::$tables['templates'] .'` ORDER BY `key`')
            ->addColumn(ColumnEdit::getInstance('key')->href('?p='. P .'&do=edit&id={%id%}'))
            ->addColumn(ColumnData::getInstance('description'))
            ->addColumn(ColumnDelete::getInstance()->href('?p='. P .'&do=_delete&id={%id%}'))
            ->attachFilterForm(
                FilterForm::getInstance()->setWidth('100%')->setCaption('<a class="btn btn-success" href="?p='. P .'&do=add">Add Template</a>')
                    ->addFilter('Key', Text::getInstance('key')->actAs('like'))
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
                'content' => [
                    'multilng' => true,
                    'type' => 'textarea',
                    'edit' => 'wysiwyg',
                    'rows' => 15
                ]
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

        $template = new EmailTemplate($id);

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
        $template = new EmailTemplate();
        $template->loadDataFromArray($_POST);
        $template->save();

        App::add('Template '. $template->getKey() .' created');

        Messages::sendMessage('Template created');

        go('?p='. P .'&highlight='. $template->getId());
    }

    public static function _edit() {
        $id = (int)$_GET['id'];
        if (!$id) return;

        $template = new EmailTemplate($id);
        $template->loadDataFromArray($_POST);
        $template->save();

        App::add('Template '. $template->getKey() .' created');

        Messages::sendMessage('Template created');

        go('?p='. P .'&highlight='. $template->getId());
    }

    public static function _delete() {
        $id = (int)$_GET['id'];
        if (!$id) return;

        $template = new EmailTemplate($id);
        $template->deleteObject();

        App::add('Template '. $template->getKey() .'  deleted');

        Messages::sendMessage('Template deleted');

        back();
    }
}