<?php
/**
 * 2019 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class AdvancedexportCronModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $token = Configuration::get('ADVANCEDEXPORT_SECURE_KEY');

        if (empty($token) or $token !== Tools::getValue('secure_key') or !$this->module->active) {
            Tools::redirect('index.php');
        }

        ob_start();
        $this->runCron();
        ob_end_clean();

        die();
    }

    protected function isTimeForRun($cron)
    {
        $hour = ($cron['cron_hour'] === '*') ? date('H') : $cron['cron_hour'];
        $day = ($cron['cron_day'] === '*') ? date('d') : $cron['cron_day'];
        $month = ($cron['cron_month'] === '*') ? date('m') : $cron['cron_month'];
        $day_of_week = ($cron['cron_week'] === '*') ? date('D') :
            date('D', strtotime('Sunday +' . $cron['cron_week'] . ' days'));

        $day = date('Y') . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
        $execution = $day_of_week . ' ' . $day . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT);
        $now = date('D Y-m-d H');

        return !(bool)strcmp($now, $execution);
    }

    private function runCron()
    {
        $tasks = Db::getInstance()->executeS(
            'SELECT * FROM ' . _DB_PREFIX_ . 'advancedexportcron WHERE `active` = 1'
        );

        if (is_array($tasks) and count($tasks) > 0) {
            foreach ($tasks as $task) {
                if ($this->isTimeForRun($task)) {
                    if ($task['is_import']) {
                        $this->module->cronImportTask($task['id_model']);
                    } else {
                        $this->module->cronExportTask($task['id_model']);
                    }
                    $query = 'UPDATE ' . _DB_PREFIX_ . 'advancedexportcron SET `last_export` = NOW() 
                    WHERE `id_advancedexportcron` = "' . (int)$task['id_advancedexportcron'] . '"';
                    Db::getInstance()->execute($query);
                }
            }
        }
    }
}
