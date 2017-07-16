<?php
/**
 * templatesEdit2
 *
 * render fields and tabs in edit docs
 *
 * @category         plugin
 * @version          2.2
 * @license          http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @package          modx
 * @internal         @events OnDocFormTemplateRender
 * @internal         @modx_category Manager and Admin
 * @internal         @properties &showTvImage=Show images in TV;list;yes,no;yes &excludeTvCategory=Exclude TVs in categories;text;
 * @reportissues     https://github.com/64j/templatesEdit2
 * @documentation    Official docs https://github.com/64j/templatesEdit2
 * @author           http://wexar.ru/
 * @lastupdate       15/07/2017
 */

global $_lang, $content, $docgrp, $which_editor, $replace_richtexteditor, $richtexteditorIds, $richtexteditorOptions;

function strClean($str) {
	return htmlspecialchars($str, ENT_QUOTES);
}

function renderTypeImage($value, $tvid, $width) {
	$src = $value ? MODX_SITE_URL . $value : '';
	$out = '<script type="text/javascript">
	var tvImageInput_' . $tvid . ' = document.getElementById("tv' . $tvid . '");
	tvImageInput_' . $tvid . '.onkeyup = tvImageInput_' . $tvid . '.oncut = tvImageInput_' . $tvid . '.oninput = function() {
		lastImageCtrl = "tv' . $tvid . '";
		lastImageCtrl_tmp = "";
		renderTvImageCheck(this.id);
	}
	</script>';
	$out .= '<div class="image_for_tv" style="' . (!$value ? 'display:none; ' : '') . 'width: ' . $width . 'px; height: ' . $width . 'px; line-height: ' . ($width - 15) . 'px; padding: 5px; margin: .1rem .1rem 0 0; border: 1px #ccc solid; background-color: #fff; text-align: center;"><img id="image_for_tv' . $tvid . '" src="' . $src . '" onclick="BrowseServer(\'tv' . $tvid . '\')" style="display: inline-block; vertical-align: middle; max-height: 100%; max-width: 100%; cursor: pointer;" /></div>';
	return $out;
}

