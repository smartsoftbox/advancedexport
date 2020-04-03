<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/AdminAdvancedExportBaseController.php';

class AdminAdvancedExportModelFileController extends AdminAdvancedExportBaseController
{
    public $type;

    public function __construct()
    {
        $this->table = $this->type . 'files';
        $this->className = 'AdvancedExportClass';
        $this->fields_list = array(
            'id_files' => array(
                'title' => $this->l('Id'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 100,
            ),
            'filesize' => array(
                'title' => $this->l('File size'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
            ),
        );

        $this->bootstrap = true;
        $this->addRowAction('download');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Would you like to delete the selected items?'),
            )
        );

        parent::__construct();

        $this->type = $this->getEntity();
        $this->list_id = $this->type . 'files';
    }

    public function initToolbar()
    {
        parent::initToolbar();

        unset($this->toolbar_btn['new']);
    }

    public function displayDownloadLink($token = null, $id, $name = null)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_download.tpl');

        $tpl->assign(array(
            'href' => Context::getContext()->link->getAdminLink(_ADMIN_AE_MODEL_FILE_, true) .
                '&url=' . $this->type . '/' . $name . '&download' . '=1',
            'action' => $this->l('Download'),
            'is_presta_16' => (_PS_VERSION_ >= 1.6 ? true : false)
        ));

        return $tpl->fetch();
    }

    public function displayDeleteLink($token = null, $id, $name = null)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_delete.tpl');

        $tpl->assign(array(
            'href' => Context::getContext()->link->getAdminLink(_ADMIN_AE_MODEL_FILE_, true) .
                '&url=' . $this->type . '/' . $name . '&delete' . '=1',
            'action' => $this->l('Delete')
        ));

        return $tpl->fetch();
    }


    public function displayAjaxGetExportForm()
    {
        echo $this->renderList();
    }

    public function getFiles($type)
    {
        $cookie = Context::getContext()->cookie;

        $dirname = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/' . $type . '/';
        $files = array_map('basename', glob($dirname . '*.{csv,xlsx,xls,ods,pdf,html}', GLOB_BRACE));

        $total = count($files);
        $result = array();
        $name_filter = $this->context->cookie->{'advancedexportmodelfile' . $this->type . 'filesFilter_name'};
        $is_filter_active = isset($this->context->cookie->{'submitFilter' . $this->type . 'files'});
        $per_page = 50;
        $submitFilterTypeFiles = $this->context->cookie->{'submitFilter' . $this->type . 'files'};
        if ($typeFilesPagination = $this->moduleTools->getValue($this->type . 'files_pagination')) {
            $this->context->cookie->{$this->type . 'files_pagination'} = $typeFilesPagination;
            $per_page = $typeFilesPagination;
        }
        $start = ($submitFilterTypeFiles ? ($submitFilterTypeFiles - 1) * $per_page : 0);

        if ($is_filter_active && $name_filter) {
            $input = preg_quote($name_filter, '~');
            $files = preg_grep('~' . $input . '~', $files);
        }

        if ($files) {
            for ($i = $start; $i < ($start + $per_page); $i++) {
                if(isset($files[$i])) {
                    $result[] = array(
                        'id_files' => $i + 1,
                        'name' => $files[$i],
                        'type_with_name' => $type . '/' . $files[$i],
                        'filesize' => $this->formatSize(filesize($dirname . $files[$i])),
                        'url' => $type . '/' . $files[$i],
                    );
                }
            }
        }

        return array($result, $total);
    }

    public function formatSize($size)
    {
        $sizes = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
        if ($size == 0) {
            return 'n/a';
        } else {
            return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i];
        }
    }

    public function postProcess()
    {
        parent::postProcess();

        if (Tools::isSubmit('download')) {
            $path = _AE_CSV_PATH_ . Tools::getValue('url');
            $this->getFile($path);
        }

        if (Tools::isSubmit('delete')) {
            if ($this->deleteFile(Tools::getValue('url'))) {
                Tools::redirectAdmin(
                    Context::getContext()->link->getAdminLink(_ADMIN_AE_, true)
                );
            }
        }
    }

    public function processBulkDelete()
    {
        $files = Tools::getValue($this->type . 'filesBox');
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
        $this->redirect_after = Context::getContext()->link->getAdminLink(_ADMIN_AE_, true)
            . '&conf=1';
    }

    public function deleteFile($url)
    {
        $dir = (string)realpath(dirname(__FILE__) . '/../../csv/' . $url);
        if (!file_exists($dir)) {
            return true;
        }
        return unlink($dir);
    }

    public function renderList()
    {
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        self::$currentIndex = Context::getContext()->link->getAdminLink(
            _ADMIN_AE_MODEL_FILE_,
            true
        );
        $this->toolbar_title[] = $this->l('Files');
        list($files, $total) = $this->getFiles($this->type);
        $helper = new HelperList();
        $this->_listTotal = $total;
        $this->identifier = 'type_with_name';
        $helper->no_link = true;
        $helper->toolbar_title = 'Files';
        $this->setHelperDisplay($helper);
        $helper->tpl_vars = $this->tpl_list_vars;
        $helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;

        // For compatibility reasons, we have to check standard actions in class attributes
        foreach ($this->actions_available as $action) {
            if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action) {
                $this->actions[] = $action;
            }
        }

        $list = $helper->generateList($files, $this->fields_list);

        return $list;
    }

    public function processFilter()
    {
        parent::processFilter();

        if (Tools::getValue('submitFilter' . $this->list_id) || Tools::getValue($this->list_id . 'Orderby') ||
            Tools::getValue($this->list_id . 'Orderway') ||
            Tools::getValue($this->list_id . '_pagination')) {
            $this->redirect_after = Context::getContext()->link->getAdminLink(
                    _ADMIN_AE_,
                    true
                ) . $this->getFilters();
        }
    }

    public function processResetFilters($list_id = null)
    {
        parent::processResetFilters($list_id); // TODO: Change the autogenerated stub

        $this->redirect_after = Context::getContext()->link->getAdminLink(
                _ADMIN_AE_,
                true
            ) . $this->getFilters();
    }
}
