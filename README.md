templatesEdit2
==============

<h2>[EVO] templatesEdit2 — плагин для изменения вида документов в админ панели MODX</h2>
<p>Этот плагин упрощён по сравнению со своим старшим братом <a href="https://github.com/64j/templatesEdit" target="_blank">templatesEdit</a></p>
<p>Так же плагин полностью совместим со всеми используемыми модулями, снипетами и плагинами.</p>
<p>Удобство плагина в том, что достаточно скопировать код плагина на новый проект и получить такое же отображение вкладок и полей.</p>
<p>Для индивидуальной настройки вывода полей документа, требуются базовые знания программирования.</p>

<h3>Установка плагина</h3>
<p>Перед установкой желательно отключить плагин ManagerManager, если он используется для добавления и перемещения вкладок и полей при выводе документа в админке.</p>
<p>Перед установкой плагина проверяете наличие события <strong>OnDocFormTemplateRender</strong>, если нет такого то выполняете SQL запрос (в админке MODX: Интрументы > Резервное копирование > Восстановить > Выполнить произвольную команду SQL)</p>
<pre>
INSERT INTO modx_system_eventnames VALUES (NULL, 'OnDocFormTemplateRender', '1', 'Documents');
</pre>
<p>Создать новый плагин <b>mutate_content_dynamic</b> (код из файла <b>mutate_content_dynamic.php</b>), и поставить на событие <b>OnDocFormTemplateRender</b></p>
<p>Внимание, обработка события <b>OnDocFormTemplateRender</b> - есть только в DEV версиях MODX</p>
<p></p>
<p>Шаблон вывода полей документа, по умолчанию заложен в коде самого плагина</p>

<h3>Свои шаблоны вывода полей в админке</h3>
<p>Если нужно изменить под каждый шаблон документа свой вывод полей, <br>
для этого нужно создать новый сниппет с названием <b>mutate_content_template_0</b> — где 0 это id шаблона (в данном случае это шаблон blank)
и с кодом из файла <b>mutate_content_template_0.php</b></p>
<p>Чтобы изменить вывод полей по умолчанию под себя, в коде плагина есть массив <b>$mutate_content_fields</b>, 
изменяя порядок элементов массива, можно менять вывод полей по умолчанию.</p>

<h3>Добавление / перемещение TV параметров</h3>
<p>В файле <b>mutate_content_template_4.php</b> пример расположения полей и вкладок для конкретного шаблона (в данном случае с ID 4) в котором TV парметры требуется перенести на другие вкладки либо поменять местами.</p>
Элемент массива для вывода TV-параметра достаточно прост, в массив [fields] достаточно добавить tvID, где ID — это id параметра.
Пример листинга. Вкладка SEO с добавленными параметрами
<pre>	'Seo' => array(
		'title' => 'SEO',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'tv9' => '',
			'tv7' => '',
			'tv8' => '',
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
			'menuindex' => array(
				'field' => array(
					'title' => $_lang['resource_opt_menu_index'],
					'help' => '',
					'roles' => '',
					'hide' => ''
				)
			)
		)
	),..........
	</pre>

<h3>Добавление разделителя для группировки полей на вкладке</h3>
<img src="https://api.monosnap.com/image/download?id=3VdmEWrGJ99hoMv3uqvWURZPx5SZAo">
<p>Для вывода разделителя нужно добавить в массив [fields] сплитер с уникальным именем.<br>
Листинг вкладки с разделителями приведён ниже</p>
<pre>
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
	),................
</pre>	
