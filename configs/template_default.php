<?php
global $_lang, $content;

$render_template = array(
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
            'description' => array(
                'field' => array(
                    'title' => $_lang['resource_description'],
                    'help' => $_lang['resource_description_help'],
                    'roles' => '',
                    'hide' => '1'
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
			'parent' => array(
				'field' => array(
					'title' => $_lang['resource_parent'],
					'help' => $_lang['resource_parent_help'],
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
			),
			'template' => array(
				'field' => array(
					'title' => $_lang['page_data_template'],
					'help' => $_lang['page_data_template_help'],
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
            'richtext' => array(
                'field' => array(
                    'title' => $_lang['resource_opt_richtext'],
                    'help' => $_lang['resource_opt_richtext_help'],
                    'roles' => '',
                    'hide' => ''
                )
            ),
			'introtext' => array(
				'field' => array(
					'title' => $_lang['resource_summary'],
					'help' => $_lang['resource_summary_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'content' => array(
				'field' => array(
					'title' => $_lang['resource_content'],
					'help' => '',
					'roles' => '',
					'hide' => ''
				)
			),
			'content-spec' => array(
                'title' => '',
                'subtitle' => 'Технические характеристики',
                'help' => '',
                'roles' => '',
                'hide' => ''
            ),
		)
	),
	'ProductProps' => array(
	    'title' => 'Свойства товара',
        'fields' => array(
            'articul' => array(),
            'sku' => array(),
            'currency' => array(),
            'price' => array(),
            'price-old' => array(),
            'material' => array(),
            'size' => array(),
            'weight' => array(),
            'country' => array(),
            'availability' => array(),
            'composition' => array(),
            'brand' => array(),
            'quantity' => array(),
            'isNew' => array()
        )
    ),
	'ImagesFiles' => array(
	    'title' => 'Изображения и файлы',
        'fields' => array(
            'icon' => array(),
            'image' => array(),
            'images' => array(),
            'slider' => array(),
            'images-partners' => array(),
            'images-certificates' => array(),
        )
    ),
    'Tabs' => array(
        'title' => 'Табы',
        'fields' => array(
            'tabs' => array()
        )
    ),
	'Seo' => array(
		'title' => 'SEO',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'metaTitle' => array(),
			'metaDescription' => array(),
			'metaKeywords' => array(),
			'alias' => array(
				'field' => array(
					'title' => $_lang['resource_alias'],
					'help' => $_lang['resource_alias_help'],
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
					'help' => $_lang['resource_opt_menu_index_help'],
					'roles' => '',
					'hide' => ''
				)
			),
			'hidemenu' => array(
				'field' => array(
					'title' => $_lang['resource_opt_show_menu'],
					'help' => $_lang['resource_opt_show_menu_help'],
					'roles' => '',
					'hide' => ''
				)
			),
            'sitemap_exclude' => array(),
            'sitemap_priority' => array(),
            'sitemap_changefreq' => array()
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
					'title' => $_lang['track_visitors_title'],
					'help' => $_lang['resource_opt_trackvisit_help'],
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
					'title' => $_lang["createdon"],
					'help' => $_lang["createdon"],
					'roles' => '',
					'hide' => ''
				)
			),
			'editedon' => array(
				'field' => array(
					'title' => $_lang["editedon"],
					'help' => $_lang["editedon"],
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

//$render_template['General']['fields']['description']['field']['hide'] = 0;

//if ($content['template'] == 8) {
//    $render_template['General']['fields']['description']['field']['hide'] = 0;
//}

//$parent = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent'];
//if ($parent == 10) {
//    $render_template['General']['fields']['description']['field']['hide'] = 0;
//}

return $render_template;
