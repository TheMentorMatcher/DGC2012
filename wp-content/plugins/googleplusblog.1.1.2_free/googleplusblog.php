<?php
	 /*
	 Plugin Name: Google+Blog
	 Plugin URI: http://www.minimali.se/google+blog/
	 Description: A plugin to import your posts from Google+.
	 Version: 1.1.2
	 Author: Daniel Treadwell
	 Author URI: http://www.minimali.se/
	 */

@date_default_timezone_set(get_option('timezone_string'));

$options = get_option('googleplusblog_options');

$GPB_API_KEY = @$options['api_key'];
$GPB_PROFILE_ID = @$options['profile_id'];
$GPB_POST_LIMIT = @$options['post_limit'];
$GPB_POST_STATUS = @$options['post_status'];
$GPB_POST_CATEGORIES = @$options['post_categories'];
$GPB_POST_TAGS = @$options['post_tags'];
$GPB_POST_OVERWRITE = @$options['post_overwrite'];
$GPB_EXCLUDED_CATEGORY = @$options['exclusion_category'];
$GPB_POST_AUTHOR = @$options['post_author'];

$GPB_POST_LINK = @$options['post_link'];
$GPB_POST_RESHARES = @$options['post_reshares'];
$GPB_POST_IMPORT_TRASHED = @$options['import_trashed'];
$GPB_POST_IMPORT_TAG = @$options['post_import_tag'];
$GPB_POST_EXCLUDE_TAG = @$options['post_exclude_tag'];

$GPB_ERRORS = @$options['errors'];
$GPB_LOG = @$options['log'];
$GPB_LOG_SIZE = 5;

$GPB_TOTAL_IMPORTED_POSTS = 0;
$GPB_TOTAL_IMPORTED_COMMENTS = 0;
$GPB_TOTAL_UPDATED_POSTS = 0;
$GPB_TOTAL_IGNORED_POSTS = 0;

function googleplusblog_canonical()
{
	global $post, $GPB_PROFILE_ID;
	if (is_single())
	{
		$url = get_post_meta($post->ID,'_googleplus_url',true);
		if ($url)
		{
			echo "<link rel='canonical' href='$url' />";
			remove_action('wp_head','rel_canonical');
		}
		else
		{
			add_action('wp_head','rel_canonical');	
		}			
	}	
	else
	{
		add_action('wp_head','rel_canonical');	
	}		
}
function googleplusblog_remove_existing_canonical()
{
	remove_action('wp_head','rel_canonical');
}
add_action('init', 'googleplusblog_remove_existing_canonical');
add_action('init', 'googleplusblog_canonical');

add_action('wp_head', 'googleplusblog_canonical');

function googleplusblog_remove_from_home()
{
	global $query_string, $posts, $GPB_EXCLUDED_CATEGORY;
	if ($GPB_EXCLUDED_CATEGORY >= 0)
	{
		if (!is_category($GPB_EXCLUDED_CATEGORY) && is_home())
		{
    			$posts = query_posts($query_string.'&cat=-'.$GPB_EXCLUDED_CATEGORY);
		}
		return $posts;
	}
}

add_action('wp_head', 'googleplusblog_remove_from_home');

#define('FOOTER','');
define('FOOTER',"<br /><br /><i>Post imported by Google+Blog.  Created By <a href='http://minimali.se/'>Daniel Treadwell</a>.</i>");


register_activation_hook(__FILE__, 'googleplusblog_activation');
register_deactivation_hook(__FILE__,'googleplusblog_deactivation');

add_action('googleplusblog_hook','googleplusblog_function');
add_action('admin_notices','googleplusblog_settings_valid');
add_action('admin_menu', 'googleplusblog_admin');



function googleplusblog_admin() 
{
	add_options_page('Google+Blog Options', 'Google+Blog Options', 'manage_options', 'googleplusblog_options', 'googleplusblog_options');
}


function googleplusblog_options() 
{
?>
	<div class="wrap">
	<h2>Google+Blog</h2>
	Options relating to the plugin.
	<form action="options.php" method="post">
<?php
	settings_fields('googleplusblog_options');
	do_settings_sections('googleplusblog_options');
	$minutes = round((wp_next_scheduled('googleplusblog_hook')-time())/60,0);
	$minutes = $minutes > 0 ? $minutes.' minutes' : 'Running Now'; 
?>
	<p class='submit'>
		<input type='submit' name='submit' value='<?php _e('Update Options &raquo;'); ?>' /> 
		&nbsp;Import posts on options update: <input type='checkbox' name='googleplusblog_options[run]' value='1' />
	</p>
	</form>
	<strong>Next scheduled import will run in: </strong><?=$minutes?>.
	<p>
<?
	googleplusblog_log();
?>
	</p>
	</div>
<?php
}