function renderContentField($name, $data, $showTvImage) {
	global $modx, $_style, $_lang, $content, $site_name, $use_editor, $which_editor, $editor, $replace_richtexteditor, $richtexteditorIds, $richtexteditorOptions, $search_default, $publish_default, $cache_default, $closeOptGroup, $custom_contenttype;
	$field = '';
	list($item_title, $item_description) = explode('||||', $data['field']['title']);
	$fieldDescription = (!empty($item_description)) ? '<br><span class="comment">' . $item_description . '</span>' : '';
	$hide = $data['field']['hide'] || $data['tv']['hide'] ? true : false;
	$roles = !empty($data['field']['roles']) ? $data['field']['roles'] : (!empty($data['tv']['roles']) ? $data['tv']['roles'] : '');

	if($roles) {
		$roles = explode(',', $data['field']['roles']);
		foreach($roles as $role) {
			if(($role[0] != '!' && trim($role) != $_SESSION['mgrRole']) || ($role[0] == '!' && ltrim($role, '!') == $_SESSION['mgrRole'])) {
				$hide = true;
			}
		}
	}

	$row_style = $hide ? ' style="display:none;"' : '';
	$mx_can_pub = $modx->hasPermission('publish_document') ? '' : 'disabled="disabled" ';
	if(isset($data['tv'])) {
		$title = '<label for="tv' . $data['tv']['id'] . '" class="warning">' . $item_title . '</label>' . $fieldDescription;
		$help = !empty($data['tv']['help']) ? '<i class="fa fa-question-circle" data-tooltip="' . stripcslashes($data['tv']['help']) . '"></i>' : '';
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
			$renderTV = renderTypeImage($tvPBV, $data['tv']['id'], $modx->config['thumbWidth']);
		}
		$field .= renderFormElement($data['tv']['type'], $data['tv']['id'], $data['tv']['default_text'], $data['tv']['elements'], $tvPBV, '', $data['tv']) . $renderTV;
		if(($data['tv']['type'] == 'text' || $data['tv']['type'] == 'number') && $data['tv']['elements']) {
			$elements = explode('||', $data['tv']['elements']);
			$field .= '<datalist id="list' . $data['tv']['id'] . '">';
			foreach($elements as $element_value) {
				$field .= '<option>' . $element_value . '</option> ';
			}
			$field .= '</datalist>';
			$field .= '<script type="text/javascript">
			document.mutate.tv' . $data['tv']['id'] . '.setAttribute("list", "list' . $data['tv']['id'] . '");
			document.mutate.tv' . $data['tv']['id'] . '.style.paddingRight = 0;
			</script>';
		}
	}
	if(isset($data['field'])) {
		$title = '<label for="' . $name . '" class="warning">' . $item_title . '</label>' . $fieldDescription;
		$help = !empty($data['field']['help']) ? '<i class="fa fa-question-circle" data-tooltip="' . stripcslashes($data['field']['help']) . '"></i>' : '';
		switch($name) {
			case 'weblink':
				if($content['type'] == 'reference' || $_REQUEST['a'] == '72') {
					$field .= '
				<i id="llock" class="' . $_style["actions_chain"] . '" onclick="enableLinkSelection(!allowLinkSelection);"></i>
				<input id="ta" class="form-control" name="ta" type="text" maxlength="255" value="' . (!empty($content['content']) ? stripslashes($content['content']) : "http://") . '" onChange="documentDirty=true;" />
				';
				}
				break;
			case 'introtext':
				$field .= '<textarea id="introtext" class="form-control" name="introtext" rows="3" wrap="soft" onChange="documentDirty=true;">' . $modx->htmlspecialchars(stripslashes($content['introtext'])) . '</textarea>';
				break;
			case 'template':
				$field .= '<select id="template" class="form-control" name="template" onChange="templateWarning();">
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
				';
				break;
			case 'menusort':
			case 'menuindex':
				$field .= '
				<span class="input-group">
					<span class="input-group-addon">
						<a class="text-muted" href="javascript:;" onclick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+\'\')-1;elm.value=v>0? v:0;elm.focus();documentDirty=true;return false;">
							<i class="fa fa-minus"></i>
						</a>
					</span>
					<input id="' . $name . '" class="form-control" name="menuindex" type="text" maxlength="6" value="' . $content['menuindex'] . '" onchange="documentDirty=true;" />
					<span class="input-group-addon">
						<a class="text-muted" href="javascript:;" onclick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+\'\')+1;elm.value=v>0? v:0;elm.focus();documentDirty=true;return false;">
							<i class="fa fa-plus"></i>
						</a>
					</span>
				</span>
				';
				break;
			case 'parent':
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
					<div class="form-control">
						<i id="plock" class="' . $_style["actions_folder"] . '" onclick="enableParentSelection(!allowParentSelection);"></i>
						<b><span id="parentName">' . (isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']) . ' (' . $parentname . ')</span></b>
						<input id="parent" name="parent" type="hidden" value="' . (isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']) . '" onChange="documentDirty=true;" />
					</div>
					';
				break;
			case 'content':
				if($content['type'] == 'document' || $_REQUEST['a'] == '4') {
					$field .= '<textarea id="ta" class="form-control" name="ta" rows="20" wrap="soft" onChange="documentDirty=true;">' . $modx->htmlspecialchars($content['content']) . '</textarea>';
					if(($content['richtext'] == 1 || $_REQUEST['a'] == '4') && $use_editor == 1) {
						// Richtext-[*content*]
						$richtexteditorIds = array();
						$richtexteditorOptions = array();
						$replace_richtexteditor = is_array($replace_richtexteditor) ? array_merge($replace_richtexteditor, array('ta')) : array('ta');
						$richtexteditorIds[$which_editor] = is_array($richtexteditorIds[$which_editor]) ? array_merge($richtexteditorIds[$which_editor], array('ta')) : array('ta');
						$richtexteditorOptions[$which_editor]['ta'] = '';
					}
				}
				break;
			case 'published':
				$field .= '
				<input id="published" class="form-checkbox" name="publishedcheck" type="checkbox" ' . ((isset($content['published']) && $content['published'] == 1) || (!isset($content['published']) && $publish_default == 1) ? "checked" : '') . ' onClick="changestate(document.mutate.published);" ' . $mx_can_pub . ' />
                <input type="hidden" name="published" value="' . ((isset($content['published']) && $content['published'] == 1) || (!isset($content['published']) && $publish_default == 1) ? 1 : 0) . '" />
				';
				break;
			case 'pub_date':
			case 'unpub_date':
			case 'createdon':
			case 'editedon':
				$field .= '
				<div class="input-group">
					<input id="' . $name . '" class="form-control DatePicker" name="' . $name . '" value="' . ($content[$name] == "0" || !isset($content[$name]) ? '' : $modx->toDateFormat($content[$name])) . '" onBlur="documentDirty=true;" placeholder="' . $modx->config['datetime_format'] . ' HH:MM:SS" ' . $mx_can_pub . ' />
					<span class="input-group-addon">
						<a class="text-danger" href="javascript:;" onclick="document.mutate.' . $name . '.value=\'\'; return true;" onmouseover="window.status=\'' . $_lang['remove_date'] . '\'; return true;">
							<i class="' . $_style["actions_calendar_delete"] . '" title="' . $_lang['remove_date'] . '"></i>
						</a>
					</span>
				</div>
				';
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
				$field .= '
				<input id="' . $name . '" class="form-checkbox" name="' . $name . 'check" type="checkbox" ' . $checked . ' onClick="changestate(document.mutate.' . $name . ');" />
                <input type="hidden" name="' . $name . '" value="' . $value . '" onChange="documentDirty=true;" />
				';
				break;
			case 'type':
				if($_SESSION['mgrRole'] == 1 || $_REQUEST['a'] != '27' || $_SESSION['mgrInternalKey'] == $content['createdby']) {
					$field .= '
					<select id="type" class="form-control" name="type" onChange="documentDirty=true;">
						<option value="document"' . (($content['type'] == "document" || $_REQUEST['a'] == '85' || $_REQUEST['a'] == '4') ? ' selected="selected"' : "") . '>' . $_lang["resource_type_webpage"] . '</option>
						<option value="reference"' . (($content['type'] == "reference" || $_REQUEST['a'] == '72') ? ' selected="selected"' : "") . '>' . $_lang["resource_type_weblink"] . '</option>
					</select>
					';
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
					$field .= '
					<select id="contentType" class="form-control" name="contentType" onChange="documentDirty=true;">';
					if(!$content['contentType']) {
						$content['contentType'] = 'text/html';
					}
					$custom_contenttype = (isset($custom_contenttype) ? $custom_contenttype : "text/html,text/plain,text/xml");
					$ct = explode(",", $custom_contenttype);
					for($i = 0; $i < count($ct); $i++) {
						$field .= '<option value="' . $ct[$i] . '"' . ($content['contentType'] == $ct[$i] ? ' selected="selected"' : '') . '>' . $ct[$i] . "</option>";
					}
					$field .= '</select>
					';
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
					$field .= '
					<select id="content_dispo" class="form-control" name="content_dispo" onChange="documentDirty=true;">
						<option value="0"' . (!$content['content_dispo'] ? ' selected="selected"' : '') . '>' . $_lang['inline'] . '</option>
						<option value="1"' . ($content['content_dispo'] == 1 ? ' selected="selected"' : '') . '>' . $_lang['attachment'] . '</option>
					</select>
					';
				} else {
					if($content['type'] != 'reference' && $_REQUEST['a'] != '72') {
						$field .= '<input type="hidden" name="content_dispo" value="' . (isset($content['content_dispo']) ? $content['content_dispo'] : '0') . '" />';
					}
				}
				break;
			default:
				$field .= '
				<input id="' . $name . '" class="form-control' . ($name == 'pagetitle' ? ' form-control-lg' : '') . '" name="' . $name . '" type="text" maxlength="255" value="' . $modx->htmlspecialchars(stripslashes($content[$name])) . '" onChange="documentDirty=true;" spellcheck="true" />';
		}
	}

	$out = '';
	if(!empty($field)) {
		if($name == 'content' || $name == 'introtext') {
			$out .= '<div class="row form-group"' . $row_style . '>';
			if(!empty($data['field']['title'])) {
				$out .= '<div class="navbar navbar-editor">' . $title . $help;
				//				if($name == 'content') {
				//					$out .= '
				//						<label class="float-xs-right">' . $_lang['which_editor_title'] . '
				//							<select id="which_editor" class="form-control form-control-sm" size="1" name="which_editor" onchange="changeRTE();">
				//							<option value="none">' . $_lang['none'] . '</option>';
				//								// invoke OnRichTextEditorRegister event
				//								$evtOut = $modx->invokeEvent("OnRichTextEditorRegister");
				//								if(is_array($evtOut)) {
				//									for($i = 0; $i < count($evtOut); $i++) {
				//										$editor = $evtOut[$i];
				//										$out .= '<option value="' . $editor . '"' . ($modx->config['which_editor'] == $editor ? ' selected="selected"' : '') . '>' . $editor . '</option>';
				//									}
				//								}
				//					$out .= '
				//							</select>
				//						</label>
				//					';
				//				}
				$out .= '</div>';
			}
			$out .= '<div class="section-editor clearfix">';
		} else {
			$out .= '<div class="row form-row"' . $row_style . '>';
			if(!empty($data['field']['title']) || !empty($data['tv']['caption'])) {
				$out .= '<div class="col-md-3 col-lg-2">' . $title . $help . '</div>';
				$out .= '<div class="col-md-9 col-lg-10">';
			} else {
				$out .= '<div class="col-sx-12">';
			}
		}
		$out .= $field;
		$out .= '</div>';
		$out .= '</div>';
	}

	return $out;
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
					'title' => $_lang['resource_parent'],
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
					'title' => $_lang['resource_alias'],
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

