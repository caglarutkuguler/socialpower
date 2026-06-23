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

class SocialpowerClass extends ObjectModel
{
	public $id;
	public $id_shop;
	public $gp_enable;
	public $tw_enable;
	public $in_enable;
	public $pin_enable;
	public $fb_enable;
	public $fb_data_layout;
	public $fb_data_width;
	public $fb_data_show_faces;
	public $fb_data_font;
	public $fb_data_action;
	public $tw_data_count;
	public $in_data_count;
	public $pin_data_count;
	public $tw_data_via;
	public $tw_width;
	public $gp_data_size;
	public $gp_data_annotation;
	public $gp_data_width;
	public $template;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'socialpower',
		'primary' => 'id_socialpower',
		'multilang' => true,
		'fields' => array(
			'id_shop' =>			array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'gp_enable' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'tw_enable' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'in_enable' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'fb_enable' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'fb_data_layout' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'fb_data_width' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'fb_data_show_faces' =>	array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'fb_data_font' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'fb_data_action' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'tw_data_count' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'tw_width' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'in_data_count' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'gp_data_size' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'gp_data_annotation' =>	array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'gp_data_width' =>		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'template' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),

			'tw_data_via' =>		array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
		)
	);

	public function add($autodate = true, $null_values = false)
	{
		return parent::add($autodate, $null_values);
	}

	public static function getByIdShop($id_shop)
	{
		$id = Db::getInstance()->getValue('SELECT `id_socialpower` FROM `'._DB_PREFIX_.'socialpower` WHERE `id_shop` ='.(int)$id_shop);
		if ($id)
			return new SocialpowerClass((int)$id);
		else
			return false;
	}

	public function copyFromPost()
	{
		/* Classical fields */
		$admin_infos = array('gp_enable' =>Tools::getValue('gp_enable'), 'tw_enable' =>Tools::getValue('tw_enable'), 'in_enable' =>Tools::getValue('in_enable'), 'fb_enable' =>Tools::getValue('fb_enable'),  'fb_data_layout' =>Tools::getValue('fb_data_layout'), 'fb_data_width' =>Tools::getValue('fb_data_width'), 'fb_data_show_faces' =>Tools::getValue('fb_data_show_faces'), 'fb_data_font' =>Tools::getValue('fb_data_font'), 'fb_data_action' =>Tools::getValue('fb_data_action'), 'tw_data_count' =>Tools::getValue('tw_data_count'), 'tw_width' =>Tools::getValue('tw_width'), 'in_data_count' =>Tools::getValue('in_data_count'), 'gp_data_size' =>Tools::getValue('gp_data_size'), 'gp_data_annotation' =>Tools::getValue('gp_data_annotation'), 'gp_data_width' => Tools::getValue('gp_data_width'), 'template' => Tools::getValue('template'));
		//print_r ($admin_infos);

		foreach ($admin_infos as $key => $value)
			if (key_exists($key, $this) && $key != 'id_'.$this->table)
				$this->{$key} = $value;

		/* Multilingual fields */
		if (count($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
				foreach ($this->fieldsValidateLang as $field => $validation)
					if (Tools::getValue($field.'_'.(int)($language['id_lang'])) != false)
						$this->{$field}[(int)($language['id_lang'])] = Tools::getValue($field.'_'.(int)($language['id_lang']));
		}
	}
}