add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init(){

	register_setting( 'googleplusblog_options', 'googleplusblog_options', 'plugin_options_validate' );
	add_settings_section('gpb_plugin_options', 'Plugin settings', create_function('','echo "Settings related to the functioning of the plugin.";'), 'googleplusblog_options');
	add_settings_section('gpb_wordpress_options', 'Wordpress settings', create_function('','echo "Predefined settings used when posts are created by the plugin.";'), 'googleplusblog_options');

	add_settings_field('gpb_api_key', 'API Key<br /><small>(via <a href="http://code.google.com/apis/console/">Google API Console</a>)</small>', 'gpb_option_api_key', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_profile_id', 'Google+ Profile Id<br /><small>(The 21 digit number in your Profile URL. Comma separated for multiple)</small>', 'gpb_option_profile_id', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_limit', 'Post History<br /><small>(The amount of posts to update)</small>', 'gpb_option_post_limit', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_overwrite', 'Post Overwrite<br /><small>(Update previously imported posts)</small>', 'gpb_option_post_overwrite', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_exclusion_category', 'Exclusion Category<br /><small>(Remove posts in this category from the front page)</small>', 'gpb_option_exclusion_category', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_import_trashed', 'Import Trashed:<br /><small>(Import posts already trashed)</small>', 'gpb_option_post_import_trashed', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_link', 'Display Google+ Link:<br /><small>(Link back to Google+ post)</small>', 'gpb_option_post_link', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_reshares', 'Display Reshares:<br /><small>(Show reshare count on posts)</small>', 'gpb_option_post_reshares', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_import_tag', 'Import Tag:<br /><small>(Only import posts with this hashtag)</small>', 'gpb_option_post_import_tag', 'googleplusblog_options', 'gpb_plugin_options');
	add_settings_field('gpb_post_exclude_tag', 'Exclude Tag:<br /><small>(Do not import posts with this hashtag)</small>', 'gpb_option_post_exclude_tag', 'googleplusblog_options', 'gpb_plugin_options');

	add_settings_field('gpb_post_status', 'Status', 'gpb_option_post_status', 'googleplusblog_options', 'gpb_wordpress_options');
	add_settings_field('gpb_post_author', 'Author', 'gpb_option_post_author', 'googleplusblog_options', 'gpb_wordpress_options');
	add_settings_field('gpb_post_categories', 'Categories', 'gpb_option_post_categories', 'googleplusblog_options', 'gpb_wordpress_options');
	add_settings_field('gpb_post_tags', 'Tags:<br /><small>(Comma seperated)</small>', 'gpb_option_post_tags', 'googleplusblog_options', 'gpb_wordpress_options');


}

function gpb_option_api_key() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();
	$options['api_key'] = array_key_exists('api_key',$options) ? $options['api_key'] : '';

	echo "<input id='gpb_api_key' name='googleplusblog_options[api_key]' size='40' type='text' value='".$options['api_key']."' />";
}

function gpb_option_profile_id() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();
	$options['profile_id'] = array_key_exists('profile_id',$options) ? $options['profile_id'] : '';

	echo "<input id='gpb_profile_id' name='googleplusblog_options[profile_id]' size='25' type='text' value='".$options['profile_id']."' />";
}

function gpb_option_post_limit() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_limit'] = array_key_exists('post_limit',$options) ? $options['post_limit'] : 20;

	echo "<select id='gpb_post_limit' name='googleplusblog_options[post_limit]'>
			<option ".(@$options['post_limit'] == 10 ? 'selected' : '').">10</option>
			<option ".(@$options['post_limit'] == 20 ? 'selected' : '').">20</option>
			<option ".(@$options['post_limit'] == 30 ? 'selected' : '').">30</option>
			<option ".(@$options['post_limit'] == 40 ? 'selected' : '').">40</option>
			<option ".(@$options['post_limit'] == 50 ? 'selected' : '').">50</option>
			<option ".(@$options['post_limit'] == 60 ? 'selected' : '').">60</option>			
			<option ".(@$options['post_limit'] == 80 ? 'selected' : '').">80</option>			
			<option ".(@$options['post_limit'] == 100 ? 'selected' : '').">100</option>			
			<option ".(@$options['post_limit'] == 200 ? 'selected' : '').">200</option>			

			</select>";
}

function gpb_option_exclusion_category() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['exclusion_category'] = array_key_exists('exclusion_category',$options) ? $options['exclusion_category'] : -1;

	echo "<select id='gpb_exclusion_category' name='googleplusblog_options[exclusion_category]' style='width:150px;'>";
		echo "<option value='-1'></option>";
	foreach (get_categories(array('hide_empty' => 0)) as $category)
	{
		echo "<option value='$category->cat_ID' ".($category->cat_ID == $options['exclusion_category'] ? 'selected' : '').">$category->name</option>";
	}
	echo "</select>";
}



function gpb_option_post_overwrite() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_overwrite'] = array_key_exists('post_overwrite',$options) ? $options['post_overwrite'] : '1';

	echo "<input type='checkbox' name='googleplusblog_options[post_overwrite]' value='1' ".($options['post_overwrite'] == '1' ? 'checked' : '')." />";
}