$render_template = $modx->runSnippet('mutate_content_template_' . $template);
$render_template_default = $modx->runSnippet('mutate_content_template_default');

if($render_template) {
	$mutate_content_fields = $render_template;
} else if($render_template_default) {
	$mutate_content_fields = $render_template_default;
}

if($modx->Event->name == 'OnDocFormTemplateRender') {
	$showTvImage = $showTvImage == 'yes' ? true : false;
	$excludeTvCategory = $excludeTvCategory ? explode(',', $excludeTvCategory) : array();
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
					$replace_richtexteditor = is_array($replace_richtexteditor) ? array_merge($replace_richtexteditor, array("tv" . $row['id'])) : array("tv" . $row['id']);
					$richtexteditorIds[$which_editor] = is_array($richtexteditorIds[$which_editor]) ? array_merge($richtexteditorIds[$which_editor], array("tv" . $row['id'])) : array("tv" . $row['id']);
					$richtexteditorOptions[$which_editor]["tv" . $row['id']] = '';
				}

				foreach($mutate_content_fields as $k => $v) {
					if(isset($v['fields']['tv' . $row['id']])) {
						$position = array_search('tv' . $row['id'], array_keys($v['fields']));
						$mutate_content_fields[$k]['fields'] = array_slice($mutate_content_fields[$k]['fields'], 0, $position, true) + array($row['name'] => $v['fields']['tv' . $row['id']]) + array_slice($mutate_content_fields[$k]['fields'], $position, count($mutate_content_fields[$k]['fields']), true);
						unset($mutate_content_fields[$k]['fields']['tv' . $row['id']]);
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
				if($row['id'] && !in_array($row['category'], $excludeTvCategory)) {
					$mutate_content_fields['General']['fields'][$row['name']] = array(
						'tv' => $row
					);
				}
			}
		}
	}
	// end Variables

	$output = '';
	$output .= "\n\n\t" . '<!------ templatesEdit ------->' . "\n\t";
	$output .= '<style>
	.warning + [data-tooltip].fa-question-circle { margin: 0.3rem 0.5rem 0; }
	input[name*=date] + .input-group-addon, input[name=createdon] + .input-group-addon, input[name=editedon] + .input-group-addon, input[name=menuindex] + .input-group-addon { float: left; width: auto }
	</style>';

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
				$output .= $tabContent;
				$output .= '
					</div><!-- end #tab' . $tabName . ' -->';
			}
		}
	}

	if($showTvImage) {
		$output .= '
		<script type="text/javascript">
		var lastImageCtrl_tmp = "";
		setInterval(function() {
			if (typeof lastImageCtrl != "undefined") {
				if(lastImageCtrl) {
					lastImageCtrl_tmp = lastImageCtrl;
				}
				else if(!lastImageCtrl && lastImageCtrl_tmp) {
					renderTvImageCheck(lastImageCtrl_tmp);
				}
			}
		}, 300);
		
		function renderTvImageCheck(id) {
			var el = document.getElementById(id), img;
			if(img = document.getElementById("image_for_" + id)) {
				if (el.value) {
					img.src = "' . MODX_SITE_URL . '" + el.value;
					img.onerror = function() {
						this.parentElement.style.display = "none";
					}     
					img.onload = function() {
						this.parentElement.style.display = "block";
					}
				} else {
					 img.parentElement.style.display = "none";
				}
			}
		}
		</script>';
	}

	$output .= '<!------ /templatesEdit/ ------->' . "\n\t";
	unset($mutate_content_fields);
	$modx->Event->output($output);
}
