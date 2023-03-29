<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once dirname(__FILE__) . '/../../controllers/admin/AdminAdvancedExportBaseController.php';

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE); // todo check if you really need this line

class AdminAdvancedExportPrestaImportController extends AdminImportControllerCore
{

    public function __construct()
    {
        parent::__construct();

        if ((int) Tools::getValue('entity') ===
            $this->entities[$this->trans('Combinations', array(), 'Admin.Global')]) {
            $this->available_fields['id_product_attribute'] = array('label' => $this->trans('Product Attribute Id', array()));
        } else if ((int) Tools::getValue('entity') ===
            $this->entities[$this->trans('Products', array(), 'Admin.Global')]) {
            $this->available_fields['delete_existing_attachments'] =
                array('label' => $this->trans('Delete existing attachments (0 = No, 1 = Yes)', array()));
            $this->available_fields['attachment'] = array('label' => $this->trans('attachment', array()));
        }
    }

    protected function excelToCsvFile($filename)
    {
        if (preg_match('#(.*?)\.(csv)#is', $filename)) {
            $dest_file = self::getPath((string)(preg_replace('/\.{2,}/', '.', $filename)));
        } else {
            $csv_folder = self::getPath();
            $excel_folder = $csv_folder . 'csvfromexcel/';
            $info = pathinfo($filename);
            $csv_name = basename($filename, '.' . $info['extension']) . '.csv';
            $dest_file = $excel_folder . $csv_name;

            if (!is_dir($excel_folder)) {
                mkdir($excel_folder);
            }

            if (!is_file($dest_file)) {
                // validator error function exists
                $reader_excel = IOFactory::createReaderForFile($csv_folder . $filename);
                $reader_excel->setReadDataOnly(true);
                $excel_file = $reader_excel->load($csv_folder . $filename);
                // validator error function exists
                $csv_writer = IOFactory::createWriter($excel_file, 'Csv');

                $csv_writer->setSheetIndex(0);
                $csv_writer->setDelimiter(';');
                $csv_writer->save($dest_file);
            }
        }

        return $dest_file;
    }

    public static function getPath($file = '')
    {
        $id = Tools::getValue('id');
        // when there is no file specify it only needs path without filename
        $path = _AE_IMPORT_PATH_ . $id . '/' . $file;
        return $path;
    }

    protected function receiveTab()
    {
        $type_value = Tools::getValue('type_value') ? Tools::getValue('type_value') : array();
        foreach ($type_value as $nb => $type) {
            if ($type != 'no') {
                self::$column_mask[$type] = $nb;
            }
        }
    }

