//<?php
/* plugins
* mutate_content_dynamic
* Events OnDocFormTemplateRender
* properties &showTvImage=Show TV image;list;yes,no;yes
*/

global $_lang, $content, $docgrp, $replace_richtexteditor;

function strClean($str) {
	return htmlspecialchars($str, ENT_QUOTES);
}

function ContentFieldSplit() {
	return '<tr><td colspan="2" style="height:0px"><div class="split"></div></td></tr>';
}

function renderTypeImage($value, $tvid) {
	$src = $value ? MODX_SITE_URL . $value : '';
	$out = '<script type="text/javascript">
	var tvImageInput_' . $tvid . ' = document.getElementById("tv' . $tvid . '");
	tvImageInput_' . $tvid . '.onkeyup = tvImageInput_' . $tvid . '.oncut = tvImageInput_' . $tvid . '.oninput = function() {
		lastImageCtrl = "tv' . $tvid . '";
		lastImageCtrl_tmp = "";
		renderTvImageCheck(this.id);
	}
	</script>';
	$out .= '<div class="image_for_tv"><img id="image_for_tv' . $tvid . '" src="' . $src . '" onclick="BrowseServer(\'tv' . $tvid . '\')"' . (!$value ? ' style="display:none"' : '') . ' /></div>';
	return $out;
}

