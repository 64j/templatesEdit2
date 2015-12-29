<?php
global $_lang, $content;
$mutate_content = array(
	'General' => array(
		'title' => $_lang['settings_general'],
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'pagetitle' => array(
				'field' => array(
					'title' => $_lang['resource_title'],
					'help' => 'Введите имя/заголовок ресурса. Нежелательно использовать при этом обратный слэш (\)',
					'roles' => '',
					'hide' => ''
				)
			),
			'longtitle' => array(
				'field' => array(
					'title' => $_lang['long_title'],
					'help' => 'Здесь вы можете ввести расширенный заголовок вашего ресурса, если он не отличается от заголовка, то оставьте пустым.',
					'roles' => '',
					'hide' => ''
				)
			),
			'parent' => array(
				'field' => array(
					'title' => 'Родительская категория'
					/*$_lang['resource_parent']*/,
					'help' => $_lang['resource_parent_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'link_attributes' => array(
				'field' => array(
					'title' => 'Код товара в 1С',
					'help' => '',
					'roles' => '',
					'hide' => ''
				)
			),
			'template' => array(
				'field' => array(
					'title' => $_lang['page_data_template'],
					'help' => $_lang['page_data_template_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'description' => array(
				'field' => array(
					'title' => $_lang['resource_description'],
					'help' => $_lang['resource_description_help'],
					'roles' => '',
					'hide' => '1'
				)
			),
			'weblink' => array(
				'field' => array(
					'title' => $_lang['weblink'],
					'help' => $_lang['resource_weblink_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'image' => ''
		)
	),
	'Content' => array(
		'title' => 'Описание',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'split_introtext' => array(
				'split' => array(
					'title' => 'Краткое описание',
					'roles' => '',
					'hide' => ''
				)
			),
			'introtext' => array(
				'field' => array(
					'title' => ''
					/*$_lang['resource_summary']*/,
					'help' => ''
					/*$_lang['resource_summary_help']*/,
					'roles' => '',
					'hide' => ''
				)
			),
			'split_content' => array(
				'split' => array(
					'title' => 'Подробное описание',
					'roles' => '',
					'hide' => ''
				)
			),
			'content' => array(
				'field' => array(
					'title' => $_lang['which_editor_title'],
					'help' => '',
					'roles' => '',
					'hide' => ''
				)
			),
			'richtext' => array(
				'field' => array(
					'title' => $_lang['resource_opt_richtext'],
					'help' => $_lang['resource_opt_richtext_help'],
					'roles' => '',
					'hide' => ''
				)
			)
		)
	),
	'Props' => array(
		'title' => 'Свойства',
		'fields' => array()
	),
	'Prices' => array(
		'title' => 'Цены',
		'fields' => array(
		)
	),
	'ImagesAndFiles' => array(
		'title' => 'Изображения и файлы',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'split_images' => array(
				'split' => array(
					'title' => 'Изображения'
				)
			),
			'images' => array(
				'tv' => array(
					'title' => ''
				)
			),
			'split_files' => array(
				'split' => array(
					'title' => '<hr style="height:2px;margin: -6px 0 6px;">Файлы'
				)
			),
			'files' => array(
				'tv' => array(
					'title' => ''
				)
			)
		)
	),
	'Seo' => array(
		'title' => 'SEO',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'metaTitle' => array(
				'tv' => array(
					'help' => 'Здесь вы можете ввести заголовок для отображения в метатеге &amp;lt;title&amp;gt, либо оставить пустым и тогда данные будут браться из поля Заголовок.'
				)
			),
			'metaDescription' => array(
				'tv' => array(
					'help' => 'Здесь вы можете ввести описание для отображения в метатеге &amp;lt;meta content=decsription&amp;gt, либо оставить пустым и тогда данные будут браться из описания документа.'
				)
			),
			'metaKeywords' => array(
				'tv' => array(
					'help' => 'Здесь вы можете ввести ключевые слова для отображения в метатеге &amp;lt;meta content=keywords&amp;gt, либо оставить пустым.'
				)
			),
			'alias' => array(
				'field' => array(
					'title' => 'URL'
					/*$_lang['resource_alias']*/,
					'help' => $_lang['resource_alias_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'menutitle' => array(
				'field' => array(
					'title' => $_lang['resource_opt_menu_title'],
					'help' => $_lang['resource_opt_menu_title_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'menuindex' => array(
				'field' => array(
					'title' => $_lang['resource_opt_menu_index'],
					'help' => '',
					'roles' => '',
					'hide' => ''
				)
			)
		)
	),
	'Tags' => array(
		'title' => 'Теги',
		'fields' => array(
			'tags' => array(
				'tv' => array(
					'title' => ''
				)
			)
		)
	),
	'Settings' => array(
		'title' => $_lang['settings_page_settings'],
		'roles' => '',
		'hide' => '0',
		'fields' => array(
			'published' => array(
				'field' => array(
					'title' => $_lang['resource_opt_published'],
					'help' => $_lang['resource_opt_published_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'alias_visible' => array(
				'field' => array(
					'title' => $_lang['resource_opt_alvisibled'],
					'help' => $_lang['resource_opt_alvisibled_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'isfolder' => array(
				'field' => array(
					'title' => $_lang['resource_opt_folder'],
					'help' => $_lang['resource_opt_folder_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'donthit' => array(
				'field' => array(
					'title' => 'Дочерние ресурсы отображаются в дереве'
					/*$_lang['track_visitors_title']*/,
					'help' => 'Это поле используется для папок с большим числом вложенных страниц'
					/*$_lang['resource_opt_trackvisit_help']*/,
					'roles' => '',
					'hide' => ''
				)
			),
			'contentType' => array(
				'field' => array(
					'title' => $_lang['page_data_contentType'],
					'help' => $_lang['page_data_contentType_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'type' => array(
				'field' => array(
					'title' => $_lang['resource_type'],
					'help' => $_lang['resource_type_message'],
					'roles' => '',
					'hide' => ''
				)
			),
			'content_dispo' => array(
				'field' => array(
					'title' => $_lang['resource_opt_contentdispo'],
					'help' => $_lang['resource_opt_contentdispo_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'pub_date' => array(
				'field' => array(
					'title' => $_lang['page_data_publishdate'],
					'help' => $_lang['page_data_publishdate_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'unpub_date' => array(
				'field' => array(
					'title' => $_lang['page_data_unpublishdate'],
					'help' => $_lang['page_data_unpublishdate_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'createdon' => array(
				'field' => array(
					'title' => 'Дата создания',
					'help' => 'Дата создания ресурса (не редактируется вручную)',
					'roles' => '',
					'hide' => ''
				)
			),
			'editedon' => array(
				'field' => array(
					'title' => 'Дата редактирования',
					'help' => 'Дата последнего редактирования ресурса (не редактируется вручную)',
					'roles' => '',
					'hide' => ''
				)
			),
			'searchable' => array(
				'field' => array(
					'title' => $_lang['page_data_searchable'],
					'help' => $_lang['page_data_searchable_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'cacheable' => array(
				'field' => array(
					'title' => $_lang['page_data_cacheable'],
					'help' => $_lang['page_data_cacheable_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'syncsite' => array(
				'field' => array(
					'title' => $_lang['resource_opt_emptycache'],
					'help' => $_lang['resource_opt_emptycache_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'hits' => array(
				'field' => array(
					'title' => 'Количество посещений'
				)
			)
		)
	)
);

$parent = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent'];
$tv = $modx->getTemplateVar('used-props', '*', $parent);
$tv = explode('||', $tv['value']);
$mutate_content_tmp = array();

foreach($tv as $v) {
	$tmp = explode('==', $v);
	$id = ltrim($tmp[1], '*');
	if(strstr($tmp[1], '*')) {
		$mutate_content_tmp['props_in_filter']['tv' . $id] = array();
	} else {
		$mutate_content_tmp['props']['tv' . $id] = array();
	}
}

$sql = $modx->db->query('SELECT id, name, category FROM modx_site_tmplvars WHERE category IN(13,14,21) ORDER BY category, rank ASC');

if($modx->db->getRecordCount($sql) > 0) {

	foreach($modx->db->makeArray($sql) as $v) {
		$mutate_content_tmp[$v['category']][$v['name']] = array();
	}

	if(isset($mutate_content_tmp[13]) || isset($mutate_content_tmp[21])) {
		$mutate_content['Prices']['fields'] = array_merge($mutate_content['Prices']['fields'], $mutate_content_tmp[13]);
		$mutate_content['Prices']['fields'] = array_merge($mutate_content['Prices']['fields'], $mutate_content_tmp[21]);
		$mutate_content['Prices']['fields']['split_discount']['split']['title'] = '<hr style="height:2px;margin: -6px 0 6px;">Скидки';
		$mutate_content['Prices']['fields']['discount']['tv']['title'] = '';		
	}

	if(isset($mutate_content_tmp[14])) {
		$mutate_content['General']['fields']['split_props']['split']['title'] = '<hr style="height:2px;margin: -6px 0 6px;">Атрибуты';
		$mutate_content['General']['fields'] = array_merge($mutate_content['General']['fields'], $mutate_content_tmp[14]);
	}

	if(isset($mutate_content_tmp['props'])) {
		$mutate_content['Props']['fields'] = array_merge($mutate_content['Props']['fields'], $mutate_content_tmp['props']);
	}

	if(isset($mutate_content_tmp['props_in_filter'])) {
		$mutate_content['Props']['fields']['split_props_in_filter']['split']['title'] = '<hr style="height:2px;margin: -6px 0 6px;">Свойства используемые в фильтре';
		$mutate_content['Props']['fields'] = array_merge($mutate_content['Props']['fields'], $mutate_content_tmp['props_in_filter']);
	}
}

return $mutate_content;
?>
