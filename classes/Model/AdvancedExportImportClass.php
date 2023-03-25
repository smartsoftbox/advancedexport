<?php
/**
 * 2019 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class AdvancedExportImportClass extends ObjectModel
{
    public $id;
    public $entity;
    public $name;
    public $import_from;
    public $import_filename;
    public $filename;
    public $file_token;
    public $url;
    public $id_advancedexport;
    public $ftp_user_name;
    public $ftp_hostname;
    public $ftp_user_pass;
    public $ftp_directory;
    public $ftp_port;
    public $iso_lang;
    public $separator;
    public $multi_value_separator;
    public $truncate;
    public $regenerate;
    public $match_ref;
    public $forceIDs;
    public $send_email;
    public $skip;
    public $mapping;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'advancedexportimport',
        'primary' => 'id_advancedexportimport',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 255),
            'import_from' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'import_filename' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'filename' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'file_token' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'url' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'ftp_user_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'ftp_hostname' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'ftp_user_pass' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'ftp_directory' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'ftp_port' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'id_advancedexport' => array(
                'type' => self::TYPE_INT,
                'required' => true,
                'validate' => 'isNullOrUnsignedId'
            ),
            'iso_lang' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 3),
            'separator' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true,
                'size' => 1
            ),
            'multi_value_separator' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 1),
            'truncate' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'regenerate' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'match_ref' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'forceIDs' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'send_email' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'skip' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'mapping' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
        )
    );

    public function copyFromPost()
    {
        /* Classical fields */
        foreach ($_POST as $key => $value) {
            if (property_exists($this, $key) and $key != 'id_' . $this->table) {
                $this->{$key} = $value;
            }
        }
    }

    public static function getAll()
    {
        return Db::getInstance()->ExecuteS(
            'SELECT aei.*, ae.type, ae.name as advancedexport_name FROM `' . _DB_PREFIX_ . 'advancedexportimport` as aei
            LEFT JOIN `' . _DB_PREFIX_ . 'advancedexport` as ae ON(aei.`id_advancedexport` = ae.`id_advancedexport`)'
        );
    }

    public function getFields()
    {
        parent::validateFields();
        $fields = $this->getAllFields();
        return $fields;
    }

    public function getAllFields()
    {
        $fields = array();
        $fields['id_advancedexportimport'] = (int)($this->id);
        $fields['name'] = (string)($this->name);
        $fields['import_filename'] = (string)($this->import_filename);
        $fields['import_from'] = (string)($this->import_from);
        $fields['filename'] = (string)($this->filename);
        $fields['file_token'] = (string)($this->file_token);
        $fields['url'] = (string)($this->url);
        $fields['id_advancedexport'] = (int)($this->id_advancedexport);
        $fields['ftp_hostname'] = (string)($this->ftp_hostname);
        $fields['ftp_user_pass'] = (string)($this->ftp_user_pass);
        $fields['ftp_user_name'] = (string)($this->ftp_user_name);
        $fields['ftp_directory'] = (string)($this->ftp_directory);
        $fields['ftp_port'] = (string)($this->ftp_port);
        $fields['entity'] = (string)($this->entity);
        $fields['iso_lang'] = (string)($this->iso_lang);
        $fields['separator'] = (string)($this->separator);
        $fields['multi_value_separator'] = (string)($this->multi_value_separator);
        $fields['truncate'] = (bool)($this->truncate);
        $fields['regenerate'] = (bool)($this->regenerate);
        $fields['match_ref'] = (bool)($this->match_ref);
        $fields['forceIDs'] = (bool)($this->forceIDs);
        $fields['send_email'] = (bool)($this->send_email);
        $fields['skip'] = $this->skip;
        $fields['mapping'] = (string)($this->mapping);

        return $fields;
    }
}