function renderContentField($name, $data, $showTvImage) {
	global $modx, $_style, $_lang, $content, $site_name, $use_editor, $which_editor, $editor, $replace_richtexteditor, $search_default, $publish_default, $cache_default, $closeOptGroup;
	$field = '';
	list($item_title, $item_description) = explode('||||', $data['field']['title']);
	$fieldDescription = (!empty($item_description)) ? '<br><span class="comment">' . $item_description . '</span>' : '';
	$title = '<span class="warning">' . $item_title . '</span>' . $fieldDescription;
	$help = $data['field']['help'] ? ' <img src="' . $_style["icons_tooltip_over"] . '" alt="' . stripcslashes($data['field']['help']) . '" style="cursor:help;" />' : '';
	$row_style = $data['field']['hide'] || $data['tv']['hide'] ? ' style="display:none;"' : '';
	$title_width = 150;
	$mx_can_pub = $modx->hasPermission('publish_document') ? '' : 'disabled="disabled" ';
	if(isset($data['tv'])) {
		$help = $data['tv']['help'] ? ' <img src="' . $_style["icons_tooltip_over"] . '" alt="' . stripcslashes($data['tv']['help']) . '" style="cursor:help;" />' : '';
		if(array_key_exists('tv' . $data['tv']['id'], $_POST)) {
			if($data['tv']['type'] == 'listbox-multiple') {
				$tvPBV = implode('||', $_POST['tv' . $data['tv']['id']]);
			} else {
				$tvPBV = $_POST['tv' . $data['tv']['id']];
			}
		} else {
			$tvPBV = $data['tv']['value'];
		}
		$item_title = $data['tv']['caption'];
		$item_description = $data['tv']['description'];
		$tvDescription = (!empty($item_description)) ? '<br><span class="comment">' . $item_description . '</span>' : '';
		$tvInherited = (substr($tvPBV, 0, 8) == '@INHERIT') ? '<span class="comment inherited">(' . $_lang['tmplvars_inherited'] . ')</span>' : '';
		$title = '<span class="warning">' . $item_title . '</span>' . $tvDescription . $tvInherited;
		$renderTV = '';
		if($data['tv']['type'] == 'image' && $showTvImage) {
			$renderTV = renderTypeImage($tvPBV, $data['tv']['id']);
		}
		$field .= '<tr' . $row_style . '>';
		if($data['tv']['caption']) {
			$field .= '<td valign="top" width="' . $title_width . '">' . $title . '</td>';
		}
		$field .= '<td valign="top" style="position:relative;"' . (!$data['tv']['caption'] ? ' colspan="2"' : '') . '>' . renderFormElement($data['tv']['type'], $data['tv']['id'], $data['tv']['default_text'], $data['tv']['elements'], $tvPBV, '', $data['tv']) . $help . $renderTV . '</td></tr>';
		if(!$data['tv']['hide'] && $field) {
			$field .= ContentFieldSplit();
		}
	}
	if(isset($data['field'])) {
		switch($name) {
			case 'weblink':
				if($content['type'] == 'reference' || $_REQUEST['a'] == '72') {
					$field .= '<tr' . $row_style . '>
				<td>' . $title . ' <img name="llock" src="' . $_style["tree_folder"] . '" alt="tree_folder" onClick="enableLinkSelection(!allowLinkSelection);" style="cursor:pointer;" /></td>
				<td><input name="ta" type="text" maxlength="255" value="' . (!empty($content['content']) ? stripslashes($content['content']) : "http://") . '" class="inputBox" onChange="documentDirty=true;" />' . $help . '</td></tr>';
				}
				break;
			case 'introtext':
				$field .= '<tr' . $row_style . '>';
				if($data['field']['title']) {
					$field .= '<td valign="top" width="' . $title_width . '">' . $title . '</td>';
				}
				$field .= '
			<td valign="top"' . (!$data['field']['title'] ? ' colspan="2"' : '') . '><textarea name="introtext" class="inputBox" rows="3" cols="" onChange="documentDirty=true;" style="width:100%">' . $modx->htmlspecialchars(stripslashes($content['introtext'])) . '</textarea>
			' . $help . '
			</td></tr>';
				break;
			case 'template':
				$field .= '<tr' . $row_style . '>
			<td>' . $title . '</td>
			<td><select id="template" name="template" class="inputBox" onChange="templateWarning();">
			<option value="0">(blank)</option>';
				$rs = $modx->db->select("t.templatename, t.id, c.category", $modx->getFullTableName('site_templates') . " AS t LEFT JOIN " . $modx->getFullTableName('categories') . " AS c ON t.category = c.id", '', 'c.category, t.templatename ASC');
				$currentCategory = '';
				while($row = $modx->db->getRow($rs)) {
					$thisCategory = $row['category'];
					if($thisCategory == null) {
						$thisCategory = $_lang["no_category"];
					}
					if($thisCategory != $currentCategory) {
						if($closeOptGroup) {
							$field .= "</optgroup>";
						}
						$field .= '<optgroup label="' . $thisCategory . '">';
						$closeOptGroup = true;
					}
					if(isset($_REQUEST['newtemplate'])) {
						$selectedtext = $row['id'] == $_REQUEST['newtemplate'] ? ' selected="selected"' : '';
					} else {
						if(isset($content['template'])) {
							$selectedtext = $row['id'] == $content['template'] ? ' selected="selected"' : '';
						} else {
							$default_template = getDefaultTemplate();
							$selectedtext = $row['id'] == $default_template ? ' selected="selected"' : '';
							$content['template'] = $default_template;
						}
					}
					$field .= '<option value="' . $row['id'] . '"' . $selectedtext . '>' . $row['templatename'] . "</option>";
					$currentCategory = $thisCategory;
				}
				if($thisCategory != '') {
					$field .= "</optgroup>";
				}
				$field .= '
			</select>
			' . $help . '
			</td></tr>';
				break;
			case 'menuindex':
				$field .= '<tr' . $row_style . '>
			<td width="' . $title_width . '">' . $title . '</td>
			<td><input name="menuindex" type="text" maxlength="6" value="' . $content['menuindex'] . '" class="inputBox" style="width:30px;" onChange="documentDirty=true;" />
				<input type="button" value="&lt;" onClick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+\'\')-1;elm.value=v>0? v:0;elm.focus();documentDirty=true;" />
				<input type="button" value="&gt;" onClick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+\'\')+1;elm.value=v>0? v:0;elm.focus();documentDirty=true;" />
				<img src="' . $_style["icons_tooltip_over"] . '" alt="' . $_lang['resource_opt_menu_index_help'] . '" style="cursor:help;" />
				<span class="warning">' . $_lang['resource_opt_show_menu'] . '</span>
				<input name="hidemenucheck" type="checkbox" class="checkbox" ' . ($content['hidemenu'] != 1 ? 'checked="checked"' : '') . ' onClick="changestate(document.mutate.hidemenu);" />
				<input type="hidden" name="hidemenu" class="hidden" value="' . ($content['hidemenu'] == 1 ? 1 : 0) . '" />
				<img src="' . $_style["icons_tooltip_over"] . '" alt="' . $_lang['resource_opt_show_menu_help'] . '" style="cursor:help;" />
			</td></tr>';
				break;
			case 'menusort':
				$field .= '<tr' . $row_style . '>
			<td width="' . $title_width . '">' . $title . '</td>
			<td><input name="menuindex" type="text" maxlength="6" value="' . $content['menuindex'] . '" class="inputBox" style="width:30px;" onChange="documentDirty=true;" />
				<input type="button" value="&lt;" onClick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+\'\')-1;elm.value=v>0? v:0;elm.focus();documentDirty=true;" />
				<input type="button" value="&gt;" onClick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+\'\')+1;elm.value=v>0? v:0;elm.focus();documentDirty=true;" />
				' . $help . '
			</td></tr>';
				break;
			case 'parent':
				$field .= '<tr' . $row_style . '>
			<td valign="top"><span class="warning">' . $title . '</span></td>
			<td valign="top">';
				$parentlookup = false;
				if(isset($_REQUEST['id'])) {
					if($content['parent'] == 0) {
						$parentname = $site_name;
					} else {
						$parentlookup = $content['parent'];
					}
				} elseif(isset($_REQUEST['pid'])) {
					if($_REQUEST['pid'] == 0) {
						$parentname = $site_name;
					} else {
						$parentlookup = $_REQUEST['pid'];
					}
				} elseif(isset($_POST['parent'])) {
					if($_POST['parent'] == 0) {
						$parentname = $site_name;
					} else {
						$parentlookup = $_POST['parent'];
					}
				} else {
					$parentname = $site_name;
					$content['parent'] = 0;
				}
				if($parentlookup !== false && is_numeric($parentlookup)) {
					$rs = $modx->db->select('pagetitle', $modx->getFullTableName('site_content'), "id='{$parentlookup}'");
					$parentname = $modx->db->getValue($rs);
					if(!$parentname) {
						$modx->webAlertAndQuit($_lang["error_no_parent"]);
					}
				}
				$field .= '
					<img alt="tree_folder" name="plock" src="' . $_style["tree_folder"] . '" onClick="enableParentSelection(!allowParentSelection);" style="cursor:pointer;" />
					<b><span id="parentName">' . (isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']) . ' (' . $parentname . ')</span></b>
					' . $help . '
					<input type="hidden" name="parent" value="' . (isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']) . '" onChange="documentDirty=true;" />
				</td></tr>';
				break;
			case 'content':
				if($content['type'] == 'document' || $_REQUEST['a'] == '4') {
					$field .= '<tr' . $row_style . '><td colspan="2">';
					if(($content['richtext'] == 1 || $_REQUEST['a'] == '4') && $use_editor == 1) {
						$field .= '<textarea id="ta" name="ta" cols="" rows="" style="width:100%; height: 400px;" onChange="documentDirty=true;">' . $modx->htmlspecialchars($content['content']) . '</textarea>';
						if(is_array($replace_richtexteditor)) {
							$replace_richtexteditor = array_merge($replace_richtexteditor, array(
								'ta'
							));
						} else {
							$replace_richtexteditor = array(
								'ta'
							);
						}
					} else {
						$field .= '<div style="width:100%"><textarea class="phptextarea" id="ta" name="ta" style="width:100%; height: 400px;" onchange="documentDirty=true;">' . $modx->htmlspecialchars($content['content']) . '</textarea></div>';
					}
					$field .= '</td></tr>';
				}
				break;
			case 'published':
				$field .= '<tr' . $row_style . '>
                <td width="' . $title_width . '">' . $title . '</td>
                <td><input ' . $mx_can_pub . 'name="publishedcheck" type="checkbox" class="checkbox" ' . ((isset($content['published']) && $content['published'] == 1) || (!isset($content['published']) && $publish_default == 1) ? "checked" : '') . ' onClick="changestate(document.mutate.published);" />
                <input type="hidden" name="published" value="' . ((isset($content['published']) && $content['published'] == 1) || (!isset($content['published']) && $publish_default == 1) ? 1 : 0) . '" />
                ' . $help . '
				</td></tr>';
				break;
			case 'pub_date':
			case 'unpub_date':
			case 'createdon':
			case 'editedon':
				$field .= '<tr' . $row_style . '>
                <td><span class="warning">' . $title . '</td>
                <td><input id="' . $name . '" ' . $mx_can_pub . 'name="' . $name . '" class="DatePicker" value="' . ($content[$name] == "0" || !isset($content[$name]) ? '' : $modx->toDateFormat($content[$name])) . '" onBlur="documentDirty=true;" />
                <a href="javascript:void(0);" onClick="javascript:document.mutate.' . $name . '.value=\'\'; return true;" onMouseOver="window.status=\'' . $_lang['remove_date'] . '\'; return true;" onMouseOut="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand;">
                <img src="' . $_style["icons_cal_nodate"] . '" width="16" height="16" border="0" alt="' . $_lang['remove_date'] . '" /></a>
                ' . $help . '
				</td></tr>
				<tr' . $row_style . '>
					<td></td>
					<td style="color: #555;font-size:10px"><em>' . $modx->config['datetime_format'] . ' HH:MM:SS</em></td>
				</tr>';
				break;
			case 'richtext':
			case 'donthit':
			case 'searchable':
			case 'cacheable':
			case 'syncsite':
			case 'alias_visible':
			case 'isfolder':
			case 'hidemenu':	
				if($name == 'richtext') {
					$value = $content[$name] == 0 && $_REQUEST['a'] == '27' ? 0 : 1;
					$checked = $value ? "checked" : '';
				} elseif($name == 'donthit' || $name == 'hidemenu') {
					$value = ($content[$name] == 0) ? 0 : 1;
					$checked = !$value ? "checked" : '';
				} elseif($name == 'searchable') {
					$value = (isset($content[$name]) && $content[$name] == 1) || (!isset($content[$name]) && $search_default == 1) ? 1 : 0;
					$checked = $value ? "checked" : '';
				} elseif($name == 'cacheable') {
					$value = (isset($content[$name]) && $content[$name] == 1) || (!isset($content[$name]) && $cache_default == 1) ? 1 : 0;
					$checked = $value ? "checked" : '';
				} elseif($name == 'syncsite') {
					$value = '1';
					$checked = $value ? "checked" : '';
				} elseif($name == 'alias_visible') {
					$value = (!isset($content[$name]) || $content[$name] == 1) ? 1 : 0;
					$checked = $value ? "checked" : '';
				} elseif($name == 'isfolder') {
					$value = ($content[$name] == 1 || $_REQUEST['a'] == '85' || $_REQUEST[$name] == '1') ? 1 : 0;
					$checked = $value ? "checked" : '';
				} else {
					$value = ($content[$name] == 1) ? 1 : 0;
					$checked = $value ? "checked" : '';
				}
				$field .= '<tr' . $row_style . '>
                <td width="' . $title_width . '">' . $title . '</td>
                <td><input name="' . $name . 'check" type="checkbox" class="checkbox" ' . $checked . ' onClick="changestate(document.mutate.' . $name . ');" />
                <input type="hidden" name="' . $name . '" value="' . $value . '" onChange="documentDirty=true;" />
                ' . $help . '
				</td></tr>';
				break;
			case 'type':
				if($_SESSION['mgrRole'] == 1 || $_REQUEST['a'] != '27' || $_SESSION['mgrInternalKey'] == $content['createdby']) {
					$field .= '<tr' . $row_style . '>
				    <td width="' . $title_width . '">' . $title . '</td>
					<td><select name="type" class="inputBox" onChange="documentDirty=true;">
					<option value="document"' . (($content['type'] == "document" || $_REQUEST['a'] == '85' || $_REQUEST['a'] == '4') ? ' selected="selected"' : "") . '>' . $_lang["resource_type_webpage"] . '</option>
					<option value="reference"' . (($content['type'] == "reference" || $_REQUEST['a'] == '72') ? ' selected="selected"' : "") . '>' . $_lang["resource_type_weblink"] . '</option>
					</select>
					' . $help . '
					</td></tr>';
				} else {
					if($content['type'] != 'reference' && $_REQUEST['a'] != '72') {
						$field .= '<input type="hidden" name="type" value="document" />';
					} else {
						$field .= '<input type="hidden" name="type" value="reference" />';
					}
				}
				break;
			case 'contentType':
				if($_SESSION['mgrRole'] == 1 || $_REQUEST['a'] != '27' || $_SESSION['mgrInternalKey'] == $content['createdby']) {
					$field .= '<tr' . $row_style . '>
					<td width="' . $title_width . '">' . $title . '</td>
					<td><select name="contentType" class="inputBox" onChange="documentDirty=true;">';
					if(!$content['contentType']) {
						$content['contentType'] = 'text/html';
					}
					$custom_contenttype = (isset($custom_contenttype) ? $custom_contenttype : "text/html,text/plain,text/xml");
					$ct = explode(",", $custom_contenttype);
					for($i = 0; $i < count($ct); $i++) {
						$field .= '<option value="' . $ct[$i] . '"' . ($content['contentType'] == $ct[$i] ? ' selected="selected"' : '') . '>' . $ct[$i] . "</option>";
					}
					$field .= '</select>
					' . $help . '
					</td></tr>';
				} else {
					if($content['type'] != 'reference' && $_REQUEST['a'] != '72') {
						$field .= '<input type="hidden" name="contentType" value="' . (isset($content['contentType']) ? $content['contentType'] : "text/html") . '" />';
					} else {
						$field .= '<input type="hidden" name="contentType" value="text/html" />';
					}
				}
				break;
			case 'content_dispo':
				if($_SESSION['mgrRole'] == 1 || $_REQUEST['a'] != '27' || $_SESSION['mgrInternalKey'] == $content['createdby']) {
					$field .= '<tr' . $row_style . '>
				        <td width="' . $title_width . '">' . $title . '</td>
						<td><select name="content_dispo" size="1" onChange="documentDirty=true;" class="inputBox">
						<option value="0"' . (!$content['content_dispo'] ? ' selected="selected"' : '') . '>' . $_lang['inline'] . '</option>
						<option value="1"' . ($content['content_dispo'] == 1 ? ' selected="selected"' : '') . '>' . $_lang['attachment'] . '</option>
						</select>
						' . $help . '
						</td></tr>';
				} else {
					if($content['type'] != 'reference' && $_REQUEST['a'] != '72') {
						$field .= '<input type="hidden" name="content_dispo" value="' . (isset($content['content_dispo']) ? $content['content_dispo'] : '0') . '" />';
					}
				}
				break;
			default:
				$field .= '<tr' . $row_style . '>';
				$field .= $data['field']['title'] ? '<td width="' . $title_width . '">' . $title . '</td><td>' : '<td colspan="2">';
				$field .= '<input name="' . $name . '" type="text" maxlength="255" value="' . $modx->htmlspecialchars(stripslashes($content[$name])) . '" class="inputBox" onChange="documentDirty=true;" spellcheck="true" />
				' . $help . '
				</td></tr>';
		}
		if(!$data['field']['hide'] && $field) {
			$field .= ContentFieldSplit();
		}
	}
	return $field;
}

