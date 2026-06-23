<?php
/**
*	Module Name: Social Buttons Pro
*
*	Module URI: Please contact with info@megventure.com
*	Description: A professional social sharing module for Prestashop platforms
*	Version: 2.0.0
*
*  @author    MEG Venture <info@megventure.com>
*  @copyright 2007-2016 MEG Venture
*  @license   For Prestashop--> http://opensource.org/licenses/osl-3.2.php  Open Software License (OSL 3.2)
*
*	This program is not a free software: you can't redistribute it and/or modify
*	it. All rights reserved to MEG Venture.
*
*	This copyright notice  and licence should be retained in all modules based on this framework.
*	This does not affect your rights to assert copyright over your own original work. 
*	However, the socialite coding belongs to David Bushell @dbushell and is maintained under MIT license.
*/

if (!defined('_PS_VERSION_'))
	exit;

class Socialpower extends Module
{
	public $twitteruname;
	public function __construct()
	{
		$this->name = 'socialpower';
		$this->tab = 'front_office_features';
		$this->version = '2.0.0';
		$this->bootstrap = true;
		$this->author = 'MEG Venture';
		$this->need_instance = 0;
		$this->module_key = 'a428eb0bed4543e61e8c8ddfc45122e7';

		parent::__construct();

		$this->displayName = $this->l('Social Buttons Pro');
		$this->description = $this->l('A professional social sharing module for Prestashop platforms.');

		/* Backward compatibility */
		require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');

		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/classes/SocialpowerClass.php';
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('productfooter') || !$this->registerHook('header'))
			return false;

		$res = Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'.pSQL(_DB_PREFIX_).'socialpower` (
			`id_socialpower` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,
			`template` varchar(10) NOT NULL,
			`fb_enable` varchar(10) NOT NULL,			
			`fb_data_layout` varchar(30) NOT NULL,
			`fb_data_width` varchar(4) NOT NULL,
			`fb_data_show_faces` varchar(5) NOT NULL,
			`fb_data_font` varchar(10) NOT NULL,
			`fb_data_action` varchar(10) NOT NULL,
			`tw_enable` varchar(10) NOT NULL,
			`tw_data_count` varchar(20) NOT NULL,
			`tw_width` varchar(4) NOT NULL,			
			`in_enable` varchar(10) NOT NULL,
			`in_data_count` varchar(20) NOT NULL,	
			`gp_enable` varchar(10) NOT NULL,
			`gp_data_size` varchar(20) NOT NULL,
			`gp_data_annotation` varchar(10) NOT NULL,
			`gp_data_width` varchar(10) NOT NULL,
			`phone` varchar(255) NOT NULL,
			PRIMARY KEY (`id_socialpower`))
			ENGINE='.pSQL(_MYSQL_ENGINE_).' DEFAULT CHARSET=utf8');

		if ($res)
			$res &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'.pSQL(_DB_PREFIX_).'socialpower_lang` (
				`id_socialpower` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`tw_data_via` varchar(255) NOT NULL,			
				PRIMARY KEY (`id_socialpower`, `id_lang`))
				ENGINE='.pSQL(_MYSQL_ENGINE_).' DEFAULT CHARSET=utf8');

		if ($res)
			foreach (Shop::getShops(false) as $shop)
				$res &= $this->createExampleSocialpower($shop['id_shop']);

			if (!$res)
				$res &= $this->uninstall();