function gpb_option_post_status() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_status'] = array_key_exists('post_status',$options) ? $options['post_status'] : 'publish';

	echo "<select id='gpb_post_status' name='googleplusblog_options[post_status]'>
			<option ".(@$options['post_status'] == 'Publish' ? 'selected' : '').">Publish</option>
			<option ".(@$options['post_status'] == 'Pending' ? 'selected' : '').">Pending</option>
			<option ".(@$options['post_status'] == 'Future' ? 'selected' : '').">Future</option>
			<option ".(@$options['post_status'] == 'Private' ? 'selected' : '').">Private</option>
			<option ".(@$options['post_status'] == 'Draft' ? 'selected' : '').">Draft</option>			
			</select>";
}

function gpb_option_post_author() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_author'] = array_key_exists('post_author',$options) ? $options['post_author'] : '1';

	wp_dropdown_users(array('name' => 'googleplusblog_options[post_author]', 'who' => 'authors', 'selected' => $options['post_author'], 'include_selected' => true));

}

function gpb_option_post_categories() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_categories'] = array_key_exists('post_categories',$options) ? $options['post_categories'] : array('1');

	echo "<select id='gpb_post_categories' name='googleplusblog_options[post_categories][]' multiple='multiple' style='height:100px;width:150px;'>";

	foreach (get_categories(array('hide_empty' => 0)) as $category)
	{
		echo "<option value='$category->cat_ID' ".(in_array($category->cat_ID,$options['post_categories']) ? 'selected' : '').">$category->name</option>";
		
	}
	echo "</select>";
}

function gpb_option_post_tags() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_tags'] = array_key_exists('post_tags',$options) ? $options['post_tags'] : 'Google+';
	echo "<input id='gpb_post_tags' name='googleplusblog_options[post_tags]' size='40' type='text' value='".$options['post_tags']."' />";
}

function gpb_option_post_import_tag() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_import_tag'] = array_key_exists('post_import_tag',$options) ? $options['post_import_tag'] : '';
	echo "<input name='googleplusblog_options[post_import_tag]' size='20' type='text' value='".$options['post_import_tag']."' />";
}

function gpb_option_post_exclude_tag() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_exclude_tag'] = array_key_exists('post_exclude_tag',$options) ? $options['post_exclude_tag'] : '';
	echo "<input name='googleplusblog_options[post_exclude_tag]' size='20' type='text' value='".$options['post_exclude_tag']."' />";
}


function gpb_option_post_import_trashed() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['import_trashed'] = array_key_exists('import_trashed',$options) ? $options['import_trashed'] : '1';

	echo "<input type='checkbox' name='googleplusblog_options[import_trashed]' value='1' ".($options['import_trashed'] == '1' ? 'checked' : '')." />";
}

function gpb_option_post_link() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_link'] = array_key_exists('post_link',$options) ? $options['post_link'] : '1';

	echo "<input type='checkbox' name='googleplusblog_options[post_link]' value='1' ".($options['post_link'] == '1' ? 'checked' : '')." />";
}

function gpb_option_post_reshares() {
	$options = get_option('googleplusblog_options');
	$options = is_array($options) ? $options : array();	
	$options['post_reshares'] = array_key_exists('post_reshares',$options) ? $options['post_reshares'] : '1';
	echo "<input type='checkbox' name='googleplusblog_options[post_reshares]' value='1' ".($options['post_reshares'] == '1' ? 'checked' : '')." />";
}


function plugin_options_validate($input)
{
	global $GPB_ERRORS, $GPB_LOG;	
	#wp_schedule_event(time()+600, 'hourly', 'googleplusblog_hook');

	$input['post_overwrite'] = array_key_exists('post_overwrite',$input) ? ($input['post_overwrite'] == '1' ? '1' : '0') : '0';
	$input['import_trashed'] = array_key_exists('import_trashed',$input) ? ($input['import_trashed'] == '1' ? '1' : '0') : '0';
	$input['post_reshares'] = array_key_exists('post_reshares',$input) ? ($input['post_reshares'] == '1' ? '1' : '0') : '0';
	$input['post_link'] = array_key_exists('post_link',$input) ? ($input['post_link'] == '1' ? '1' : '0') : '0';

	$input['errors'] = $GPB_ERRORS;
	$input['log'] = $GPB_LOG;

	$input['post_import_tag'] = trim(str_replace('#','',$input['post_import_tag']));
	$input['post_exclude_tag'] = trim(str_replace('#','',$input['post_exclude_tag']));

	if (@$input['run'] == 1)
	{
		wp_clear_scheduled_hook('googleplusblog_hook');		
		wp_schedule_event(time(), 'hourly', 'googleplusblog_hook');	
		unset($input['run']);
	}
	return $input;
}

function googleplusblog_activation()
{	

	$options = get_option('googleplusblog_options');
	$options['errors'] = array();
	update_option('googleplusblog_options',$options);
	wp_clear_scheduled_hook('googleplusblog_hook');	
	wp_schedule_event(time()+60, 'hourly', 'googleplusblog_hook');
}

function googleplusblog_deactivation()
{
	wp_clear_scheduled_hook('googleplusblog_hook');
}