/////////////////
if(isset($_REQUEST['newtemplate'])) {
	$template = $_REQUEST['newtemplate'];
} else {
	if(isset($content['template'])) {
		$template = $content['template'];
	} else {
		$template = getDefaultTemplate();
	}
}

$mutate_content_fields = array(
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
					'title' => 'Родительская категория'
					/*$_lang['resource_parent']*/,
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
	'Seo' => array(
		'title' => 'SEO',
		'roles' => '',
		'hide' => '',
		'fields' => array(
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
					'title' => 'Дочерние ресурсы отображаются в дереве'
					/*$_lang['track_visitors_title']*/,
					'help' => 'Это поле используется для папок с большим числом вложенных страниц'
					/*$_lang['resource_opt_trackvisit_help']*/,
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

$render_template = $modx->runSnippet('mutate_content_template_' . $template);
$render_template_default = $modx->runSnippet('mutate_content_template_default');

if($render_template) {
	$mutate_content_fields = $render_template;
}
if(!$render_template && $render_template_default) {
	$mutate_content_fields = $render_template_default;
}

if($modx->Event->name == 'OnDocFormTemplateRender') {
	$showTvImage = $showTvImage == 'yes' ? true : false;
	// Variables
	if(($content['type'] == 'document' || $_REQUEST['a'] == '4') || ($content['type'] == 'reference' || $_REQUEST['a'] == 72)) {
		$rs = $modx->db->select("
				DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value", "" . $modx->getFullTableName('site_tmplvars') . " AS tv
				INNER JOIN " . $modx->getFullTableName('site_tmplvar_templates') . " AS tvtpl ON tvtpl.tmplvarid = tv.id
				LEFT JOIN " . $modx->getFullTableName('site_tmplvar_contentvalues') . " AS tvc ON tvc.tmplvarid=tv.id AND tvc.contentid='" . $id . "'
				LEFT JOIN " . $modx->getFullTableName('site_tmplvar_access') . " AS tva ON tva.tmplvarid=tv.id", "tvtpl.templateid='" . $template . "' 
				AND (1='" . $_SESSION['mgrRole'] . "' 
				OR ISNULL(tva.documentgroup)" . (!$docgrp ? '' : " OR tva.documentgroup IN (" . $docgrp . ")") . ")", 'tvtpl.rank, tv.rank, tv.id');
		$limit = $modx->db->getRecordCount($rs);
		if($limit > 0) {
			require_once(MODX_MANAGER_PATH . 'includes/tmplvars.inc.php');
			require_once(MODX_MANAGER_PATH . 'includes/tmplvars.commands.inc.php');
			$i = 0;
			while($row = $modx->db->getRow($rs)) {
				// Go through and display all Template Variables
				if($row['type'] == 'richtext' || $row['type'] == 'htmlarea') {
					// Add richtext editor to the list
					if(is_array($replace_richtexteditor)) {
						$replace_richtexteditor = array_merge($replace_richtexteditor, array(
							"tv" . $row['id']
						));
					} else {
						$replace_richtexteditor = array(
							"tv" . $row['id']
						);
					}
				}
				foreach($mutate_content_fields as $k => $v) {
					if(isset($v['fields'][$row['name']])) {
						$mutate_content_fields[$k]['fields'][$row['name']] = array(
							'tv' => $row
						);
						if(isset($v['fields'][$row['name']]['tv']['help'])) {
							$mutate_content_fields[$k]['fields'][$row['name']]['tv']['help'] = $v['fields'][$row['name']]['tv']['help'];
						}
						if(isset($v['fields'][$row['name']]['tv']['title'])) {
							$mutate_content_fields[$k]['fields'][$row['name']]['tv']['caption'] = $v['fields'][$row['name']]['tv']['title'];
							$mutate_content_fields[$k]['fields'][$row['name']]['tv']['description'] = '';
						}
						if(isset($v['fields'][$row['name']]['tv']['hide'])) {
							$mutate_content_fields[$k]['fields'][$row['name']]['tv']['hide'] = 1;
						}
						unset($row);
					}
				}
				if($row['id']) {
					$mutate_content_fields['General']['fields'][$row['name']] = array(
						'tv' => $row
					);
				}
			}
		}
	}
	// end Variables


	$output = '';
	$output .= $modx->db->getValue($rs); //
	$output .= "\n\n\t" . '<!------ templatesEdit ------->' . "\n\t";

	foreach($mutate_content_fields as $tabName => $tab) {
		if($tab['title']) {
			$tabContent = '';
			$counter = 0;
			$split_counter = 0;
			$hide_counter = 0;

			foreach($tab['fields'] as $fieldName => $field) {
				if($fieldName == 'richtext' && ($content['type'] == 'reference' || $_REQUEST['a'] == '72')) {
					$field['field']['hide'] = 1;
				}
				if($field['split']) {
					$tabContent .= '<tr><td colspan="2"><h4><strong class="warning">' . $field['split']['title'] . '</strong></h4></td></tr>';
					$counter++;
					$split_counter++;
					if($field['split']['hide']) {
						$hide_counter++;
					}
				} else {
					if(isset($tab['fields'][$fieldName]['field']) || isset($tab['fields'][$fieldName]['tv']['name'])) {
						$render_field = renderContentField($fieldName, $field, $showTvImage);
						if($render_field) {
							$tabContent .= $render_field;
							$counter++;
							if($field['field']['hide'] || $field['tv']['hide']) {
								$hide_counter++;
							}
						}
					}
				}
			}

			if($hide_counter == $counter) {
				$tab['hide'] = 1;
			}

			if($tabContent && $split_counter != $counter) {
				$output .= '<!-- ' . $tabName . ' -->
					<div class="tab-page" id="tab' . $tabName . '"' . ($tab['hide'] ? ' style="display:none !important"' : '') . '>';
				if(!$tab['hide']) {
					$output .= '<h2 class="tab">' . $tab['title'] . '</h2>
								<script type="text/javascript">tpSettings.addTabPage(document.getElementById("tab' . $tabName . '"));</script>';
				}
				$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . $tabContent . '</table>
					</div><!-- end #tab' . $tabName . ' -->';
			}
		}
	}

	if($showTvImage) {
		$output .= '
		<style>
		.image_for_tv img {float:left;vertical-align:middle;max-height:' . $modx->config['thumbHeight'] . 'px;max-width:' . $modx->config['thumbWidth'] . 'px;cursor:pointer;}
		</style>
		<script type="text/javascript">
		function renderTvImageCheck(id) {
			var el = document.getElementById(id);
			var img = document.getElementById("image_for_" + id);
			if (img != null && el.value && img.src != "' . MODX_SITE_URL . '" + el.value) {
				img.src = "' . MODX_SITE_URL . '" + el.value;
				img.onerror = function() {
					img.style.display = "none";
				}
				img.onload = function() {
					img.style.display = "block";
				}
			} else if (!el.value) {
				img.style.display = "none";
			}
		}

		var lastImageCtrl_tmp = "";
		setInterval(function() {
			if (lastImageCtrl) {
				lastImageCtrl_tmp = lastImageCtrl;
			} else if (!lastImageCtrl && lastImageCtrl_tmp) {
				renderTvImageCheck(lastImageCtrl_tmp)
			}
		}, 300);
		</script>';
	}

	$output .= '<!------ /templatesEdit/ ------->' . "\n\t";
	unset($mutate_content_fields);
	$modx->Event->output($output);
}
