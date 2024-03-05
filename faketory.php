<?php
/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
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

        $this->displayName = $this->l('Faketory');
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
        if (parent::install() && $this->registerHook('actionAdminControllerSetMedia')) {
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

    public function getContent()
    {
        return $this->displayForm();
    }

    public function displayForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Anonymization'),
                'icon' => 'icon-user-secret'
            ],
            'warning' => $this->l('WARNING: Do not run this action in a production environment!'),
            'input' => [
                [
					'type' => 'switch',
                    'label' => $this->l('I understand'),
                    'name' => 'acceptWarning',
					'is_bool' => true,
					'values' => [
						[
							'id' => 'acceptWarning_on',
							'value' => 1,
							'label' => '',
						],
						[
							'id' => 'acceptWarning_off',
							'value' => 0,
							'label' => '',
                        ]
                    ]
                ],
            ],
            'submit' => [
                'title' => $this->l('Anonymize User Data'),
                'class' => 'btn btn-default pull-right'
            ]
    	];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'back' => [
                'href' => AdminController::$currentIndex.'&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to modules list')
            ]
        ];
        $helper->fields_value['acceptWarning'] = 0;

        return $helper->generateForm($fields_form);
    }

    public function hookActionAdminControllerSetMedia()
    {
		if (get_class($this->context->controller) == 'AdminModulesController' && Tools::getValue('configure') == $this->name)
		{
            $this->context->controller->addJS('/modules/faketory/js/faketory.js?' . $this->version);
            Media::addJsDef([
                'faketoryController' => $this->context->link->getAdminLink('FaketoryController', true, ['route' => 'faketory_anomymize'])
            ]);
		}
    }

}