function googleplusblog_settings_valid()
{
	global $GPB_API_KEY, $GPB_PROFILE_ID, $GPB_POST_LIMIT, $GPB_POST_STATUS, $GPB_POST_CATEGORIES, $GPB_POST_TAGS;
	$errors = array();
	if (!$GPB_API_KEY)
		$errors[] = 'API Key';
	if (!$GPB_PROFILE_ID)
		$errors[] = 'Profile Id';
	if (!$GPB_POST_LIMIT)
		$errors[] = 'Post History';
	if (!$GPB_POST_STATUS)
		$errors[] = 'Post Status';
	if (!$GPB_POST_CATEGORIES)
		$errors[] = 'Post Categories';

	if (count($errors) > 0)
	{
		echo "<div id='message' class='error'><p><strong>Google+Blog</strong> Please correct the following settings via Settings -> Google+Blog Options: <strong>".join(',',$errors)."</strong></p></div>";
		return false;
	}

	return true;
}

function googleplusblog_fetch_posts($url)
{
	if (ini_get('allow_url_fopen') && in_array('https', stream_get_wrappers()))
	{
		return file_get_contents($url);	
	}
	else
	{
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		 
		$response = curl_exec($ch); 
		curl_close($ch); 
		return $response; 		
	}
}

function googleplusblog_log_error($message)
{
	global $GPB_LOG_SIZE;
	add_action('admin_notices','googleplusblog_errors');

	$options = get_option('googleplusblog_options');

	if (!isset($options['errors']) || !is_array($options['errors']))
		$options['errors'] = array();

	if (!isset($options['log']) || !is_array($options['log']))
		$options['log'] = array();

	$timestamp = date('Y-m-d H:i:s');
	array_push($options['errors'], "$message [$timestamp]");	
	if (count($options['log']) >= $GPB_LOG_SIZE)
		array_shift($options['log']);

	array_push($options['log'],"$message [$timestamp]");	

	update_option('googleplusblog_options',$options);
}

function googleplusblog_log_info($message)
{
	global $GPB_LOG_SIZE;
	@date_default_timezone_set(get_option('timezone_string'));	
	$options = get_option('googleplusblog_options');

	if (!isset($options['log']) || !is_array($options['log']))
		$options['log'] = array();

	$timestamp = date('Y-m-d H:i:s');
	if (count($options['log']) >= $GPB_LOG_SIZE)
		array_shift($options['log']);

	array_push($options['log'],"$message [$timestamp]");	

	update_option('googleplusblog_options',$options);
}

function googleplusblog_errors()
{

	$options = get_option('googleplusblog_options');
	if (@count($options['errors']))
	{
		$errors = @$options['errors'];
		foreach ($errors as $error)
		{
			echo "<div id='message' class='error'><p><strong>Google+Blog</strong> $error</p></div>";
		}
	}

	$options['errors'] = array();
	unset($errors);
	update_option('googleplusblog_options',$options);		
	remove_action('admin_notices','googleplusblog_errors');
	return;
}

function googleplusblog_log()
{
	$options = get_option('googleplusblog_options');
	if (@count($options['log']))
	{
		$logs = array_reverse(@$options['log']);
		foreach ($logs as $log)
		{
			echo "<div id='message' class='message'><p><strong>Google+Blog</strong> $log</p></div>";
		}
	}
}


