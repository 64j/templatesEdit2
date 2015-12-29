templatesEdit2
==============
<h6>UPD: 29.12.2015</h6>
<p>Изменения в функции показа картинок.</p>
<p>Нововведение.
Для полей TV с типом Text и Number, теперь при заполнении возможных значений, они показываются подсказками при вводе данных полей в админке.</p>
<p>Добавленно в конфигурацию плагина, Исключить TV из категорий (id категорий, из которых не будут показываться тв-параметры).
По умолчанию все параметры не попавшие на другие вкладки - выводятся на вкладке Основные  [General]
Сделано для того, чтобы можно было самостоятельно выводить параметры не назначая их по отдельности, а присваивать целыми категориями сразу.</p>
Пример листинга вывода шаблона:

в созданном сниппет <b>mutate_content_template_5</b> (то есть для шаблона с id = 5, в корне прилагается рабочий файл с проекта)
создаём вкладку Props и Prices
и в самом низу ставим запрос на получение параметров из определённых категорий
и получаем, что тв параметры из категории 15 попадают на вкладку - Свойства, а из категории 16 на вклдаку - Цены 
<pre>
$mutate_content = array(
	'Props' => array(
		'title' => 'Свойства',
		'fields' => array()
	),
	'Prices' => array(
		'title' => 'Цены',
		'fields' => array(
		)
	),	
	..........
	
);

$sql = $modx->db->query('SELECT id, name, category FROM modx_site_tmplvars WHERE category IN(15,16) ORDER BY category, rank ASC');

if($modx->db->getRecordCount($sql) > 0) {
	foreach($modx->db->makeArray($sql) as $v) {
		$mutate_content_tmp[$v['category']][$v['name']] = array();
	}
	
	// находим параметры из категории 15 и выводим
	if(isset($mutate_content_tmp[15])) {
		$mutate_content['Props']['fields'] = array_merge($mutate_content['Props']['fields'], $mutate_content_tmp[15]);
	}
	
	// находим параметры из категории 16 и выводим
	if(isset($mutate_content_tmp[16])) {
		$mutate_content['Prices']['fields'] = array_merge($mutate_content['Prices']['fields'], $mutate_content_tmp[16]);
	}
}

return $mutate_content;	
</pre>



<h6>UPD: 7.11.2014</h6>
<p>Возможность разделения полей hidemenu и menuindex, для вывода отдельно menuindex - использовать menusort</p>
<h6>UPD: 7.11.2014</h6>
<p>добавленна возможность показа TV с типом image<br>
обязательно добавить в конфигурацию плагина - &showTvImage=Show TV image;list;yes,no;yes</p>

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
<p>Внимание, обработка события <b>OnDocFormTemplateRender</b> - есть только в CUSTOM и DEV версиях MODX by Dmi3yy</p>
<p></p>
<p>Шаблон вывода полей документа, по умолчанию заложен в коде самого плагина, либо можно поставить свой, создав сниппет с названием <b>mutate_content_template_default</b> с кодом из файла <b>mutate_content_template_default.php</b></p>

<h3>Свои шаблоны вывода полей в админке</h3>
<p>Если нужно изменить под каждый шаблон документа свой вывод полей, <br>
для этого нужно создать новый сниппет с названием <b>mutate_content_template_0</b> — где 0 это id шаблона (в данном случае это шаблон blank)
и с кодом из файла <b>mutate_content_template_0.php</b></p>
<p>Чтобы изменить вывод полей по умолчанию под себя, в коде плагина есть массив <b>$mutate_content_fields</b>, 
изменяя порядок элементов массива, можно менять вывод полей по умолчанию.</p>

<h3>Добавление / перемещение TV параметров</h3>
<p>В файле <b>mutate_content_template_4.php</b> пример расположения полей и вкладок для конкретного шаблона (в данном случае с ID 4) в котором TV парметры требуется перенести на другие вкладки либо поменять местами.</p>
Элемент массива для вывода TV-параметра достаточно прост, в массив [fields] достаточно добавить название TV параметра.
Пример листинга. Вкладка SEO с добавленными параметрами
<pre>	'Seo' => array(
		'title' => 'SEO',
		'roles' => '',
		'hide' => '',
		'fields' => array(
			'tv_pagetitle' => '', // ТВ мета заголовок
			'tv_description' => '', // ТВ мета описание
			'tv_keywords' => '', // ТВ мета ключевые слова
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
