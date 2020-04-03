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
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportImportClass.php';


class AdminAdvancedExportImportFileController extends AdminAdvancedExportBaseController
{
    const FORMATS = array('csv', 'xlsx', 'xls', 'xslt', 'odt', 'ods');
    public $type;

    public function __construct()
    {
        $this->table = 'importfiles';
        $this->className = 'AdvancedExportClass';
        $this->fields_list = array(
            'id_files' => array(
                'title' => $this->l('Id'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
                'remove_onclick' => true,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 100,
                'orderby' => false,
                'remove_onclick' => true,
            ),
            'filesize' => array(
                'title' => $this->l('File size'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
                'remove_onclick' => true,
            ),
        );

        $this->bootstrap = true;
        $this->addRowAction('download');
        $this->addRowAction('log');

        parent::__construct();

        $this->type = $this->getEntity();
    }

    public function initToolbar()
    {
        parent::initToolbar();

        unset($this->toolbar_btn['new']);
    }

    // todo check if you need null as default value
    public function displayDownloadLink($token = null, $id, $name = null)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_download.tpl');

        $tpl->assign(array(
            'href' => Context::getContext()->link->getAdminLink(_ADMIN_AE_IMPORT_FILE_, true)
                . '&url=' . $id . '&download=1',
            'action' => $this->l('Download'),
            'is_presta_16' => $this->isGreaterOrEqualThenPrestaShopVersion(1.6)
        ));

        return $tpl->fetch();
    }

    public function displayLogLink($token = null, $id, $name = null)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_log.tpl');

        $tpl->assign(array(
            'href' => Context::getContext()->link->getAdminLink('AdminLogs', true)
                . '&filters[message]=' . basename($id),
            'action' => $this->l('Show log'),
            'is_presta_16' => $this->isGreaterOrEqualThenPrestaShopVersion(1.6)
        ));

        return $tpl->fetch();
    }

    public function displayAjaxGetExportForm()
    {
        echo $this->renderList();
    }

    public function getFiles($id_import)
    {
        $files_filtered = array();
        $per_page = 50;

        $cookie = $this->moduleTools->getCookieObject();

        $dirname = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/import/' . $id_import . '/';
        $files = $this->getFilesFromDirectory($dirname, self::FORMATS);

        $total = count($files);

        $name_filter = $cookie->{'advancedexportimportfile' . $this->type . 'filesFilter_name'};
        $submitFilter = $cookie->{'submitFilter' . $this->type . 'files'};

        if ($typeFilesPagination = $this->moduleTools->getValue($this->type . 'files_pagination')) {
            $cookie->{$this->type . 'files_pagination'} = $typeFilesPagination;
            $per_page = $typeFilesPagination;
        }
        $start = ($submitFilter ? ($submitFilter - 1) * $per_page : 0);

        if ($submitFilter && $name_filter) {
            $input = preg_quote($name_filter, '~');
            $files = preg_grep('~' . $input . '~', $files);
        }

        if ($files) {
            for ($i = $start; $i < ($start + $per_page); $i++) {
                if (isset($files[$i])) {
                    $files_filtered[] = array(
                        'id_files' => $i + 1,
                        'name' => $files[$i],
                        'type_with_name' => $dirname . $files[$i],
                        'filesize' => $this->formatSize(filesize($dirname . $files[$i])),
                        'url' => $dirname . $files[$i],
                    );
                }
            }
        }

        return array($files_filtered, $total);
    }

    public function postProcess()
    {
        parent::postProcess();

        if (Tools::isSubmit('download')) {
            $this->getFile(Tools::getValue('url'));
        }
    }

    public function getImportId($id_import)
    {
        $cookie = $this->moduleTools->getCookieObject();
        if ($id_import) {
            $cookie->{'id_advancedexportimport'} = $id_import;
            $cookie->write();
        } else if ($cookie->{'id_advancedexportimport'}) {
            $id_import = $cookie->{'id_advancedexportimport'};
        }

        return $id_import;
    }

    public function renderList()
    {
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        self::$currentIndex = Context::getContext()->link->getAdminLink(
            _ADMIN_AE_IMPORT_FILE_,
            true
        );

        $id_import = $this->getImportId($this->moduleTools->getValue('id_advancedexportimport'));
        $this->toolbar_title = $this->getToolbarTitle(new AdvancedExportImportClass($id_import));

        list($files, $total) = $this->getFiles($id_import);

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

    /**
     * @param AdvancedExportImportClass $aeImport
     */
    public function getToolBarTitle(AdvancedExportImportClass $aeImport)
    {
        return $this->l('Files used for') . ' ' . $aeImport->name . ' ' .
            $this->l('import model id') . ' ' . $aeImport->id;
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
