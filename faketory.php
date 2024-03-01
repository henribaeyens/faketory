<?php
/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT Locense
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
class Faketory extends Module
{
    /**
     * @var string
     */
    private $output;

    /**
     * @var bool
     */
    private $ps_version;

    /**
     * @var string
     */
    private $logo_path;

    /**
     * @var string
     */
    private $module_path;

    public function __construct()
    {
        $this->name = 'faketory';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Henri Baeyens';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->output = '';

        $this->displayName = $this->l('Fake User Data');
        $this->description = $this->l('Anonymizes user data in your developement database.');
        $this->ps_version = (bool) version_compare(_PS_VERSION_, '1.7', '>=');

       // $this->logo_path = $this->_path . 'logo.png';
        $this->module_path = $this->_path;

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    /**
     * install()
     *
     * @return bool
     *
     * @throws PrestaShopException
     */
    public function install()
    {
        if (parent::install()) {
            return true;
        } else {
            $this->_errors[] = $this->l('There was an error during the uninstallation. Please contact us through Addons website.');
            return false;
        }
    }

    /**
     * uninstall()
     *
     * @return bool
     */
    public function uninstall()
    {
        if (parent::uninstall()) {
            return true;
        } else {
            $this->_errors[] = $this->l('There was an error on module uninstall. Please contact us through Addons website');
            return false;
        }
    }
}
