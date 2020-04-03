<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../classes/ModuleTools.php';

abstract class ExportInterface
{
    protected $sorted_fields;
    protected $ae;
    protected $context;
    protected $link;
    protected $moduleTools;

    abstract public function getEntityData();

    public function __construct($ae, $sorted_fields)
    {
        $this->ae = $ae;
        $this->sorted_fields = $sorted_fields;
        $this->context = Context::getContext();
        $this->link = new Link();
        $this->moduleTools = new ModuleTools();
    }

    public function getModuleTools()
    {
        return $this->moduleTools;
    }
}