function googleplusblog_function()
{
	global $GPB_API_KEY, $GPB_PROFILE_ID, $GPB_POST_LIMIT, $GPB_POST_STATUS, $GPB_POST_CATEGORIES, $GPB_POST_TAGS, $GPB_TOTAL_IMPORTED_POSTS, $GPB_TOTAL_IMPORTED_COMMENTS, $GPB_TOTAL_UPDATED_POSTS, $GPB_TOTAL_IGNORED_POSTS, $GPB_POST_IMPORT_TAG, $GPB_POST_EXCLUDE_TAG;
	
	googleplusblog_log_info('Running timed Sync of Google+ posts.');

	if (!googleplusblog_settings_valid())
	{
		return;
	}

	$profile_ids = strpos($GPB_PROFILE_ID,',') >= 0 ?explode(',', $GPB_PROFILE_ID) : array($GPB_PROFILE_ID);
	foreach ($profile_ids as $profile_id) 
	{
		$profile_id = trim($profile_id);
		$post_count = 1;
		do
		{	
			$maxResults = $GPB_POST_LIMIT > 100 ? 100 : $GPB_POST_LIMIT;
			$response = @googleplusblog_fetch_posts('https://www.googleapis.com/plus/v1/people/'.$profile_id.'/activities/public?alt=json&pp=1&key='.$GPB_API_KEY."&maxResults=$maxResults&pageToken=$page_token");
			$page_token = '';

			if (!$response)
			{
				googleplusblog_log_error('Unable to fetch posts, please ensure you have entered the correct API Key and Google+ Profile ID');
				return;
			}
			else
			{
				$response = json_decode($response);
				$page_token = $response->nextPageToken;
				if (isset($response->items))
				{
					foreach ($response->items as $item)
					{
						if (in_array($item->provider->title,array('Google+', 'Mobile', 'Photos', 'Google Reader')) && $post_count <= $GPB_POST_LIMIT)
						{
							$post = new GooglePlusBlogPost();
							$post->id = @$item->id;
							$post->url = @$item->url;
							$post->title = @$item->title;
							$post->verb = @$item->verb; // post, checkin, share
							$post->published = @date('Y-m-d H:i:s',@strtotime(@$item->published));
							$post->published_gmt = @gmdate('Y-m-d H:i:s',@strtotime(@$item->published));

							$post->reshares = @$item->object->resharers->totalItems;

							switch (@$item->object->objectType)
							{
								case 'activity':
									$post->content = @$item->object->content;
									$post->actor_name = @$item->object->actor->displayName;
									$post->actor_url = @$item->object->actor->url;
									$post->actor_image = @$item->object->actor->image->url;
									$post->annotation = @str_replace('<br>','<br />',@$item->annotation);
								default:
									$post->content = @$item->object->content;
							}

							if (@$item->object->attachments)
							{
								foreach ($item->object->attachments as $attachment)
								{
									$gpbp_attachment = new GooglePlusBlogPostAttachment();
									$gpbp_attachment->type = @$attachment->objectType;
									$gpbp_attachment->id = @$post->id.'-'.@$attachment->url;

									switch ($attachment->objectType)
									{
										case 'photo':
											$gpbp_attachment->title = @$attachment->displayName;
											$gpbp_attachment->url = @$attachment->fullImage->url;
											$gpbp_attachment->image_width = @$attachment->fullImage->width;
											$gpbp_attachment->image_height = @$attachment->fullImage->height;
											$gpbp_attachment->thumbnail_url = @$attachment->image->url;
											$gpbp_attachment->thumbnail_height = @$attachment->image->height;
											$gpbp_attachment->thumbnail_width = @$attachment->image->width;
										break;
										case 'photo-album':
											$gpbp_attachment->url = @$attachment->url;
											$gpbp_attachment->title = @$attachment->displayName; 			
										break;
										case 'video':
											if (@$attachment->embed->url)
												$attachment->url = $attachment->embed->url;
											$gpbp_attachment->url = @str_replace('&autoplay=1','',$attachment->url);
											$gpbp_attachment->title = @$attachment->displayName; 			
											$gpbp_attachment->thumbnail_url = @$attachment->image->url;
										break;
										case 'article':
											$gpbp_attachment->url = @$attachment->url;
											$gpbp_attachment->title = @$attachment->displayName; 			
											$gpbp_attachment->article_snippet = @$attachment->content;
										break;
									}
									$post->attachments[] = $gpbp_attachment;
								}
							}
							if (!$GPB_POST_IMPORT_TAG || @in_array($GPB_POST_IMPORT_TAG,explode(', ', $post->getHashTags())))
							{
								if (strlen(trim($GPB_POST_EXCLUDE_TAG)) == 0 || !@in_array($GPB_POST_EXCLUDE_TAG,explode(', ', $post->getHashTags())))
								{
									googleplusblog_create_post($post);
								}
							}
						}
						$post_count++;
					}
				}
				elseif ($GPB_TOTAL_IMPORTED_COMMENTS == 0 && $GPB_TOTAL_IMPORTED_POSTS == 0)
				{
					googleplusblog_log_error("There were no posts to fetch for the given Profile ID on Google+");				
				}
			}
		}
		while ($page_token != '' && $post_count < $GPB_POST_LIMIT);
	}

	googleplusblog_log_info("Completed sync of Google+Posts. Imported $GPB_TOTAL_IMPORTED_POSTS posts ($GPB_TOTAL_UPDATED_POSTS were updated, $GPB_TOTAL_IGNORED_POSTS were ignored) and $GPB_TOTAL_IMPORTED_COMMENTS comments.");

}

function googleplusblog_get_comments($post_id, $wp_post_id)
{
	global $GPB_API_KEY, $GPB_PROFILE_ID, $GPB_POST_LIMIT, $GPB_POST_STATUS, $GPB_POST_CATEGORIES, $GPB_POST_TAGS, $GPB_TOTAL_IMPORTED_POSTS, $GPB_TOTAL_IMPORTED_COMMENTS, $wpdb;

	$comment_ids = array();
	// get_comments was killing the script on some servers
	$comments = $wpdb->get_results( "SELECT comment_agent,comment_author_IP FROM $wpdb->comments WHERE comment_post_ID='$wp_post_id'");

	foreach ($comments as $old_comment)
	{
		if ($old_comment->comment_author_IP == 'Google+')
		{
			$comment_ids[] = $old_comment->comment_agent;			
		}
	}

	do
	{
		$response = @googleplusblog_fetch_posts('https://www.googleapis.com/plus/v1/activities/'.$post_id.'/comments?alt=json&pp=1&key='.$GPB_API_KEY."&maxResults=100&pageToken=$page_token");
		$page_token = '';

		if (!$response)
		{
			echo 'There was a problem updating comments for post '.$post_id;
		}
		else
		{
			$response = json_decode($response);
			$page_token = @$response->nextPageToken;
			if (isset($response->items))
			{
				foreach ($response->items as $item)
				{
					if (!in_array($item->id,$comment_ids))
					{
						$comment = new GooglePlusBlogComment();
						$comment->id = $item->id;
						$comment->post_id = $post_id;
						$comment->content = $item->object->content;
						#$comment->published = date('Y-m-d H:i:s',strtotime($item->published));
						
						$comment->published = @date('Y-m-d H:i:s',@strtotime(@$item->published));
						$comment->published_gmt = @gmdate('Y-m-d H:i:s',@strtotime(@$item->published));

						$comment->author_id = $item->actor->id;
						$comment->author_name = $item->actor->displayName;
						$comment->author_url = $item->actor->url;
						googleplusblog_create_comment($comment, $wp_post_id);
					}
				}
			}
		}
	}
	while ($page_token != '');
}

