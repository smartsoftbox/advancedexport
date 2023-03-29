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

class AdminAdvancedExportController extends AdminAdvancedExportBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $filters = $this->getFilters();

        $this->content .= '<script type="text/javascript">
            var ae_controller_model_url = "' . $this->context->link->getAdminLink(_ADMIN_AE_MODEL_)
            . $filters .'";
            var ae_controller_model = "' . _ADMIN_AE_MODEL_ . '";
            var ae_controller_import_url = "' . $this->context->link->getAdminLink(_ADMIN_AE_IMPORT_)
            . $filters . '";
            var ae_controller_import = "' . _ADMIN_AE_IMPORT_ . '";
            var ae_controller_cron_url = "' . $this->context->link->getAdminLink(_ADMIN_AE_CRON_)
            . $filters . '";
            var ae_controller_cron = "' . _ADMIN_AE_CRON_ . '";
        </script>';

        $this->path = __PS_BASE_URI__ . 'modules/advancedexport/';

        $this->context->controller->addCSS($this->path . 'views/css/admin.css');
        $this->context->controller->addCSS($this->path . 'views/css/duallist.css');
        $this->context->controller->addCSS($this->path . 'views/css/bootstrap-editable.css');
        $this->context->controller->addCSS($this->path . 'views/css/jquery.percentageloader-0.1.css');
        $this->context->controller->addJS($this->path . 'views/js/tabs.js');
        $this->context->controller->addJS($this->path . 'views/js/import.js');

        if (_PS_VERSION_ >= 1.6) {
            $this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/ui/jquery.ui.sortable.min.js');
            $this->context->controller->addJS($this->path . 'views/js/admin.js');
        } else {
            $this->context->controller->addJS($this->path . 'views/js/jquery-ui-1.10.4.custom.min.js');
            $this->context->controller->addJS($this->path . 'views/js/fixadmin.js');
            $this->context->controller->addCSS($this->path . 'views/css/fixadmin.css');
            $this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/ui/jquery.ui.datepicker.min.js');
            $this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/ui/themes/base/jquery.ui.datepicker.css');
            $this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/ui/themes/base/jquery.ui.theme.css');
        }

        $this->context->controller->addJS($this->path . 'views/js/duallist.js');
        $this->context->controller->addJS($this->path . 'views/js/selectall.chosen.js');
        $this->context->controller->addJS($this->path . 'views/js/jquery.percentageloader-0.1.min.js');

        $this->context->controller->addJS($this->path . 'views/js/jquery.cooki-plugin.js');
        $this->context->controller->addJS($this->path . 'views/js/clipboard.min.js');
    }

    public function initModal()
    {
        parent::initModal();

        if (_PS_VERSION_ > 1.6) {
            $modal_content = $this->context->smarty->fetch(_PS_MODULE_DIR_ .
                'advancedexport/views/templates/admin/advanced_export/controllers/import/modal_import_progress.tpl');
            $this->modals[] = array(
                'modal_id' => 'importProgress',
                'modal_class' => 'modal-md',
                'modal_title' => $this->l('Importing your data...'),
                'modal_content' => $modal_content,
            );
        }
    }

    public function initContent()
    {
        parent::initContent();
        $cron_url = $this->context->link->getModuleLink(
            'advancedexport',
            'cron',
            array('secure_key' => (string)Configuration::get('ADVANCEDEXPORT_SECURE_KEY'))
        );

        $this->context->smarty->assign(array(
            'export_types' => ExportEnum::getExportEntities(),
            'cron_url' => $cron_url,
            'start' => dirname(__FILE__) . '/../../views/templates/admin/start.tpl',
            'is_17' => $this->isGreaterOrEqualThenPrestaShopVersion(1.7),
        ));
        $content = $this->context->smarty->fetch(_PS_MODULE_DIR_ .
            'advancedexport/views/templates/admin/index.tpl');

        $this->context->smarty->assign(array(
            'content' => $this->content . $content
        ));
    }

    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
    }
}