			return $res;
	}

	public function uninstall()
	{
		$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_).'socialpower`');
		$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_).'socialpower_lang`');

		if (!$res || !parent::uninstall())
			return false;

		return true;
	}

	private function createExampleSocialpower($id_shop)
	{
		$socialpower = new SocialpowerClass();
		$socialpower->id_shop = (int)$id_shop;
		$socialpower->template = '1';
		$socialpower->fb_enable = 'true';
		$socialpower->fb_data_layout = 'box_count';
		$socialpower->fb_data_width = '90';
		$socialpower->fb_data_show_faces = 'true';
		$socialpower->fb_data_font = 'arial';
		$socialpower->fb_data_action = 'both';
		$socialpower->tw_enable = 'true';
		$socialpower->tw_data_count = 'vertical';
		$socialpower->in_enable = 'true';
		$socialpower->in_data_count = 'top';
		$socialpower->gp_enable = 'true';
		$socialpower->gp_data_size = 'tall';
		$socialpower->gp_data_annotation = 'bubble';
		$socialpower->gp_data_width = '52';
		$socialpower->tw_width = '60';
		foreach (Language::getLanguages(false) as $lang)
			$socialpower->tw_data_via[$lang['id_lang']] = 'Prestashop';

		return $socialpower->add();
	}

	private function settemplate1($id_shop)
	{
		$socialpower = new SocialpowerClass();
		$socialpower->id_shop = (int)$id_shop;
		$socialpower->template = '1';
		$socialpower->fb_enable = 'true';
		$socialpower->fb_data_layout = 'box_count';
		$socialpower->fb_data_width = '90';
		$socialpower->fb_data_show_faces = 'true';
		$socialpower->fb_data_font = 'arial';
		$socialpower->fb_data_action = 'both';
		$socialpower->tw_enable = 'true';
		$socialpower->tw_data_count = 'vertical';
		$socialpower->tw_width = '60';
		$socialpower->in_enable = 'true';
		$socialpower->in_data_count = 'top';
		$socialpower->gp_enable = 'true';
		$socialpower->gp_data_size = 'tall';
		$socialpower->gp_data_annotation = 'bubble';
		$socialpower->gp_data_width = '52';
		foreach (Language::getLanguages(false) as $lang)
			$socialpower->tw_data_via[$lang['id_lang']] = 'Prestashop';

		Db::getInstance()->delete(''.pSQL(_DB_PREFIX_).'socialpower', 'id_socialpower > 0');
		return $socialpower->add();
	}

	private function settemplate2($id_shop)
	{
		$socialpower = new SocialpowerClass();
		$socialpower->id_shop = (int)$id_shop;
		$socialpower->template = '2';
		$socialpower->fb_enable = 'true';
		$socialpower->fb_data_layout = 'button_count';
		$socialpower->fb_data_width = '180';
		$socialpower->fb_data_show_faces = 'true';
		$socialpower->fb_data_font = 'arial';
		$socialpower->fb_data_action = 'both';
		$socialpower->tw_enable = 'true';
		$socialpower->tw_data_count = 'horizontal';
		$socialpower->tw_width = '80';
		$socialpower->in_enable = 'true';
		$socialpower->in_data_count = 'right';
		$socialpower->gp_enable = 'true';
		$socialpower->gp_data_size = 'medium';
		$socialpower->gp_data_annotation = 'bubble';
		$socialpower->gp_data_width = '65';
		foreach (Language::getLanguages(false) as $lang)
			$socialpower->tw_data_via[$lang['id_lang']] = 'Prestashop';
		Db::getInstance()->delete(''.pSQL(_DB_PREFIX_).'socialpower', 'id_socialpower > 0');
		return $socialpower->add();
	}

	private function settemplate3($id_shop)
	{
		$socialpower = new SocialpowerClass();
		$socialpower->id_shop = (int)$id_shop;
		$socialpower->template = '3';
		$socialpower->fb_enable = 'true';
		$socialpower->fb_data_layout = 'standard';
		$socialpower->fb_data_width = '180';
		$socialpower->fb_data_show_faces = 'true';
		$socialpower->fb_data_font = 'arial';
		$socialpower->fb_data_action = 'like';
		$socialpower->tw_enable = 'true';
		$socialpower->tw_data_count = 'none';
		$socialpower->tw_width = '62';
		$socialpower->in_enable = 'true';
		$socialpower->in_data_count = '';
		$socialpower->gp_enable = 'true';
		$socialpower->gp_data_size = 'medium';
		$socialpower->gp_data_annotation = 'none';
		$socialpower->gp_data_width = '35';
		foreach (Language::getLanguages(false) as $lang)
			$socialpower->tw_data_via[$lang['id_lang']] = 'Prestashop';
		Db::getInstance()->delete(''.pSQL(_DB_PREFIX_).'socialpower', 'id_socialpower > 0');
		return $socialpower->add();
	}

	public function getContent()
	{
		$this->_html = '';
		$success = '';
		$errors = '';
		$id_shop = (int)$this->context->shop->id;
		$this->postProcess();
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		if (Tools::isSubmit('submitUpdateSocialpower2'))
		{
			if ($socialpower->template == '1')
				$this->settemplate1($id_shop);
			if ($socialpower->template == '2')
				$this->settemplate2($id_shop);
			if ($socialpower->template == '3')
				$this->settemplate3($id_shop);
		}
		$helper = $this->initForm();
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		if (!$socialpower) //if socialpower ddo not exist for this shop => create a new example one
			$this->createExampleSocialpower($id_shop);
		foreach ($this->fields_form[0]['form']['input'] as $input) //fill all form fields
				$helper->fields_value[$input['name']] = $socialpower->{$input['name']};
		foreach ($this->fields_form[1]['form']['input'] as $input) //fill all form fields
				$helper->fields_value[$input['name']] = $socialpower->{$input['name']};
		foreach ($this->fields_form[2]['form']['input'] as $input) //fill all form fields
				$helper->fields_value[$input['name']] = $socialpower->{$input['name']};
		foreach ($this->fields_form[3]['form']['input'] as $input) //fill all form fields
				$helper->fields_value[$input['name']] = $socialpower->{$input['name']};
		foreach ($this->fields_form[4]['form']['input'] as $input) //fill all form fields
				$helper->fields_value[$input['name']] = $socialpower->{$input['name']};
		$this->_html .= $this->DisplayConfirmation($this->l('Settings updated successfully'));
		$this->_html .= $helper->generateForm($this->fields_form);
		return $this->_html;
	}

	private function initForm()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language)
			$languages[$k]['is_default'] = (int)($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));
		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'socialpower';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitUpdateSocialpower';
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Pick one of our predetermined templates'),
				'image' => $this->_path.'logo.gif'
			),
			'submit' => array(
				'name' => 'submitUpdateSocialpower2',
				'title' => $this->l('   Set Template   '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'radio',
					'label' => $this->l('Social button templates'),
					'name' => 'template',
					'required' => false,
					'class' => 't',
					'values' => array(
						array(
							'id' => '1',
							'value' => '1',
							'label' => ''.$this->l('Template 1').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/templates/template1.jpg'.'"/><br><br>'
						),
						array(
							'id' => '2',
							'value' => '2',
							'label' => ''.$this->l('Template 2').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/templates/template2.jpg'.'"/><br><br>'
						),
						array(
							'id' => '3',
							'value' => '3',
							'label' => ''.$this->l('Template 3').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/templates/template3.jpg'.'"/>'
						)
					),
					'desc' => $this->l('You can use among the predetermined social button templates in one simple step. ')
				),
			)
		);

		$this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Facebook Like Button Settings'),
				'image' => $this->_path.'views/img/like.jpg'
			),
			'submit' => array(
				'name' => 'submitUpdateSocialpower',
				'title' => $this->l('   Save   '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'radio',
					'label' => $this->l('Enable facebook'),
					'name' => 'fb_enable',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'on',
							'value' => 'true',
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'off',
							'value' => 'false',
							'label' => $this->l('No')
						)
					),
					'desc' => $this->l('if you enable, facebook like button will be displayed.')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Action'),
					'name' => 'fb_data_action',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'like',
							'value' => 'like',
							'label' => ''.$this->l('Like').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/facebook/box.jpg'.'"/>'
						),
						array(
							'id' => 'recommend',
							'value' => 'recommend',
							'label' => ''.$this->l('Recommend').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/facebook/recommend.jpg'.'"/>',
						),
						array(
							'id' => 'both',
							'value' => 'both',
							'label' => ''.$this->l('Both').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/facebook/both.jpg'.'"/>',
						)
					),
					'desc' => $this->l('the verb to be displayed on the button.')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Layout'),
					'name' => 'fb_data_layout',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'standard',
							'value' => 'standard',
							'label' => ''.$this->l('Standard').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/facebook/standard.jpg'.'"/>',
							'hint' => $this->l('minimum width: 225 pixels. Minimum increases by 40px if action is recommend by. Default width: 90 pixels. Height: 35 pixels (without photos) or 80 pixels (with photos).')
						),
						array(
							'id' => 'button_count',
							'value' => 'button_count',
							'label' => ''.$this->l('Button count').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/facebook/button.jpg'.'"/>',
							'hint' => $this->l('displays the total number of likes to the right of the button. Minimum width: 90 pixels. Default width: 90 pixels. Height: 20 pixels.')
						),
						array(
							'id' => 'box_count',
							'value' => 'box_count',
							'label' => ''.$this->l('Box count').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/facebook/box.jpg'.'"/>',
							'hint' => $this->l('displayss the total number of likes above the button. Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels.')
						)
					),
					'desc' => $this->l('there are three layout options. Default is box count.')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Width'),
					'required' => true,
					'name' => 'fb_data_width',
					'hint' => $this->l('The width of the Like/Recommend button'),
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Show Faces'),
					'name' => 'fb_data_show_faces',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'on',
							'value' => 'true',
							'label' => $this->l('On')
						),
						array(
							'id' => 'off',
							'value' => 'false',
							'label' => $this->l('Off')
						)
					),
					'desc' => $this->l('specifies whether to display profile photos below the button (standard layout only).')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Font'),
					'name' => 'fb_data_font',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'arial',
							'value' => 'arial',
							'label' => $this->l('Arial')
						),
						array(
							'id' => 'lucida_grande',
							'value' => 'lucida grande',
							'label' => $this->l('Lucida grande')
						),
						array(
							'id' => 'segoe_ui',
							'value' => 'segoe ui',
							'label' => $this->l('Segoe ui')
						),
						array(
							'id' => 'tahoma',
							'value' => 'tahoma',
							'label' => $this->l('Tahoma')
						),
						array(
							'id' => 'trebuchet_ms',
							'value' => 'trebuchet ms',
							'label' => $this->l('Trebuchet ms')
						),
						array(
							'id' => 'verdana',
							'value' => 'verdana',
							'label' => $this->l('Verdana')
						)
					),
					'desc' => $this->l('the font to be displayed in the button. Default is Arial.')
				),
			)
		);
		$this->fields_form[2]['form'] = array(
			'legend' => array(
				'title' => $this->l('Twitter Button Settings'),
				'image' => $this->_path.'views/img/tweet.gif'
			),
			'submit' => array(
				'name' => 'submitUpdateSocialpower',
				'title' => $this->l('   Save   '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'radio',
					'label' => $this->l('Enable Tweet button'),
					'name' => 'tw_enable',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'on',
							'value' => 'true',
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'off',
							'value' => 'false',
							'label' => $this->l('No')
						)
					),
					'desc' => $this->l('if you enable, twitter tweet button will be displayed.')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Count box'),
					'name' => 'tw_data_count',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'none',
							'value' => 'none',
							'label' => ''.$this->l('None').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/twitter/none.jpg'.'"/>'
						),
						array(
							'id' => 'above',
							'value' => 'vertical',
							'label' => ''.$this->l('Above the button').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/twitter/above.jpg'.'"/>'
						),
						array(
							'id' => 'next',
							'value' => 'horizontal',
							'label' => ''.$this->l('Right side of the button').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/twitter/right.jpg'.'"/>'
						)
					),
					'desc' => $this->l('determines the position of the count box')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Width'),
					'required' => true,
					'name' => 'tw_width',
					'hint' => $this->l('Twitter button width'),
				),
				array(
					'type' => 'text',
					'label' => $this->l('Twitter account name'),
					'name' => 'tw_data_via',
					'lang' => true,
					'desc' => $this->l('this is your twitter account and the tweet button will automatically adds your account to the tweets with a via tag. Ex: megventure'),
				),
			)
		);
		$this->fields_form[3]['form'] = array(
			'legend' => array(
				'title' => $this->l('Google+ Button Settings'),
				'image' => $this->_path.'views/img/googleplus.png'
			),
			'submit' => array(
				'name' => 'submitUpdateSocialpower',
				'title' => $this->l('   Save   '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'radio',
					'label' => $this->l('Enable Google Plus button'),
					'name' => 'gp_enable',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'on',
							'value' => 'true',
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'off',
							'value' => 'false',
							'label' => $this->l('No')
						)
					),
					'desc' => $this->l('if you enable, google plus button will be displayed.')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Button size'),
					'name' => 'gp_data_size',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'small',
							'value' => 'small',
							'label' => ''.$this->l('Small (15px)').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/small.jpg'.'"/>'
						),
						array(
							'id' => 'medium',
							'value' => 'medium',
							'label' => ''.$this->l('Medium (20px)').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/medium.jpg'.'"/>'
						),
						array(
							'id' => 'standard',
							'value' => 'standard',
							'label' => ''.$this->l('Standard (24px)').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/standard.jpg'.'"/>'
						),
						array(
							'id' => 'tall',
							'value' => 'tall',
							'label' => ''.$this->l('Tall (60px)').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/tall.jpg'.'"/>'
						)
					),
					'desc' => $this->l('sets the +1 button size to render. default is tall')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Annotation'),
					'name' => 'gp_data_annotation',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'none',
							'value' => 'none',
							'label' => ''.$this->l('None').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/none.jpg'.'"/>',
							'hint' => $this->l('Do not render additional annotations.')
						),
						array(
							'id' => 'bubble',
							'value' => 'bubble',
							'label' => ''.$this->l('Bubble').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/bubble.jpg'.'"/>',
							'hint' => $this->l('Display the number of users who have +1d the page in a graphic over the button. To display the count box above the button, tall button size should be selected along with the bubble option.')
						),
						array(
							'id' => 'inline',
							'value' => 'inline',
							'label' => ''.$this->l('Inline').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/gplus/inline.jpg'.'"/>',
							'hint' => $this->l('Display number and/or profile pictures of connected users who have +1d the page and a count of users who have +1d the page.')
						)
					),
					'desc' => $this->l('sets the annotation to display next to the button. to disable the count display, use annotation="none".')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Width'),
					'required' => true,
					'name' => 'gp_data_width',
					'hint' => $this->l('If annotation is set to "inline", this parameter sets the width in pixels to use for the button and its inline annotation. If the width is omitted, a button and its inline annotation use 90px.'),
					'desc' => '<a href="https://developers.google.com/+/plugins/+1button/#inline-annotation" target="_blank">See example Inline annotation widths</a>',
				),
			)
		);
		$this->fields_form[4]['form'] = array(
			'legend' => array(
				'title' => $this->l('Linkedin Share Button Settings'),
				'image' => $this->_path.'views/img/linkedin.png'
			),
			'submit' => array(
				'name' => 'submitUpdateSocialpower',
				'title' => $this->l('   Save   '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'radio',
					'label' => $this->l('Enable Linkedin button'),
					'name' => 'in_enable',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'on',
							'value' => 'true',
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'off',
							'value' => 'false',
							'label' => $this->l('No')
						)
					),
					'desc' => $this->l('if you enable, linkedin share button will be displayed.')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Count box'),
					'name' => 'in_data_count',
					'required' => true,
					'class' => 't',
					'values' => array(
						array(
							'id' => 'none',
							'value' => '',
							'label' => ''.$this->l('None').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/linkedin/none.jpg'.'"/>'
						),
						array(
							'id' => 'top',
							'value' => 'top',
							'label' => ''.$this->l('Top of the button').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/linkedin/top.jpg'.'"/>'
						),
						array(
							'id' => 'right',
							'value' => 'right',
							'label' => ''.$this->l('Right of the button').'&nbsp;<img style="vertical-align:text-top;" src="'.$this->_path.'views/img/linkedin/right.jpg'.'"/>'
						)
					),
					'desc' => $this->l('determines the position of the count box')
				),
			)
		);
		return $helper;
	}

	public function postProcess()
	{
		$errors = '';
		if (Tools::isSubmit('submitUpdateSocialpower'))
		{
			$id_shop = (int)$this->context->shop->id;
			$socialpower = SocialpowerClass::getByIdShop($id_shop);
			$socialpower->copyFromPost();
			$socialpower->update();
		}
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'views/css/socialpower.css', 'all');
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_header.tpl');
	}

	public function hookProductFooter($params)
	{
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_product_footer.tpl');
	}


	public function hookFooter($params)
	{
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_footer.tpl');
	}

	public function hookHome($params)
	{
		return $this->hookTop($params);
	}

	public function hookExtraleft($params)
	{
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_extraleft.tpl');
	}

	public function hookExtraright($params)
	{
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_extraright.tpl');
	}

	public function hookLeftColumn($params)
	{
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_left.tpl');
	}

	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}

	public function hookTop($params)
	{
		$id_shop = (int)$this->context->shop->id;
		$socialpower = SocialpowerClass::getByIdShop($id_shop);
		$socialpower = new SocialpowerClass((int)$socialpower->id, $this->context->language->id);
		$this->smarty->assign(array(
				'socialpower' => $socialpower,
				'default_lang' => (int)$this->context->language->id,
				'id_lang' => $this->context->language->id
			));
		return $this->display(__FILE__, 'views/templates/front/socialpower_top.tpl');
	}
}