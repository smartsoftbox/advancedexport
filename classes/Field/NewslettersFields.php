<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

include_once 'BaseFields.php';

class NewslettersFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'Email',
            'field' => 'email',
            'database' => 'newsletter',
            'group15' => NewsletterGroup::INFORMATION
        ),
        array(
            'name' => 'Date add',
            'field' => 'newsletter_date_add',
            'database' => 'newsletter',
            'group15' => NewsletterGroup::INFORMATION
        ),
        array(
            'name' => 'Ip',
            'field' => 'ip_registration_newsletter',
            'database' => 'newsletter',
            'group15' => NewsletterGroup::INFORMATION
        ),
        array(
            'name' => 'Referer',
            'field' => 'http_referer',
            'database' => 'newsletter',
            'group15' => NewsletterGroup::INFORMATION
        ),
        array(
            'name' => 'Active',
            'field' => 'active',
            'database' => 'newsletter',
            'group15' => NewsletterGroup::INFORMATION
        )
    );
}
