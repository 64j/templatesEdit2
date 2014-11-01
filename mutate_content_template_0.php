<?php
global $_lang;
return array(
	'General' => array(
		'title' => $_lang['settings_general'],
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'pagetitle' => array(
				'field' => array(
					'title' => $_lang['resource_title'],
					'help' => $_lang['resource_title_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'longtitle' => array(
				'field' => array(
					'title' => $_lang['long_title'],
					'help' => $_lang['resource_long_title_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'parent' => array(
				'field' => array(
					'title' => 'Родительская категория'/*$_lang['resource_parent']*/,
					'help' => $_lang['resource_parent_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'description' => array(
				'field' => array(
					'title' => $_lang['resource_description'],
					'help' => $_lang['resource_description_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'weblink' => array(
				'field' => array(
					'title' => $_lang['weblink'],
					'help' => $_lang['resource_weblink_help'],
					'roles' => '',
					'hide' => ''
				)
			)
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
					'title' => ''/*$_lang['resource_summary']*/,
					'help' => ''/*$_lang['resource_summary_help']*/,
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
	'Seo' => array(
		'title' => 'SEO',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'alias' => array(
				'field' => array(
					'title' => 'URL'/*$_lang['resource_alias']*/,
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
			'link_attributes' => array(
				'field' => array(
					'title' => $_lang['link_attributes'],
					'help' => strClean($_lang['link_attributes_help']),
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
	'Settings' => array(
		'title' => $_lang['settings_page_settings'],
		'roles' => '',
		'hide' => '',
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
					'title' => 'Дочерние ресурсы отображаются в дереве'/*$_lang['track_visitors_title']*/,
					'help' => 'Это поле используется для папок с большим числом вложенных страниц'/*$_lang['resource_opt_trackvisit_help']*/,
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
					'help' => 'Дата создания ресурса',
					'roles' => '',
					'hide' => ''
				)
			),
			'editedon' => array(
				'field' => array(
					'title' => 'Дата редактирования',
					'help' => 'Дата последнего редактирования ресурса',
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
			)
		)
	)
);
?>
