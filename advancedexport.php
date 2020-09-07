<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'classes/Group/AddressGroup.php';
require_once 'classes/Group/CategoryGroup.php';
require_once 'classes/Group/CustomerGroup.php';
require_once 'classes/Group/ManufacturerGroup.php';
require_once 'classes/Group/NewsletterGroup.php';
require_once 'classes/Group/OrderGroup.php';
require_once 'classes/Group/ProductGroup.php';
require_once 'classes/Group/SupplierGroup.php';

require_once 'classes/FTP/SFTP.php';
require_once 'classes/FTP/FTP.php';

require_once 'classes/Model/AdvancedExportClass.php';
require_once 'classes/Model/AdvancedExportCronClass.php';
require_once 'classes/Model/AdvancedExportFieldClass.php';
//require_once 'controllers/admin/AdminAdvancedExportImportController.php';

require_once 'classes/Install.php';
require_once 'classes/Uninstall.php';

require_once 'classes/Data/Cron.php';
require_once 'classes/ModuleTools.php';

require_once 'classes/Export/Export.php';
require_once 'classes/Data/ExportEnum.php';

define('_ADMIN_AE_', 'AdminAdvancedExport');
define('_ADMIN_AE_BASE_', 'AdminAdvancedExportBase');
define('_ADMIN_AE_CRON_', 'AdminAdvancedExportCron');
define('_ADMIN_AE_MODEL_', 'AdminAdvancedExportModel');
define('_ADMIN_AE_MODEL_FIELD_', 'AdminAdvancedExportModelField');
define('_ADMIN_AE_MODEL_FILE_', 'AdminAdvancedExportModelFile');
define('_ADMIN_AE_IMPORT_', 'AdminAdvancedExportImport');
define('_ADMIN_AE_IMPORT_FILE_', 'AdminAdvancedExportImportFile');
define('_ADMIN_AE_PRESTA_IMPORT_', 'AdminAdvancedExportPrestaImport');

class Advancedexport extends Module
{
    public $moduleTools;

    public function __construct()
    {
        $this->name = 'advancedexport';
        $this->tab = 'administration';
        $this->bootstrap = true;
        $this->author = 'Smart Soft';
        $this->need_instance = 0;
        $this->version = '4.5.9';
        $this->displayName = $this->l('Advanced Export');
        $this->description = $this->l(
            'Advanced CSV Export is an easy to use but powerful tool for export products, orders, categories, 
            suppliers, manufacturers, newsletters in csv format.'
        );
        $this->module_key = 'a3895af3e1e55fa47a756b6e973e77fe';
        $this->moduleTools = new ModuleTools();

        parent::__construct();
    }

    public function install()
    {
        if (!Install::run() or !parent::install()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!Uninstall::run() or !parent::uninstall()) {
            return false;
        }

        return true;
    }

    /**
     * Add application static methods for easy tests
     * @return ModuleTools
     */
    public function getModuleTools()
    {
        return $this->moduleTools;
    }

    public function getContent()
    {
        $this->moduleTools->redirectAdmin(
            Context::getContext()->link->getAdminLink(_ADMIN_AE_, true)
        );
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function hookAjaxCall()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        $action = Tools::getValue('action');

        switch ($action) {
            case 'checkConnection':
                echo $this->checkConnection();
                break;
        }
    }

    public function getExportType($id_advancedexport)
    {
        $export = new Export();
        $export->createExportFile(new AdvancedExportClass($id_advancedexport));
    }

    public function cronExportTask($id)
    {
        Context::getContext()->link = new Link();
        $this->getExportType($id);
    }

    public function cronImportTask($id)
    {
        require_once 'controllers/admin/AdminAdvancedExportImportController.php';

        $context = Context::getContext();
        $context->employee = new Employee(1);

        $rootDir = getenv('_PS_ROOT_DIR_');
        define('_PS_ADMIN_DIR_', $rootDir . '/admin-dev');

        $advancedExportImportController = new AdminAdvancedExportImportController();
        $advancedExportImportController->getImportPath(new AdvancedExportImportClass($id), false);
        $advancedExportImportController->addImportGETValues($id);
        $advancedExportImportController->runImport(0, 999999999, false, 0, $id);
    }
}