    protected function attributeImportOne($info, $default_language, &$groups, &$attributes, $regenerate, $shop_is_feature_active, $validateOnly = false)
    {
        /* start modification */
        if (isset($info['id_product_attribute']) && isset($info['id_product'])) {
            $id_product_attribute = $info['id_product_attribute'];
            $product = new Product((int) $info['id_product'], false, $default_language);
            $attribute_combinations = $product->getAttributeCombinationsById($id_product_attribute, $default_language);
            foreach ($attribute_combinations as $attribute_combination) {
                foreach ($attribute_combination as $key => $field) {
                    if (!isset($info[$key])) {
                        $info[$key] = $field;
                    }
                }
            }
        } else {
            AdminImportController::setDefaultValues($info);
        }
        /* end modification */

        if (!$shop_is_feature_active) {
            $info['shop'] = 1;
        } elseif (empty($info['shop'])) {
            $info['shop'] = implode($this->multiple_value_separator, Shop::getContextListShopID());
        }

        // Get shops for each attributes
        $info['shop'] = explode($this->multiple_value_separator, $info['shop']);

        $id_shop_list = [];
        if (is_array($info['shop'])) {
            foreach ($info['shop'] as $shop) {
                if (!empty($shop) && !is_numeric($shop)) {
                    $id_shop_list[] = Shop::getIdByName($shop);
                } elseif (!empty($shop)) {
                    $id_shop_list[] = $shop;
                }
            }
        }

        if (isset($info['id_product']) && $info['id_product']) {
            $product = new Product((int)$info['id_product'], false, $default_language);
        } elseif (Tools::getValue('match_ref') && isset($info['product_reference']) && $info['product_reference']) {
            $datas = Db::getInstance()->getRow('
				SELECT p.`id_product`
				FROM `' . _DB_PREFIX_ . 'product` p
				' . Shop::addSqlAssociation('product', 'p') . '
				WHERE p.`reference` = "' . pSQL($info['product_reference']) . '"
			', false);
            if (isset($datas['id_product']) && $datas['id_product']) {
                $product = new Product((int)$datas['id_product'], false, $default_language);
            } else {
                return;
            }
        } else {
            return;
        }

        $id_image = array();

        if (isset($info['image_url']) && $info['image_url']) {
            $info['image_url'] = explode($this->multiple_value_separator, $info['image_url']);

            if (is_array($info['image_url'])) {
                foreach ($info['image_url'] as $key => $url) {
                    $url = trim($url);
                    $product_has_images = (bool)Image::getImages($this->context->language->id, $product->id);

                    $image = new Image();
                    $image->id_product = (int)$product->id;
                    $image->position = Image::getHighestPosition($product->id) + 1;
                    $image->cover = (!$product_has_images) ? true : false;

                    if (isset($info['image_alt'])) {
                        $alt = self::split($info['image_alt']);
                        if (isset($alt[$key]) && Tools::strlen($alt[$key]) > 0) {
                            $alt = self::createMultiLangField($alt[$key]);
                            $image->legend = $alt;
                        }
                    }

                    $field_error = $image->validateFields(UNFRIENDLY_ERROR, true);
                    $lang_field_error = $image->validateFieldsLang(UNFRIENDLY_ERROR, true);

                    if ($field_error === true &&
                        $lang_field_error === true &&
                        !$validateOnly &&
                        $image->add()) {
                        $image->associateTo($id_shop_list);
                        // FIXME: 2s/image !
                        if (!AdminImportController::copyImg($product->id, $image->id, $url, 'products', !$regenerate)) {
                            $this->warnings[] = $this->trans(
                                'Error copying image: %url%',
                                array('%url%' => Tools::htmlentitiesUTF8($url)),
                                'Admin.Advparameters.Notification'
                            );
                            $image->delete();
                        } else {
                            $id_image[] = (int)$image->id;
                        }
                        // until here
                    } else {
                        if (!$validateOnly) {
                            $this->warnings[] = $this->trans(
                                '%data% cannot be saved',
                                array(
                                    '%data%' => (isset($image->id_product) ? ' (' . Tools::htmlentitiesUTF8($image->id_product) . ')' : ''),
                                ),
                                'Admin.Advparameters.Notification'
                            );
                        }
                        if ($field_error !== true || $lang_field_error !== true) {
                            $this->errors[] = ($field_error !== true ? $field_error : '')
                                . ($lang_field_error !== true ? $lang_field_error : '');
                        }
                    }
                }
            }
        } elseif (isset($info['image_position']) && $info['image_position']) {
            $info['image_position'] = explode($this->multiple_value_separator, $info['image_position']);

            if (is_array($info['image_position'])) {
                foreach ($info['image_position'] as $position) {
                    // choose images from product by position
                    $images = $product->getImages($default_language);

                    if ($images) {
                        foreach ($images as $row) {
                            if ($row['position'] == (int)$position) {
                                $id_image[] = (int)$row['id_image'];

                                break;
                            }
                        }
                    }
                    if (empty($id_image)) {
                        $this->warnings[] = sprintf(
                            $this->trans(
                                'No image was found for combination with id_product = %s and image position = %s.',
                                array(),
                                'Admin.Advparameters.Notification'
                            ),
                            Tools::htmlentitiesUTF8($product->id),
                            (int)$position
                        );
                    }
                }
            }
        }

        $id_attribute_group = 0;
        // groups
        $groups_attributes = array();
        if (isset($info['group'])) {
            foreach (explode($this->multiple_value_separator, $info['group']) as $key => $group) {
                if (empty($group)) {
                    continue;
                }
                $tab_group = explode(':', $group);
                $group = trim($tab_group[0]);
                if (!isset($tab_group[1])) {
                    $type = 'select';
                } else {
                    $type = trim($tab_group[1]);
                }

                // sets group
                $groups_attributes[$key]['group'] = $group;

                // if position is filled
                if (isset($tab_group[2])) {
                    $position = trim($tab_group[2]);
                } else {
                    $position = false;
                }

                if (!isset($groups[$group])) {
                    $obj = new AttributeGroup();
                    $obj->is_color_group = false;
                    $obj->group_type = pSQL($type);
                    $obj->name[$default_language] = $group;
                    $obj->public_name[$default_language] = $group;
                    $obj->position = (!$position) ? AttributeGroup::getHigherPosition() + 1 : $position;

                    if (($field_error = $obj->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                        ($lang_field_error = $obj->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true) {
                        // here, cannot avoid attributeGroup insertion to avoid an error during validation step.
                        //if (!$validateOnly) {
                        $obj->add();
                        $obj->associateTo($id_shop_list);
                        $groups[$group] = $obj->id;
                        //}
                    } else {
                        $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '');
                    }

                    // fills groups attributes
                    $id_attribute_group = $obj->id;
                    $groups_attributes[$key]['id'] = $id_attribute_group;
                } else {
                    // already exists

                    $id_attribute_group = $groups[$group];
                    $groups_attributes[$key]['id'] = $id_attribute_group;
                }
            }
        }

        // inits attribute
        $id_product_attribute = 0;
        $id_product_attribute_update = false;
        $attributes_to_add = array();

        // for each attribute
        if (isset($info['attribute'])) {
            foreach (explode($this->multiple_value_separator, $info['attribute']) as $key => $attribute) {
                if (empty($attribute)) {
                    continue;
                }
                $tab_attribute = explode(':', $attribute);
                $attribute = trim($tab_attribute[0]);
                // if position is filled
                if (isset($tab_attribute[1])) {
                    $position = trim($tab_attribute[1]);
                } else {
                    $position = false;
                }

                if (isset($groups_attributes[$key])) {
                    $group = $groups_attributes[$key]['group'];
                    if (!isset($attributes[$group . '_' . $attribute])) {
                        $id_attribute_group = $groups_attributes[$key]['id'];
                        $obj = new ProductAttribute();
                        // sets the proper id (corresponding to the right key)
                        $obj->id_attribute_group = $groups_attributes[$key]['id'];
                        $obj->name[$default_language] = str_replace('\n', '', str_replace('\r', '', $attribute));
                        $obj->position = (!$position && isset($groups[$group])) ? ProductAttribute::getHigherPosition($groups[$group]) + 1 : $position;

                        if (($field_error = $obj->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                            ($lang_field_error = $obj->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true) {
                            if (!$validateOnly) {
                                $obj->add();
                                $obj->associateTo($id_shop_list);
                                $attributes[$group . '_' . $attribute] = $obj->id;
                            }
                        } else {
                            $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '');
                        }
                    }

                    $info['minimal_quantity'] = isset($info['minimal_quantity']) && $info['minimal_quantity'] ? (int)$info['minimal_quantity'] : 1;
                    $info['low_stock_threshold'] = empty($info['low_stock_threshold']) && '0' != $info['low_stock_threshold'] ? null : (int)$info['low_stock_threshold'];
                    $info['low_stock_alert'] = !empty($info['low_stock_alert']);

                    $info['wholesale_price'] = str_replace(',', '.', $info['wholesale_price']);
                    $info['price'] = str_replace(',', '.', $info['price']);
                    $info['ecotax'] = str_replace(',', '.', $info['ecotax']);
                    $info['weight'] = str_replace(',', '.', $info['weight']);
                    $info['available_date'] = Validate::isDate($info['available_date']) ? $info['available_date'] : null;

                    if (!Validate::isEan13($info['ean13'])) {
                        $this->warnings[] = $this->trans(
                            'EAN13 "%ean13%" has incorrect value for product with id %id%.',
                            array(
                                '%ean13%' => Tools::htmlentitiesUTF8($info['ean13']),
                                '%id%' => Tools::htmlentitiesUTF8($product->id),
                            ),
                            'Admin.Advparameters.Notification'
                        );
                        $info['ean13'] = '';
                    }

                    if ($info['default_on'] && !$validateOnly) {
                        $product->deleteDefaultAttributes();
                    }

                    // if a reference is specified for this product, get the associate id_product_attribute to UPDATE
                    if (isset($info['reference']) && !empty($info['reference'])) {
                        $id_product_attribute = Combination::getIdByReference(
                            $product->id,
                            (string)($info['reference'])
                        );

                        // updates the attribute
                        if ($id_product_attribute && !$validateOnly) {
                            // gets all the combinations of this product
                            $attribute_combinations = $product->getAttributeCombinations($default_language);
                            foreach ($attribute_combinations as $attribute_combination) {
                                if (in_array($id_product_attribute, $attribute_combination)) {
                                    // FIXME: ~3s/declinaison
                                    $product->updateAttribute(
                                        $id_product_attribute,
                                        (float) $info['wholesale_price'],
                                        (float) $info['price'],
                                        (float) $info['weight'],
                                        0,
                                        (Configuration::get('PS_USE_ECOTAX') ? (float) $info['ecotax'] : 0),
                                        $id_image,
                                        (string) $info['reference'],
                                        (string) $info['ean13'],
                                        ((bool) $info['default_on'] ? (bool) $info['default_on'] : null),
                                        '',
                                        (string) $info['upc'],
                                        (int) $info['minimal_quantity'],
                                        $info['available_date'],
                                        false,
                                        $id_shop_list,
                                        '',
                                        $info['low_stock_threshold'],
                                        $info['low_stock_alert']
                                    );
                                    $id_product_attribute_update = true;
                                    if (isset($info['supplier_reference']) && !empty($info['supplier_reference'])) {
                                        $product->addSupplierReference($product->id_supplier, $id_product_attribute, $info['supplier_reference']);
                                    }
                                    // until here
                                }
                            }
                        }
                    }

                    // if no attribute reference is specified, creates a new one
                    if (!$id_product_attribute && !$validateOnly) {
                        $id_product_attribute = $product->addCombinationEntity(
                            (float)$info['wholesale_price'],
                            (float)$info['price'],
                            (float)$info['weight'],
                            0,
                            (Configuration::get('PS_USE_ECOTAX') ? (float)$info['ecotax'] : 0),
                            (int)$info['quantity'],
                            $id_image,
                            (string)$info['reference'],
                            0,
                            (string)$info['ean13'],
                            ((int)$info['default_on'] ? (int)$info['default_on'] : null),
                            0,
                            (string)$info['upc'],
                            (int)$info['minimal_quantity'],
                            $id_shop_list,
                            $info['available_date'],
                            '',
                            $info['low_stock_threshold'],
                            $info['low_stock_alert']
                        );

                        if (isset($info['supplier_reference']) && !empty($info['supplier_reference'])) {
                            $product->addSupplierReference(
                                $product->id_supplier,
                                $id_product_attribute,
                                $info['supplier_reference']
                            );
                        }
                    }

                    // fills our attributes array, in order to add the attributes to the product_attribute afterwards
                    if (isset($attributes[$group . '_' . $attribute])) {
                        $attributes_to_add[] = (int)$attributes[$group . '_' . $attribute];
                    }

                    // after insertion, we clean attribute position and group attribute position
                    if (!$validateOnly) {
                        $obj = new ProductAttribute();
                        $obj->cleanPositions((int)$id_attribute_group, false);
                        AttributeGroup::cleanPositions();
                    }
                }
            }
        }

        // start modification
        // if a reference is specified for this product, get the associate id_product_attribute to UPDATE
        if (isset($info['id_product_attribute']) && !empty($info['id_product_attribute']) &&
            !isset($info['attribute']) && !isset($info['group'])) {
            $id_product_attribute = $info['id_product_attribute'];

            // updates the attribute
            if ($id_product_attribute && !$validateOnly) {
                // gets all the combinations of this product
                $attribute_combinations = $product->getAttributeCombinationsById(
                    $id_product_attribute,
                    $default_language
                );
                // FIXME: ~3s/declinaison
                foreach ($attribute_combinations as $attribute_combination) {
                    // FIXME: ~3s/declinaison
                    $product->updateAttribute(
                        $id_product_attribute,
                        (float) $info['wholesale_price'],
                        (float) $info['price'],
                        (float) $info['weight'],
                        (float) $info['unit_price_impact'],
                        (float) $info['ecotax'],
                        $id_image,
                        (string) $info['reference'],
                        (string) $info['ean13'],
                        ((int) $info['default_on'] ? (int) $info['default_on'] : null),
                        $info['location'],
                        (string) $info['upc'],
                        (int) $info['minimal_quantity'],
                        $info['available_date'],
                        null,
                        $id_shop_list,
                        $info['isbn'],
                        $info['low_stock_threshold'],
                        $info['low_stock_alert'],
                        (isset($info['mpn']) ? $info['mpn'] : '')
                    );
                    $id_product_attribute_update = true;
                    if (isset($info['supplier_reference']) && !empty($info['supplier_reference'])) {
                        $product->addSupplierReference($product->id_supplier, $id_product_attribute, $info['supplier_reference']);
                    }
                    // until here
                }
            }
        }
        // end modification

        $product->checkDefaultAttributes();
        if (!$product->cache_default_attribute && !$validateOnly) {
            Product::updateDefaultAttribute($product->id);
        }
        if ($id_product_attribute) {
            if (!$validateOnly) {
                // now adds the attributes in the attribute_combination table
                if ($id_product_attribute_update) {
                    Db::getInstance()->execute('
						DELETE FROM ' . _DB_PREFIX_ . 'product_attribute_combination
						WHERE id_product_attribute = ' . (int)$id_product_attribute);
                }

                foreach ($attributes_to_add as $attribute_to_add) {
                    Db::getInstance()->execute('
						INSERT IGNORE INTO ' . _DB_PREFIX_ . 'product_attribute_combination (id_attribute, id_product_attribute)
						VALUES (' . (int)$attribute_to_add . ',' . (int)$id_product_attribute . ')', false);
                }
            }

            // set advanced stock managment
            if (isset($info['advanced_stock_management'])) {
                if ($info['advanced_stock_management'] != 1 && $info['advanced_stock_management'] != 0) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management has incorrect value. Not set for product with id %id%.',
                        array(
                            '%id%' => Tools::htmlentitiesUTF8($product->id),
                        ),
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $info['advanced_stock_management'] == 1) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, cannot enable on product with id %id%.',
                        array(
                            '%id%' => Tools::htmlentitiesUTF8($product->id),
                        ),
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!$validateOnly) {
                    $product->setAdvancedStockManagement($info['advanced_stock_management']);
                }
                // automaticly disable depends on stock, if a_s_m set to disabled
                if (!$validateOnly && StockAvailable::dependsOnStock($product->id) == 1 && $info['advanced_stock_management'] == 0) {
                    StockAvailable::setProductDependsOnStock($product->id, false, null, $id_product_attribute);
                }
            }

            // Check if warehouse exists
            if (isset($info['warehouse']) && $info['warehouse']) {
                if (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, warehouse is not set on product with id %id%.',
                        array('%id%' => Tools::htmlentitiesUTF8($product->id)),
                        'Admin.Advparameters.Notification'
                    );
                } else {
                    if (Warehouse::exists($info['warehouse'])) {
                        $warehouse_location_entity = new WarehouseProductLocation();
                        $warehouse_location_entity->id_product = $product->id;
                        $warehouse_location_entity->id_product_attribute = $id_product_attribute;
                        $warehouse_location_entity->id_warehouse = $info['warehouse'];
                        if (!$validateOnly) {
                            if (WarehouseProductLocation::getProductLocation(
                                $product->id,
                                $id_product_attribute,
                                $info['warehouse']) !== false
                            ) {
                                $warehouse_location_entity->update();
                            } else {
                                $warehouse_location_entity->save();
                            }
                            StockAvailable::synchronize($product->id);
                        }
                    } else {
                        $this->warnings[] = $this->trans(
                            'Warehouse did not exist, cannot set on product %name%.',
                            array(
                                '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language]),
                            ),
                            'Admin.Advparameters.Notification'
                        );
                    }
                }
            }

            // stock available
            if (isset($info['depends_on_stock'])) {
                if ($info['depends_on_stock'] != 0 && $info['depends_on_stock'] != 1) {
                    $this->warnings[] = $this->trans(
                        'Incorrect value for "Depends on stock" for product %name% ',
                        array('%name%' => Tools::htmlentitiesUTF8($product->name[$default_language])),
                        'Admin.Notifications.Error'
                    );
                } elseif ((!$info['advanced_stock_management'] || $info['advanced_stock_management'] == 0) && $info['depends_on_stock'] == 1) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, cannot set "Depends on stock" for product %name% ',
                        array('%name%' => Tools::htmlentitiesUTF8($product->name[$default_language])),
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!$validateOnly) {
                    StockAvailable::setProductDependsOnStock($product->id, $info['depends_on_stock'], null,
                        $id_product_attribute);
                }

                // This code allows us to set qty and disable depends on stock
                if (isset($info['quantity'])) {
                    // if depends on stock and quantity, add quantity to stock
                    if ($info['depends_on_stock'] == 1) {
                        $stock_manager = StockManagerFactory::getManager();
                        $price = str_replace(',', '.', $info['wholesale_price']);
                        if ($price == '0') {
                            $price = 0.000001;
                        }
                        $price = round((float)$price, 6);
                        $warehouse = new Warehouse($info['warehouse']);
                        if (!$validateOnly && $stock_manager->addProduct((int)$product->id, $id_product_attribute,
                                $warehouse, (int)$info['quantity'], 1, $price, true)) {
                            StockAvailable::synchronize((int)$product->id);
                        }
                    } elseif (!$validateOnly) {
                        if ($shop_is_feature_active) {
                            foreach ($id_shop_list as $shop) {
                                StockAvailable::setQuantity((int)$product->id, $id_product_attribute,
                                    (int)$info['quantity'], (int)$shop);
                            }
                        } else {
                            StockAvailable::setQuantity((int)$product->id, $id_product_attribute,
                                (int)$info['quantity'], $this->context->shop->id);
                        }
                    }
                }
            } elseif (!$validateOnly) { // if not depends_on_stock set, use normal qty
                if ($shop_is_feature_active) {
                    foreach ($id_shop_list as $shop) {
                        StockAvailable::setQuantity((int)$product->id, $id_product_attribute, (int)$info['quantity'],
                            (int)$shop);
                    }
                } else {
                    StockAvailable::setQuantity((int)$product->id, $id_product_attribute, (int)$info['quantity'],
                        $this->context->shop->id);
                }
            }

            // assign combination id to already associated product suppliers
            $productSuppliers = ProductSupplier::getSupplierCollection($product->id);
            /** @var ProductSupplier $productSupplier */
            foreach ($productSuppliers as $productSupplier) {
                // skip if related combination supplier already exists
                if ((int) $productSupplier->id_product_attribute === (int) $id_product_attribute) {
                    continue;
                }

                $combinationSupplier = clone $productSupplier;
                $combinationSupplier->id = null;
                $combinationSupplier->id_product_attribute = $id_product_attribute;
                $combinationSupplier->add();
            }
        }
    }

    protected function productImportOne($info, $default_language_id, $id_lang, $force_ids, $regenerate, $shop_is_feature_active, $shop_ids, $match_ref, &$accessories, $validateOnly = false)
    {
        if (!$force_ids) {
            unset($info['id']);
        }

        $id_product = null;
        // Use product reference as key
        if (!empty($info['id'])) {
            $id_product = (int) $info['id'];
        } elseif ($match_ref && isset($info['reference'])) {
            $idProductByRef = (int) Db::getInstance()->getValue('
                                    SELECT p.`id_product`
                                    FROM `' . _DB_PREFIX_ . 'product` p
                                    ' . Shop::addSqlAssociation('product', 'p') . '
                                    WHERE p.`reference` = "' . pSQL($info['reference']) . '"
                                ', false);
            if ($idProductByRef) {
                $id_product = $idProductByRef;
            }
        }

        $product = new Product($id_product);

        $update_advanced_stock_management_value = false;
        if (isset($product->id) && $product->id && Product::existsInDatabase((int) $product->id, 'product')) {
            $product->loadStockData();
            $update_advanced_stock_management_value = true;
            $category_data = Product::getProductCategories((int) $product->id);

            if (is_array($category_data)) {
                foreach ($category_data as $tmp) {
                    if ($product->category && is_array($product->category)) {
                        continue;
                    }
                    $product->category[] = $tmp;
                }
            }
        }

        AdminImportController::setEntityDefaultValues($product);
        AdminImportController::arrayWalk($info, ['AdminImportController', 'fillInfo'], $product);

        /** @var Product|null $product */
        if (!$product) {
            return;
        }

        if (!$shop_is_feature_active) {
            $product->shop = (int) Configuration::get('PS_SHOP_DEFAULT');
        } elseif (!isset($product->shop) || empty($product->shop)) {
            $product->shop = implode($this->multiple_value_separator, Shop::getContextListShopID());
        }

        if (!$shop_is_feature_active) {
            $product->id_shop_default = (int) Configuration::get('PS_SHOP_DEFAULT');
        } else {
            $product->id_shop_default = (int) Context::getContext()->shop->id;
        }

        // link product to shops
        foreach (explode($this->multiple_value_separator, $product->shop) as $shop) {
            if (!empty($shop) && !is_numeric($shop)) {
                $product->id_shop_list[] = Shop::getIdByName($shop);
            } elseif (!empty($shop)) {
                $product->id_shop_list[] = $shop;
            }
        }

        if ((int) $product->id_tax_rules_group != 0) {
            if (Validate::isLoadedObject(new TaxRulesGroup($product->id_tax_rules_group))) {
                $address = $this->context->shop->getAddress();
                $tax_manager = TaxManagerFactory::getManager($address, $product->id_tax_rules_group);
                $product_tax_calculator = $tax_manager->getTaxCalculator();
                $product->tax_rate = $product_tax_calculator->getTotalRate();
            } else {
                $this->addProductWarning(
                    'id_tax_rules_group',
                    $product->id_tax_rules_group,
                    $this->trans('Unknown tax rule group ID. You need to create a group with this ID first.', [], 'Admin.Advparameters.Notification')
                );
            }
        }
        if (isset($product->manufacturer) && is_numeric($product->manufacturer) && Manufacturer::manufacturerExists((int) $product->manufacturer)) {
            $product->id_manufacturer = (int) $product->manufacturer;
        } elseif (isset($product->manufacturer) && is_string($product->manufacturer) && !empty($product->manufacturer)) {
            if ($manufacturer = Manufacturer::getIdByName($product->manufacturer)) {
                $product->id_manufacturer = (int) $manufacturer;
            } else {
                $manufacturer = new Manufacturer();
                $manufacturer->name = $product->manufacturer;
                $manufacturer->active = true;
                if (($field_error = $manufacturer->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                    ($lang_field_error = $manufacturer->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true &&
                    !$validateOnly && // Do not move this condition: previous tests should be played always, but next ->add() test should not be played in validateOnly mode
                    $manufacturer->add()) {
                    $product->id_manufacturer = (int) $manufacturer->id;
                    $manufacturer->associateTo($product->id_shop_list);
                } else {
                    if (!$validateOnly) {
                        $this->errors[] = sprintf(
                            $this->trans('%1$s (ID: %2$s) cannot be saved', [], 'Admin.Advparameters.Notification'),
                            Tools::htmlentitiesUTF8($manufacturer->name),
                            !empty($manufacturer->id) ? $manufacturer->id : 'null'
                        );
                    }
                    if ($field_error !== true || isset($lang_field_error) && $lang_field_error !== true) {
                        $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '') .
                            Db::getInstance()->getMsgError();
                    }
                }
            }
        }

        if (isset($product->supplier) && is_numeric($product->supplier) && Supplier::supplierExists((int) $product->supplier)) {
            $product->id_supplier = (int) $product->supplier;
        } elseif (isset($product->supplier) && is_string($product->supplier) && !empty($product->supplier)) {
            if ($supplier = Supplier::getIdByName($product->supplier)) {
                $product->id_supplier = (int) $supplier;
            } else {
                $supplier = new Supplier();
                $supplier->name = $product->supplier;
                $supplier->active = true;

                if (($field_error = $supplier->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                    ($lang_field_error = $supplier->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true &&
                    !$validateOnly &&  // Do not move this condition: previous tests should be played always, but next ->add() test should not be played in validateOnly mode
                    $supplier->add()) {
                    $product->id_supplier = (int) $supplier->id;
                    $supplier->associateTo($product->id_shop_list);
                } else {
                    if (!$validateOnly) {
                        $this->errors[] = sprintf(
                            $this->trans('%1$s (ID: %2$s) cannot be saved', [], 'Admin.Advparameters.Notification'),
                            Tools::htmlentitiesUTF8($supplier->name),
                            !empty($supplier->id) ? Tools::htmlentitiesUTF8($supplier->id) : 'null'
                        );
                    }
                    if ($field_error !== true || isset($lang_field_error) && $lang_field_error !== true) {
                        $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '') .
                            Db::getInstance()->getMsgError();
                    }
                }
            }
        }

        if (isset($product->price_tex) && !isset($product->price_tin)) {
            $product->price = $product->price_tex;
        } elseif (isset($product->price_tin) && !isset($product->price_tex)) {
            $product->price = $product->price_tin;
            // If a tax is already included in price, withdraw it from price
            if ($product->tax_rate) {
                $product->price = (float) number_format($product->price / (1 + $product->tax_rate / 100), 6, '.', '');
            }
        } elseif (isset($product->price_tin, $product->price_tex)) {
            $product->price = $product->price_tex;
        }

        if (!Configuration::get('PS_USE_ECOTAX')) {
            $product->ecotax = 0;
        }

        if (!empty($product->category) && is_array($product->category)) {
            $product->id_category = []; // Reset default values array
            foreach ($product->category as $value) {
                if (is_numeric($value)) {
                    if (Category::categoryExists((int) $value)) {
                        $product->id_category[] = (int) $value;
                    } else {
                        $category_to_create = new Category();
                        $category_to_create->id = (int) $value;
                        $category_to_create->name = AdminImportController::createMultiLangField($value);
                        $category_to_create->active = true;
                        $category_to_create->id_parent = (int) Configuration::get('PS_HOME_CATEGORY'); // Default parent is home for unknown category to create
                        $category_link_rewrite = Tools::link_rewrite($category_to_create->name[$default_language_id]);
                        $category_to_create->link_rewrite = AdminImportController::createMultiLangField($category_link_rewrite);
                        if (($field_error = $category_to_create->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                            ($lang_field_error = $category_to_create->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true &&
                            !$validateOnly &&  // Do not move this condition: previous tests should be played always, but next ->add() test should not be played in validateOnly mode
                            $category_to_create->add()) {
                            $product->id_category[] = (int) $category_to_create->id;
                        } else {
                            if (!$validateOnly) {
                                $this->errors[] = sprintf(
                                    $this->trans('%1$s (ID: %2$s) cannot be saved', [], 'Admin.Advparameters.Notification'),
                                    Tools::htmlentitiesUTF8($category_to_create->name[$default_language_id]),
                                    !empty($category_to_create->id) ? Tools::htmlentitiesUTF8($category_to_create->id) : 'null'
                                );
                            }
                            if ($field_error !== true || isset($lang_field_error) && $lang_field_error !== true) {
                                $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '') .
                                    Db::getInstance()->getMsgError();
                            }
                        }
                    }
                } elseif (!$validateOnly && is_string($value) && !empty($value)) {
                    $category = Category::searchByPath($default_language_id, trim($value), $this, 'productImportCreateCat');
                    if ($category['id_category']) {
                        $product->id_category[] = (int) $category['id_category'];
                    } else {
                        $this->errors[] = $this->trans(
                            '%data% cannot be saved',
                            [
                                '%data%' => Tools::htmlentitiesUTF8(trim($value)),
                            ],
                            'Admin.Advparameters.Notification'
                        );
                    }
                }
            }

            $product->id_category = array_values(array_unique($product->id_category));
        }

        // Category default now takes the value of the first new category during import
        if (isset($product->id_category[0])) {
            if (empty($product->id_category_default) || !in_array($product->id_category_default, $product->id_category)) {
                $product->id_category_default = (int) $product->id_category[0];
            }
        } else {
            if (empty($product->id_category_default)) {
                $defaultProductShop = new Shop($product->id_shop_default);
                $product->id_category_default = Category::getRootCategory(null, Validate::isLoadedObject($defaultProductShop) ? $defaultProductShop : null)->id;
            }
        }

        $link_rewrite = (is_array($product->link_rewrite) && isset($product->link_rewrite[$id_lang])) ? trim($product->link_rewrite[$id_lang]) : '';
        $valid_link = Validate::isLinkRewrite($link_rewrite);
        if ((isset($product->link_rewrite[$id_lang]) && empty($product->link_rewrite[$id_lang])) || !$valid_link) {
            $link_rewrite = Tools::link_rewrite($product->name[$id_lang]);
            if ($link_rewrite == '') {
                $link_rewrite = 'friendly-url-autogeneration-failed';
            }
        }

        if (!$valid_link) {
            $this->informations[] = $this->trans(
                'Rewrite link for %1$s (ID %2$s): re-written as %3$s.',
                [
                    '%1$s' => Tools::htmlentitiesUTF8($product->name[$id_lang]),
                    '%2$s' => !empty($info['id']) ? Tools::htmlentitiesUTF8($info['id']) : 'null',
                    '%3$s' => Tools::htmlentitiesUTF8($link_rewrite),
                ],
                'Admin.Advparameters.Notification'
            );
        }

        if (!$valid_link || !(is_array($product->link_rewrite) && count($product->link_rewrite))) {
            $product->link_rewrite = AdminImportController::createMultiLangField($link_rewrite);
        } else {
            $product->link_rewrite[(int) $id_lang] = $link_rewrite;
        }

        // replace the value of separator by coma
        if ($this->multiple_value_separator != ',') {
            if (is_array($product->meta_keywords)) {
                foreach ($product->meta_keywords as &$meta_keyword) {
                    if (!empty($meta_keyword)) {
                        $meta_keyword = str_replace($this->multiple_value_separator, ',', $meta_keyword);
                    }
                }
            }
        }

        // Convert comma into dot for all floating values
        foreach (Product::$definition['fields'] as $key => $array) {
            if ($array['type'] == Product::TYPE_FLOAT) {
                $product->{$key} = str_replace(',', '.', $product->{$key});
            }
        }

        // Indexation is already 0 if it's a new product, but not if it's an update
        $product->indexed = false;
        $productExistsInDatabase = false;

        if ($product->id && Product::existsInDatabase((int) $product->id, 'product')) {
            $productExistsInDatabase = true;
        }

        if (($match_ref && $product->reference && $product->existsRefInDatabase($product->reference)) || $productExistsInDatabase) {
            $product->date_upd = date('Y-m-d H:i:s');
        }

        $res = false;
        $field_error = $product->validateFields(UNFRIENDLY_ERROR, true);
        $lang_field_error = $product->validateFieldsLang(UNFRIENDLY_ERROR, true);
        if ($field_error === true && $lang_field_error === true) {
            // check quantity
            if ($product->quantity == null) {
                $product->quantity = 0;
            }

            // If match ref is specified && ref product && ref product already in base, trying to update
            if ($match_ref && $product->reference && $product->existsRefInDatabase($product->reference)) {
                $datas = Db::getInstance()->getRow('
					SELECT product_shop.`date_add`, p.`id_product`
					FROM `' . _DB_PREFIX_ . 'product` p
					' . Shop::addSqlAssociation('product', 'p') . '
					WHERE p.`reference` = "' . pSQL($product->reference) . '"
				', false);
                $product->id = (int) $datas['id_product'];
                $product->date_add = pSQL($datas['date_add']);
                $res = ($validateOnly || $product->update());
            } // Else If id product && id product already in base, trying to update
            elseif ($productExistsInDatabase) {
                $datas = Db::getInstance()->getRow('
					SELECT product_shop.`date_add`
					FROM `' . _DB_PREFIX_ . 'product` p
					' . Shop::addSqlAssociation('product', 'p') . '
					WHERE p.`id_product` = ' . (int) $product->id, false);
                $product->date_add = pSQL($datas['date_add']);
                $res = ($validateOnly || $product->update());
            }
            // If no id_product or update failed
            $product->force_id = (bool) $force_ids;

            if (!$res) {
                if ($product->date_add != '') {
                    $res = ($validateOnly || $product->add(false));
                } else {
                    $res = ($validateOnly || $product->add());
                }
            }

            if (!$validateOnly) {
                if ($product->getType() == Product::PTYPE_VIRTUAL) {
                    StockAvailable::setProductOutOfStock((int) $product->id, 1);
                } else {
                    StockAvailable::setProductOutOfStock((int) $product->id, (int) $product->out_of_stock);
                }

                if ($product_download_id = ProductDownload::getIdFromIdProduct((int) $product->id)) {
                    $product_download = new ProductDownload($product_download_id);
                    $product_download->delete(true);
                }

                if ($product->getType() == Product::PTYPE_VIRTUAL) {
                    $product_download = new ProductDownload();
                    $product_download->filename = ProductDownload::getNewFilename();
                    Tools::copy($info['file_url'], _PS_DOWNLOAD_DIR_ . $product_download->filename);
                    $product_download->id_product = (int) $product->id;
                    $product_download->nb_downloadable = (int) $info['nb_downloadable'];
                    $product_download->date_expiration = $info['date_expiration'];
                    $product_download->nb_days_accessible = (int) $info['nb_days_accessible'];
                    $product_download->display_filename = basename($info['file_url']);
                    $product_download->add();
                }
            }
        }

        $shops = [];
        $product_shop = explode($this->multiple_value_separator, $product->shop);
        foreach ($product_shop as $shop) {
            if (empty($shop)) {
                continue;
            }
            $shop = trim($shop);
            if (!empty($shop) && !is_numeric($shop)) {
                $shop = Shop::getIdByName($shop);
            }

            if (in_array($shop, $shop_ids)) {
                $shops[] = $shop;
            } else {
                $this->addProductWarning(Tools::safeOutput($info['name']), $product->id, $this->trans('Shop is not valid', [], 'Admin.Advparameters.Notification'));
            }
        }
        if (empty($shops)) {
            $shops = Shop::getContextListShopID();
        }
        // If both failed, mysql error
        if (!$res) {
            $this->errors[] = sprintf(
                $this->trans('%1$s (ID: %2$s) cannot be saved', [], 'Admin.Advparameters.Notification'),
                !empty($info['name']) ? Tools::safeOutput($info['name']) : 'No Name',
                !empty($info['id']) ? Tools::safeOutput($info['id']) : 'No ID'
            );
            $this->errors[] = ($field_error !== true ? $field_error : '') . ($lang_field_error !== true ? $lang_field_error : '') .
                Db::getInstance()->getMsgError();
        } else {
            // Product supplier
            if (!$validateOnly && !empty($product->id) && property_exists($product, 'supplier_reference') && !empty($product->id_supplier)) {
                $id_product_supplier = (int) ProductSupplier::getIdByProductAndSupplier((int) $product->id, 0, (int) $product->id_supplier);
                if ($id_product_supplier) {
                    $product_supplier = new ProductSupplier($id_product_supplier);
                } else {
                    $product_supplier = new ProductSupplier();
                }
                $product_supplier->id_product = (int) $product->id;
                $product_supplier->id_product_attribute = 0;
                $product_supplier->id_supplier = (int) $product->id_supplier;
                $product_supplier->product_supplier_price_te = $product->wholesale_price;
                $product_supplier->product_supplier_reference = $product->supplier_reference;
                $product_supplier->id_currency = Currency::getDefaultCurrency()->id;
                $product_supplier->save();
            }

            // SpecificPrice (only the basic reduction feature is supported by the import)
            if (!$shop_is_feature_active) {
                $info['shop'] = 1;
            } elseif (!isset($info['shop']) || empty($info['shop'])) {
                $info['shop'] = implode($this->multiple_value_separator, Shop::getContextListShopID());
            }

            // Get shops for each attributes
            $info['shop'] = explode($this->multiple_value_separator, $info['shop']);

            $id_shop_list = [];
            foreach ($info['shop'] as $shop) {
                if (!empty($shop) && !is_numeric($shop)) {
                    $id_shop_list[] = (int) Shop::getIdByName($shop);
                } elseif (!empty($shop)) {
                    $id_shop_list[] = $shop;
                }
            }

            if ((isset($info['reduction_price']) && $info['reduction_price'] > 0) || (isset($info['reduction_percent']) && $info['reduction_percent'] > 0)) {
                foreach ($id_shop_list as $id_shop) {
                    $specific_price = SpecificPrice::getSpecificPrice($product->id, $id_shop, 0, 0, 0, 1, 0, 0, 0, 0);

                    if (is_array($specific_price) && isset($specific_price['id_specific_price'])) {
                        $specific_price = new SpecificPrice((int) $specific_price['id_specific_price']);
                    } else {
                        $specific_price = new SpecificPrice();
                    }
                    $specific_price->id_product = (int) $product->id;
                    $specific_price->id_specific_price_rule = 0;
                    $specific_price->id_shop = $id_shop;
                    $specific_price->id_currency = 0;
                    $specific_price->id_country = 0;
                    $specific_price->id_group = 0;
                    $specific_price->price = -1;
                    $specific_price->id_customer = 0;
                    $specific_price->from_quantity = 1;
                    $specific_price->reduction = (isset($info['reduction_price']) && $info['reduction_price']) ? $info['reduction_price'] : $info['reduction_percent'] / 100;
                    $specific_price->reduction_type = (isset($info['reduction_price']) && $info['reduction_price']) ? 'amount' : 'percentage';
                    $specific_price->from = (isset($info['reduction_from']) && Validate::isDate($info['reduction_from'])) ? $info['reduction_from'] : '0000-00-00 00:00:00';
                    $specific_price->to = (isset($info['reduction_to']) && Validate::isDate($info['reduction_to'])) ? $info['reduction_to'] : '0000-00-00 00:00:00';
                    if (!$validateOnly && !$specific_price->save()) {
                        $this->addProductWarning(Tools::safeOutput($info['name']), $product->id, $this->trans('Discount is invalid', [], 'Admin.Advparameters.Notification'));
                    }
                }
            }

            if (!$validateOnly && !empty($product->tags)) {
                if (isset($product->id) && $product->id) {
                    $tags = Tag::getProductTags($product->id);
                    if (is_array($tags) && count($tags)) {
                        /** @phpstan-ignore-next-line $product->tags is filled with a string at line 1986 */
                        $productTags = explode($this->multiple_value_separator, $product->tags);
                        foreach ($productTags as $key => $tag) {
                            if (!empty($tag)) {
                                $productTags[$key] = trim($tag);
                            }
                        }
                        $tags[$id_lang] = $productTags;
                        $product->tags = $tags;
                    }
                }
                // Delete tags for this id product, for no duplicating error
                Tag::deleteTagsForProduct($product->id);
                if (!is_array($product->tags) && !empty($product->tags)) {
                    $product->tags = AdminImportController::createMultiLangField($product->tags);
                    foreach ($product->tags as $key => $tags) {
                        $is_tag_added = Tag::addTags($key, $product->id, $tags, $this->multiple_value_separator);
                        if (!$is_tag_added) {
                            $this->addProductWarning(Tools::safeOutput($info['name']), $product->id, $this->trans('Tags list is invalid', [], 'Admin.Advparameters.Notification'));

                            break;
                        }
                    }
                } else {
                    foreach ($product->tags as $key => $tags) {
                        $str = '';
                        foreach ($tags as $one_tag) {
                            $str .= $one_tag . $this->multiple_value_separator;
                        }
                        $str = rtrim($str, $this->multiple_value_separator);

                        $is_tag_added = Tag::addTags($key, $product->id, $str, $this->multiple_value_separator);
                        if (!$is_tag_added) {
                            $this->addProductWarning(Tools::safeOutput($info['name']), (int) $product->id, $this->trans(
                                'Invalid tag(s) (%s)',
                                [$str],
                                'Admin.Notifications.Error'
                            ));

                            break;
                        }
                    }
                }
            }

            //delete existing images if "delete_existing_images" is set to 1
            if (!$validateOnly && isset($product->delete_existing_images)) {
                if ((bool) $product->delete_existing_images) {
                    $product->deleteImages();
                }
            }

            if (!$validateOnly && isset($product->image) && is_array($product->image) && count($product->image)) {
                $product_has_images = (bool) Image::getImages($this->context->language->id, (int) $product->id);
                foreach ($product->image as $key => $url) {
                    $url = trim($url);
                    $error = false;
                    if (!empty($url)) {
                        $url = str_replace(' ', '%20', $url);

                        $image = new Image();
                        $image->id_product = (int) $product->id;
                        $image->position = Image::getHighestPosition($product->id) + 1;
                        $image->cover = (!$key && !$product_has_images) ? true : false;
                        $alt = $product->image_alt[$key];
                        if (strlen($alt) > 0) {
                            $image->legend = self::createMultiLangField($alt);
                        }
                        // file_exists doesn't work with HTTP protocol
                        if (($field_error = $image->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                            ($lang_field_error = $image->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true && $image->add()) {
                            // associate image to selected shops
                            $image->associateTo($shops);
                            if (!AdminImportController::copyImg($product->id, $image->id, $url, 'products', !$regenerate)) {
                                $image->delete();
                                $this->warnings[] = $this->trans('Error copying image: %url%', ['%url%' => $url], 'Admin.Advparameters.Notification');
                            }
                        } else {
                            $error = true;
                        }
                    } else {
                        $error = true;
                    }

                    if ($error) {
                        $this->warnings[] = $this->trans(
                            'Product #%id%: the picture (%url%) cannot be saved.', [
                            '%id%' => Tools::htmlentitiesUTF8(isset($image) ? $image->id_product : ''),
                            '%url%' => Tools::htmlentitiesUTF8($url),
                        ],
                            'Admin.Advparameters.Notification'
                        );
                    }
                }
            }

            if (!$validateOnly && isset($product->id_category) && is_array($product->id_category)) {
                $product->updateCategories(array_map('intval', $product->id_category));
            }

            $product->checkDefaultAttributes();
            if (!$validateOnly && !$product->cache_default_attribute) {
                Product::updateDefaultAttribute($product->id);
            }

            // Features import
            $features = get_object_vars($product);

            if (!$validateOnly && isset($features['features']) && !empty($features['features'])) {
                foreach (explode($this->multiple_value_separator, $features['features']) as $single_feature) {
                    if (empty($single_feature)) {
                        continue;
                    }
                    $tab_feature = explode(':', $single_feature);
                    $feature_name = isset($tab_feature[0]) ? trim($tab_feature[0]) : '';
                    $feature_value = isset($tab_feature[1]) ? trim($tab_feature[1]) : '';
                    $position = isset($tab_feature[2]) ? (int) $tab_feature[2] - 1 : false;
                    $custom = isset($tab_feature[3]) ? (int) $tab_feature[3] : false;
                    if (!empty($feature_name) && !empty($feature_value)) {
                        $id_feature = (int) Feature::addFeatureImport($feature_name, $position);
                        $id_product = null;
                        if ($force_ids || $match_ref) {
                            $id_product = (int) $product->id;
                        }
                        $id_feature_value = (int) FeatureValue::addFeatureValueImport($id_feature, $feature_value, $id_product, $id_lang, $custom);
                        Product::addFeatureProductImport($product->id, $id_feature, $id_feature_value);
                    }
                }
            }
            // clean feature positions to avoid conflict
            Feature::cleanPositions();

            // set advanced stock managment
            if (!$validateOnly) {
                /* @phpstan-ignore-next-line Data from the property `advanced_stock_management` come from the database */
                if ($product->advanced_stock_management != 1 && $product->advanced_stock_management != 0) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management has incorrect value. Not set for product %name%',
                        [
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language_id]),
                        ],
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $product->advanced_stock_management == 1) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, cannot enable on product %name%',
                        [
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language_id]),
                        ],
                        'Admin.Advparameters.Notification'
                    );
                } elseif ($update_advanced_stock_management_value) {
                    $product->setAdvancedStockManagement($product->advanced_stock_management);
                }
                // automaticly disable depends on stock, if a_s_m set to disabled
                if (StockAvailable::dependsOnStock($product->id) == 1 && $product->advanced_stock_management == 0) {
                    StockAvailable::setProductDependsOnStock($product->id, false);
                }
            }

            // Check if warehouse exists
            if (isset($product->warehouse) && $product->warehouse) {
                if (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, warehouse not set on product %name%',
                        [
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language_id]),
                        ],
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!$validateOnly) {
                    if (Warehouse::exists($product->warehouse)) {
                        // Get already associated warehouses
                        $associated_warehouses_collection = WarehouseProductLocation::getCollection($product->id);
                        // Delete any entry in warehouse for this product
                        foreach ($associated_warehouses_collection as $awc) {
                            $awc->delete();
                        }
                        $warehouse_location_entity = new WarehouseProductLocation();
                        $warehouse_location_entity->id_product = $product->id;
                        $warehouse_location_entity->id_product_attribute = 0;
                        $warehouse_location_entity->id_warehouse = $product->warehouse;
                        if (WarehouseProductLocation::getProductLocation($product->id, 0, $product->warehouse) !== false) {
                            $warehouse_location_entity->update();
                        } else {
                            $warehouse_location_entity->save();
                        }
                        StockAvailable::synchronize($product->id);
                    } else {
                        $this->warnings[] = $this->trans(
                            'Warehouse did not exist, cannot set on product %name%.',
                            [
                                '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language_id]),
                            ],
                            'Admin.Advparameters.Notification'
                        );
                    }
                }
            }

            // stock available
            if (isset($product->depends_on_stock)) {
                /* @phpstan-ignore-next-line Data from the property `depends_on_stock` come from the database */
                if ($product->depends_on_stock != 0 && $product->depends_on_stock != 1) {
                    $this->warnings[] = $this->trans(
                        'Incorrect value for "Depends on stock" for product %name%',
                        [
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language_id]),
                        ],
                        'Admin.Advparameters.Notification'
                    );
                    /* @phpstan-ignore-next-line Data from properties `advanced_stock_management` & `depends_on_stock` come from the database */
                } elseif ((!$product->advanced_stock_management || $product->advanced_stock_management == 0) && $product->depends_on_stock == 1) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, cannot set "Depends on stock" for product %name%',
                        [
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language_id]),
                        ],
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!$validateOnly) {
                    StockAvailable::setProductDependsOnStock($product->id, $product->depends_on_stock);
                }

                // This code allows us to set qty and disable depends on stock
                if (!$validateOnly) {
                    // if depends on stock and quantity, add quantity to stock
                    if ($product->depends_on_stock == 1) {
                        $stock_manager = StockManagerFactory::getManager();
                        $price = str_replace(',', '.', (string) $product->wholesale_price);
                        if ($price == '0') {
                            $price = 0.000001;
                        }
                        $price = round((float) $price, 6);
                        $warehouse = new Warehouse($product->warehouse);
                        if ($stock_manager->addProduct((int) $product->id, 0, $warehouse, (int) $product->quantity, 1, $price, true)) {
                            StockAvailable::synchronize((int) $product->id);
                        }
                    } else {
                        if ($shop_is_feature_active) {
                            foreach ($shops as $shop) {
                                StockAvailable::setQuantity((int) $product->id, 0, (int) $product->quantity, (int) $shop);
                            }
                        } else {
                            StockAvailable::setQuantity((int) $product->id, 0, (int) $product->quantity, (int) $this->context->shop->id);
                        }
                    }
                }
            } elseif (!$validateOnly) {
                // if not depends_on_stock set, use normal qty
                if ($shop_is_feature_active) {
                    foreach ($shops as $shop) {
                        StockAvailable::setQuantity((int) $product->id, 0, (int) $product->quantity, (int) $shop);
                    }
                } else {
                    StockAvailable::setQuantity((int) $product->id, 0, (int) $product->quantity, (int) $this->context->shop->id);
                }
            }

            // modification 13.01.2023
            // import attachments
            if (!$validateOnly && isset($product->delete_existing_attachments)
                && (bool)$product->delete_existing_attachments) {
                $product->deleteAttachments();
            }

            $attachments = get_object_vars($product);

            if (!$validateOnly && isset($attachments['attachment']) && !empty($attachments['attachment'])) {
                foreach (explode($this->multiple_value_separator, $attachments['attachment']) as $attachment_string) {
                    $attachment = explode('|', $attachment_string);
                    $attachment_filename = isset($attachment[0]) ? $attachment[0] : '';
                    $attachment_name = isset($attachment[1]) ? trim($attachment[1]) : $attachment_filename;
                    $attachment_description = isset($attachment[2]) ? trim($attachment[2]) : '';

                    if (!empty($attachment_filename)) {
                        self::addAttachment($attachment_filename, $attachment_name,
                            $attachment_description, $product->id);
                    }
                }
            }
            //end of modification import accessories

            // Accessories linkage
            if (isset($product->accessories) && !$validateOnly && is_array($product->accessories) && count($product->accessories)) {
                $accessories[$product->id] = $product->accessories;
            }
        }
    }

    public static function addAttachment($filename, $name, $description, $id_product)
    {
        $attachment = new Attachment();
        $languages = Language::getLanguages();
        foreach ($languages as $language) {
            $attachment->name[$language['id_lang']] = $name;
            $attachment->description[$language['id_lang']] = $description;
        }
        $uniq_id = sha1(microtime());
        $attachment->file = sha1($uniq_id);
        $attachment->file_name = $filename;
        $path_file = _PS_DOWNLOAD_DIR_ . $filename;
        $attachment->file_size = filesize($path_file);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $attachment->mime = finfo_file($finfo, $path_file);

        $attachment->add();
        $attachment->attachProduct($id_product);
    }
}