function googleplusblog_create_post($post)
{
	global $GPB_API_KEY, $GPB_PROFILE_ID, $GPB_POST_LIMIT, $GPB_POST_STATUS, $GPB_POST_CATEGORIES, $GPB_POST_TAGS, $GPB_TOTAL_IMPORTED_POSTS, $GPB_TOTAL_IMPORTED_COMMENTS, $GPB_TOTAL_UPDATED_POSTS,$GPB_TOTAL_IGNORED_POSTS, $GPB_POST_OVERWRITE, $wpdb, $GPB_POST_AUTHOR, $GPB_POST_IMPORT_TRASHED;
	
	$wp_post = array(
		'comment_status' => 'open',
		'post_content' => $post->getContent(),
		'post_status' => strtolower($GPB_POST_STATUS), //'publish', 
		'post_title' => $post->getTitle(), //The title of your post.
		'post_type' => 'post',
		'post_author' => $GPB_POST_AUTHOR,
		'post_date' => $post->published,
		'post_date_gmt' => $post->published_gmt,		
		'post_category' => $GPB_POST_CATEGORIES,
		'tags_input' => $GPB_POST_TAGS,
		'filter' => true
	);  	
	$hashtags = $post->getHashTags();
	if (strlen($hashtags) > 0)
	{
		$wp_post['tags_input'] .= ', ' . $hashtags;	
	}

/*
	$import_trash_sql = $GPB_POST_IMPORT_TRASHED ? "AND post_status != 'trash'" : ''; 

	$trashed = array();
	if (!$GPB_POST_IMPORT_TRASHED)
	{
		$trashed_posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id WHERE post_status='trash' AND meta_key='_googleplus_id'");
		foreach ($trashed_posts as $trashed_post)
		{
			$trashed[] = $trashed_post->meta_value;
		}
		mail('daniel@djt.id.au','Trashed posts',$trashed_posts);
	}	
*/

	$is_trashed = $GPB_POST_IMPORT_TRASHED ? false : ($wpdb->get_results("SELECT * FROM $wpdb->postmeta JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id WHERE meta_key='_googleplus_id' AND meta_value='$post->id' AND post_status = 'trash' LIMIT 1") ? true : false);


	$old_posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id WHERE meta_key='_googleplus_id' AND meta_value='$post->id' AND post_status != 'trash' LIMIT 1");
	if ($old_posts)
	{
		$old_post = $old_posts[0];
		$wp_post['ID'] = $old_post->ID;

		if ($GPB_POST_OVERWRITE && !$is_trashed)
		{
			#$wp_post['tags_input'] = join(',', wp_get_post_tags($old_post->ID, array('fields' => 'name' )));
			$wp_post['post_category'] = array();
			$categories = get_the_category($old_post->ID);
			foreach ($categories as $category)
			{
				$wp_post['post_category'][] = $category->cat_ID;
			}
			// Handle updates gracefully by not reverting the post status after it has changed (or if the latest status is publish).
			if (strtolower($GPB_POST_STATUS) == 'publish' || strtolower($old_post->post_status) == 'publish')
			{
				$wp_post['post_status'] = 'publish';
			}
			elseif ($old_post->post_status != $GPB_POST_STATUS)
			{
				$wp_post['post_status'] = $old_post->post_status;	
			}
		}

		$post_id = $old_post->ID;
		$updated = true;
	}
	
	if (!$updated && !$is_trashed)
	{
		kses_remove_filters();
		$post_id = wp_insert_post($wp_post, true);
		kses_init_filters();
	}
	elseif ($updated && $GPB_POST_OVERWRITE && !$is_trashed)
	{
		kses_remove_filters();
		$post_id = wp_update_post($wp_post);
		kses_init_filters();

	}

	if (is_numeric($post_id))
	{
		if ($post_id > 0)
		{
			add_post_meta($post_id, '_googleplus_id', $post->id, true);
			add_post_meta($post_id, '_googleplus_url', $post->url, true);
		}

		if (!$updated && !$is_trashed)
		{
			$GPB_TOTAL_IMPORTED_POSTS++;
		}
		elseif ($updated && $GPB_POST_OVERWRITE && !$is_trashed)
		{
			$GPB_TOTAL_IMPORTED_POSTS++;
			$GPB_TOTAL_UPDATED_POSTS++;	
		}
		else
		{
			$GPB_TOTAL_IGNORED_POSTS++;
		}
		if ($post_id > 0 && !$is_trashed)
		{
			googleplusblog_get_comments($post->id, $post_id);
		}
		return $post_id;
	}
	else
	{
		$GPB_TOTAL_IGNORED_POSTS++;
		return -1;
	}
}

