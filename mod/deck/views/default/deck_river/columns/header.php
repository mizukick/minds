<?php
/**
 * @uses $vars['column_settings'] Settings of this tab
 **/

$column = elgg_extract('column', $vars);

$account = $column->getAccount();

// check if this column can filter content
if ($column_settings['network'] == 'elgg' &&
	in_array($column_settings['type'], array('all', 'friends', 'mine', 'mention', 'groups', 'group', 'group_mention', 'search'))) {
		$has_filter = true;
	} else {
		$has_filter = false;
	}

// set filter
if ($has_filter) {
	$filter = elgg_view('page/layouts/content/deck_river_column_filter', array(
		'column_settings' => $column_settings
	));
} else {
	$filter = '';
}

//delete button
$buttons = elgg_view('output/url', array('text'=>'&#10006;', 'title' => elgg_echo('delete'), 'href' => "#", 'class' => "elgg-column-delete-button delete-tab tooltip s"));

$params = array(
	'text' => elgg_view_icon('refresh'),
	'title' => elgg_echo('deck_river:refresh'),
	'href' => "#",
	'class' => "elgg-column-refresh-button tooltip s prs",
);
$buttons .= elgg_view('output/url', $params);

$buttons .= elgg_view('output/img', array(
	'src' => elgg_get_site_url() . 'mod/elgg-deck_river/graphics/refresh.gif',
	'class' => 'refresh-gif'
));

$params = array(
	'text' => elgg_view_icon('settings-alt'),
	'title' => elgg_echo('deck_river:edit'),
	'href' => "#",
	'class' => "elgg-column-edit-button tooltip s",
);
$buttons .= elgg_view('output/url', $params);

if ($has_filter) {
	$params = array(
		'text' => elgg_view_icon('search'),
		'title' => elgg_echo('deck_river:filter'),
		'href' => "#",
		'class' => "elgg-column-filter-button tooltip s",
	);
	$buttons .= elgg_view('output/url', $params);
}

$title = elgg_echo($column->name);
//$title = is_array($column_settings['title']) ? elgg_echo($column_settings['title'][0], array($column_settings['title'][1])) : elgg_echo($column_settings['title'], array());
$subtitle = $column->getAccount()->name;
if ($subtitle) $subtitle = '<span>' . $subtitle . '</span>';

if (isset($column->types_filter) || isset($column->subtypes_filter)) {
	$hidden = '';
} else {
	$hidden = 'hidden';
}
$filtered = '<span class="filtered link '.$hidden.'">' . elgg_echo('river:filtred'). '</span>';

if(isset($column->data) && $column->data)
	$data = elgg_format_attributes($column->data);
else 
	$data = '';
echo <<<HTML
<div class="message-box"><div class="column-messages"></div></div>
<ul class="column-header gwfb {$account->screen_name} $account->network" $data>
	<li>
		$buttons
		<div class="count hidden"></div>
		<div class="column-handle">
			<h3 class="title">$title</h3><br/>
			<h6 class="subtitle">{$subtitle}{$filtered}</h6>
		</div>
	</li>
</ul>
$filter
HTML;
