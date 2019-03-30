templatesEdit2
==============
<h2>[EVO] templatesEdit2 — плагин для изменения вида документов в админ панели MODX</h2>

! Это устаревшая версия плагина. Используйте templatesEdit3 https://github.com/64j/templatesedit3

Картинки на можно посмотреть на <a href="http://wexar.ru/evo-templatesedit2.html" target="_blank"> нашем сайте</a><br>
<h4>Быстрая установка для сборок от Dmi3yy MODx Custom выше версии 1.8</h4>
<p>скопировать файл <strong>plugin.templatesedit.php</strong> в папку <strong>assets\plugins\templatesedit</strong></p>
<p>создать плагин <strong>templatesEdit2</strong> на событие <strong>OnDocFormTemplateRender</strong> с кодом</p>
<pre>require_once MODX_BASE_PATH.'assets/plugins/templatesedit/plugin.templatesedit.php';</pre>
<p>Доавить конфигурацию плагина</p>
<pre>&showTvImage=Показывать картинки в TV;list;yes,no;yes &excludeTvCategory=Исключить TV из категорий;text;</pre>
<br>
<br>

<h6>UPD: 21.07.2017</h6>
<p>Добавлена возможность подключать файлы шаблонов из папки configs</p>
<p>
в папке с плагином нужно создать папку configs и в неё можно складывать все конфигурационные файлы<br>
Код файлов следующий
</p>

```php
<?php
global $_lang;

$mutate_content_fields = array(

....................

);

return $mutate_content_fields;
```

<br>

<h6>UPD: 16.07.2017</h6>
<p>Изменение под EVO 1.3</p>
<p>Убран из массива menusort, заменён на menuindex</p>
<br>

<h6><a id="user-content-upd-29122015" class="anchor" href="#upd-29122015" aria-hidden="true"><span class="octicon octicon-link"></span></a><strong>UPD: 29.12.2015</strong></h6>
<p>1. Изменения в функции показа картинок.</p>
<p>2. Нововведение. Для полей TV с типом <strong>Text</strong> и <strong>Number</strong>, теперь при заполнении возможных значений, они показываются подсказками при вводе данных полей в админке.</p>
<p>3. Добавленно в конфигурацию плагина, <strong>Исключить TV из категорий</strong> (id категорий, из которых не будут показываться тв-параметры). По умолчанию все параметры не попавшие на другие вкладки - выводятся на вкладке Основные [General] Сделано для того, чтобы можно было самостоятельно выводить параметры не назначая их по отдельности, а присваивать целыми категориями сразу.</p>
<p>В созданном сниппет <a href="https://github.com/64j/templatesEdit2/blob/master/mutate_content_template_5" target="_blank"><b>mutate_content_template_5</b></a> (то есть для шаблона с id = 5, в корне прилагается рабочий файл с проекта) создаём вкладку <strong>Props</strong> и <strong>Prices</strong> и в самом низу ставим запрос на получение параметров из определённых категорий и получаем, что тв параметры из категории <strong>15</strong> попадают на вкладку - <strong>Свойства</strong>, а из категории <strong>16</strong> на вклдаку - <strong>Цены</strong></p>
<p>Пример листинга вывода шаблона:</p>
<pre>$mutate_content = array(
    'Props' =&gt; array(
        'title' =&gt; 'Свойства',
        'fields' =&gt; array()
    ),
    'Prices' =&gt; array(
        'title' =&gt; 'Цены',
        'fields' =&gt; array()
    ),  
    ..........
    
);

$sql = $modx-&gt;db-&gt;query('SELECT id, name, category FROM modx_site_tmplvars WHERE category IN(15,16) ORDER BY category, rank ASC');

if($modx-&gt;db-&gt;getRecordCount($sql) &gt; 0) {
    foreach($modx-&gt;db-&gt;makeArray($sql) as $v) {
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

return $mutate_content;</pre>


<h6>UPD: 7.11.2014</h6>
<p><del>Возможность разделения полей hidemenu и menuindex, для вывода отдельно menuindex - использовать menusort</del></p>
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