function googleplusblog_create_comment($comment, $wp_post_id)
{
	global $GPB_API_KEY, $GPB_PROFILE_ID, $GPB_POST_LIMIT, $GPB_POST_STATUS, $GPB_POST_CATEGORIES, $GPB_POST_TAGS, $GPB_TOTAL_IMPORTED_POSTS, $GPB_TOTAL_IMPORTED_COMMENTS;
	
	$wp_comment = array(
	    'comment_post_ID' => $wp_post_id,
	    'comment_author' => $comment->author_name,
	    'comment_author_email' => '',
	    'comment_author_url' => $comment->author_url,
	    'comment_content' => $comment->content,
	    'type' => 'comment',
	    'comment_parent' => 0,
	    'user_id' => 0,
	    'comment_author_IP' => 'Google+',
	    'comment_agent' => $comment->id,
#	    'comment_date' => $comment->published,
		'comment_date' => $comment->published,
		'comment_date_gmt' => $comment->published_gmt,		

	    'comment_approved' => 1
	);


	
	$comment_id = wp_insert_comment($wp_comment, true);
	if (is_numeric($comment_id))
	{
		$GPB_TOTAL_IMPORTED_COMMENTS++;
	}
	else
	{
		echo 'Unable to create comment';
	}
}


class GooglePlusBlogPost
{
	public $id;
	public $title;
	public $content;

	public $verb;

	public $actor_name;
	public $actor_url;
	public $actor_image;

	public $annotation;

	public $attachments = array();

	public $published;
	public $published_gmt;

	public $reshares;

	function getTitle()
	{
		switch ($this->verb)
		{
			case 'share':
				if (!trim(strip_tags($this->annotation)))
				{
					$this->annotation = $this->content;
				}

				if (strpos($this->annotation, '<br />') > 0 && strpos($this->annotation, '<br />') < 80)
				{
					return rtrim(strip_tags(substr($this->annotation,0, strpos($this->annotation, '<br />'))),'.');
				}
				elseif (strpos(strip_tags($this->annotation), '. ') > 0 && strpos(strip_tags($this->annotation), '. ') < 80)
				{
					return rtrim(substr(strip_tags($this->annotation),0, strpos(strip_tags($this->annotation), '. ')),'.');
				}
				else
				{
					if (strpos($this->annotation, '<br />') > 0)
					{
						return safe_truncate(strip_tags(substr($this->annotation,0, strpos($this->annotation, '<br />'))), 80);
					}
					else
					{
						return safe_truncate(strip_tags($this->annotation), 80);
					}
				}				
			break;
			default:
				if (strpos($this->content, '<br />') > 0 && strpos($this->content, '<br />') < 80)
				{
					return rtrim(strip_tags(substr($this->content,0, strpos($this->content, '<br />'))),'.');
				}
				elseif (strpos(strip_tags($this->content), '. ') > 0 && strpos(strip_tags($this->content), '. ') < 80)
				{
					return rtrim(substr(strip_tags($this->content),0, strpos(strip_tags($this->content), '. ')),'.');
				}
				else
				{	
					if (strpos($this->content, '<br />') > 0)
					{
						return safe_truncate(strip_tags(substr($this->content,0, strpos($this->content, '<br />'))), 80);
					}
					else
					{
						return safe_truncate(strip_tags($this->content), 80);
					}
				}
			break;
		}
	}

