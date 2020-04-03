<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class ModuleTools
{
    public $context;

    public function __construct()
    {
        $this->context = Context::getContext();
    }

    public function query($sql_query)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->query($sql_query);
    }

    public function execute($sql)
    {
        return Db::getInstance()->Execute($sql);
    }

    public function executeS($sql)
    {
        return Db::getInstance()->ExecuteS($sql);
    }

    public function getLanguages()
    {
        return Language::getLanguages();
    }

    public function getConfiguration($value)
    {
        return (int)Configuration::get($value);
    }

    public function getValue($value, $default_value = false)
    {
        return Tools::getValue($value, $default_value);
    }

    public function isSubmit($value)
    {
        return Tools::isSubmit($value);
    }

    public function redirectAdmin($action)
    {
        Tools::redirectAdmin(
            $action
        );
    }

    public function getCookie($name)
    {
        return Context::getContext()->cookie->$name;
    }

    public function getCookieObject()
    {
        return Context::getContext()->cookie;
    }

    public function fetch($name)
    {
        return Context::getContext()->smarty->fetch($name);
    }

    public function getAdminLink($controller)
    {
        return $this->context->link->getAdminLink($controller);
    }

    public function getIsoById($iso_lang)
    {
        return Language::getIsoById($iso_lang);
    }

    public function copy($source, $destination)
    {
        Tools::copy($source, $destination);
    }

    public function addCSS($path)
    {
        return $this->context->controller->addCSS($path);
    }

    public function addJS($path)
    {
        return $this->context->controller->addJS($path);
    }

    public function isFeatureActive()
    {
        return Shop::isFeatureActive();
    }

    public function dbGetValue($sql)
    {
        return Db::getInstance()->getValue($sql);
    }
}