	function getContent()
	{

		global $GPB_POST_LINK, $GPB_POST_RESHARES;

		$content = '';
		$content_video = '';
		$content_album = '';
		$content_images = '';
		$content_article = '';
		
		switch ($this->verb)
		{
			case 'share':
				if (strpos($this->annotation, '<br />') > 0 && strpos($this->annotation, '<br />') < 80)
				{
					$content = substr($this->annotation, strpos($this->annotation, '<br />')+6)."<br /><br /><strong>Reshared post from +<a href='$this->actor_url'>$this->actor_name</a></strong><br /><blockquote>$this->content</blockquote>";
				}
				elseif (strpos(strip_tags($this->annotation), '. ') > 0 && strpos(strip_tags($this->annotation), '. ') < 80)
				{
					$content = trim(substr($this->annotation,strpos(strip_tags($this->annotation), '. ')+2))."<br /><br /><strong>Reshared post from +<a href='$this->actor_url'>$this->actor_name</a></strong><br /><blockquote>$this->content</blockquote>";
				}
				else
				{
					$content = $this->annotation."<br /><br /><strong>Reshared post from +<a href='$this->actor_url'>$this->actor_name</a></strong><br /><blockquote>$this->content</blockquote>";
				}				
			break;
			default:
				if (strpos($this->content, '<br />') > 0 && strpos($this->content, '<br />') < 80)
				{
					$content = substr($this->content,strpos($this->content, '<br />')+6);
				}
				elseif (strpos($this->content, '. ') > 0 && strpos($this->content, '. ') < 80)
				{
					$content = trim(substr($this->content,strpos($this->content, '. ')+2));
				}
				else
				{	
					$content = $this->content;
				}
			break;

		}		

		$firstPhoto = true;
		foreach ($this->attachments as $attachment)
		{

			switch ($attachment->type)
			{
				case 'photo':
					if (!$this->hasArticle())
					{
						if ($firstPhoto)
						{
							if (@$attachment->width > 400)
							{
								$width = "width='100%'";
							}
							$content_images = "<br /><div><a href='$attachment->url'><img src='$attachment->url' style='max-width:97.5%;clear:both;' border='0' /></a></div><span>$attachment->title</span>";
							$firstPhoto = false;
						}
						else
						{
							$content_images .= "<div style='float:left;display:block;height:60px;width:60px;overflow:hidden;margin-right:5px;margin-top:5px;margin-bottom:5px;'><a href='$attachment->url'><img style='max-width:none;' src='$attachment->thumbnail_url' border='0' /></a></div><br />";
						}
					}
					else
					{
						$article_thumbnail = $attachment->thumbnail_url;
					}
				break;
				case 'photo-album':
					$content_album = "<p style='clear:both;'><a href='$attachment->url'>In album $attachment->title</a></p>";				
				break;
				case 'video':
					if (@substr(@$attachment->url,0,4) == 'http')
					{
						$content_video = "<p style='clear:both;'><iframe type='text/html' width='97.5%' height='385' src='$attachment->url' frameborder='0'></iframe></p>";
					}
				break;
				case 'article':
					if ($article_image = $this->getArticleThumbnail())
					{
						$content_article = "<p style='clear:both;'>
												<p style='margin-bottom:5px;'><strong>Embedded Link</strong></p>
												<div style='height:120px;width:120px;overflow:hidden;float:left;margin-top:0px;padding-top:0px;margin-right:10px;vertical-align:top;text-align:center;clear:both;'>
													<img style='max-width:none;' src='".$article_image."' border='0' />
												</div>
												<a href='$attachment->url'>$attachment->title</a><br />
												$attachment->article_snippet<br />
											</p>";
					}
					else
					{
						$content_article = "<p style='clear:both;'>
												<p style='margin-bottom:5px;'><strong>Embedded Link</strong></p>
												<a href='$attachment->url'>$attachment->title</a><br />
												$attachment->article_snippet<br />
											</p>";
					}
										
				break;
			}
		}

		$content_reshare_link = $content_post_link = "";

		if ($GPB_POST_RESHARES && $this->reshares > 0)
		{
			$content_reshare_link = "<strong>Google+:</strong> Reshared <a href='$this->url' target='_new'>$this->reshares</a> times<br />";			
		}
		if ($GPB_POST_LINK)
		{
			$content_post_link = "<strong>Google+:</strong> <a href='$this->url' target='_new'>View post on Google+</a>";
		}	
		$content_links = "<p style='clear:both;'>$content_reshare_link $content_post_link</p>";

		return $content.$content_video.$content_album.$content_images.$content_article.$content_links.FOOTER;
	}

	function hasArticle()
	{
		foreach ($this->attachments as $attachment)
		{
			if ($attachment->type == 'article')
			{
				return true;
			}
		}
		return false;
	}

	function getHashTags()
	{
		$tags = '';
		preg_match_all("/(?:#)([\w\+\-]+)(?=\s|\.|<|$)/", $this->content.$this->annotation, $matches);
		if (@count($matches))
		{	
			foreach ($matches[0] as $match)
			{
				$tags .= ', '. str_replace('#','', trim($match)); 
			}
		}

		return $tags; 		

	}

	function getArticleThumbnail()
	{
		foreach ($this->attachments as $attachment)
		{
			if ($attachment->type == 'photo')
			{
				return $attachment->thumbnail_url;
			}
		}
		return '';
	}

}


class GooglePlusBlogPostAttachment
{		
	public $type;
	public $id;
	
	public $title; #displayName
	public $url; #url

	public $article_snippet; #content
	
	public $thumbnail_url; #image/url
	public $thumbnail_height;
	public $thumbnail_width;
	public $image_width; #fullImage/width
	public $image_height; #fullImage/width
}

class GooglePlusBlogComment
{
	public $id;
	public $post_id;
	public $content;
	public $published;
	public $published_gmt;	

	public $author_id;
	public $author_name;
	public $author_url;
}

function safe_truncate($input, $length)
{
	if (strlen($input) <= $length)
	{
		return $input;
	}
	else
	{
		if (false !== ($endpoint_location = strpos($input, ' ', $length))) 
		{
			if ($endpoint_location < strlen($input) - 1) 
			{
				$input = substr($input, 0, $endpoint_location);
			}
		}
		else
		{
			$input = substr($input, 0, $length);
		}
		return $input.'...';
	}
}
?>