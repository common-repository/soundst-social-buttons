<?php
/*
Plugin Name: Soundst Social Buttons
Plugin URI: http://www.soundst.com/
Description: This plugin will add a social networks share buttons after the body content on pages and posts
Author: Sound Strategies
Version: 0.0.5
*/

class Add_social_buttons {
	
	private $fb_button_page = '';
	private $fb_button_post = '';
	private $fb_page_box = false;
	private $fb_post_box = false;
	private $tw_button_page = '';
	private $tw_button_post = '';
	private $tw_page_box = false;
	private $tw_post_box = false;
	private $email_button_page = '';
	private $email_button_post = '';
	private $email_page_box = false;
	private $email_post_box = false;
	

	public function __construct() {
		if ( is_admin() ){
			add_action('admin_menu', array( $this, 'fb_create_menu' ), 1);
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_settings_link' ) );
		} else {
			add_filter('the_content',  array( $this, 'add_post_content' ), 101);
		}
	}
	
	public function init_params() {
		$fb_theme_options = get_option('fb_theme_options');
		$this->fb_button_page = isset($fb_theme_options['fb_button_page'])?$fb_theme_options['fb_button_page']:'';
		$this->fb_button_post = isset($fb_theme_options['fb_button_post'])?$fb_theme_options['fb_button_post']:'';
		$this->tw_button_page = isset($fb_theme_options['tw_button_page'])?$fb_theme_options['tw_button_page']:'';
		$this->tw_button_post = isset($fb_theme_options['tw_button_post'])?$fb_theme_options['tw_button_post']:'';
		$this->email_button_page = isset($fb_theme_options['email_button_page'])?$fb_theme_options['email_button_page']:'';
		$this->email_button_post = isset($fb_theme_options['email_button_post'])?$fb_theme_options['email_button_post']:'';
		$this->fb_page_box = (bool) isset($fb_theme_options['fb_page_box'])?$fb_theme_options['fb_page_box']:false;
		$this->fb_post_box = (bool) isset($fb_theme_options['fb_post_box'])?$fb_theme_options['fb_post_box']:false;
		$this->tw_page_box = (bool) isset($fb_theme_options['tw_page_box'])?$fb_theme_options['tw_page_box']:false;
		$this->tw_post_box = (bool) isset($fb_theme_options['tw_post_box'])?$fb_theme_options['tw_post_box']:false;
		$this->email_page_box = (bool) isset($fb_theme_options['email_page_box'])?$fb_theme_options['email_page_box']:false;
		$this->email_post_box = (bool) isset($fb_theme_options['email_post_box'])?$fb_theme_options['email_post_box']:false;
		
		if ($this->fb_button_page === '') $this->fb_button_page = plugins_url() . '/' . basename(__DIR__)  . '/ss_fb_share.png';
		if ($this->fb_button_post === '') $this->fb_button_post = plugins_url() . '/' . basename(__DIR__)  . '/ss_fb_share.png';
		if ($this->tw_button_page === '') $this->tw_button_page = plugins_url() . '/' . basename(__DIR__)  . '/ss_tw_share.png';
		if ($this->tw_button_post === '') $this->tw_button_post = plugins_url() . '/' . basename(__DIR__)  . '/ss_tw_share.png';
		if ($this->email_button_page === '') $this->email_button_page = plugins_url() . '/' . basename(__DIR__)  . '/ss_email_share.png';
		if ($this->email_button_post === '') $this->email_button_post = plugins_url() . '/' . basename(__DIR__)  . '/ss_email_share.png';
	}
	
	public function add_post_content($content) {
		if(is_page() || is_single()) {
			$this->init_params();
			
			$Path = urlencode('http'.(!empty($_SERVER["HTTPS"])?'s':'').'://' . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI']);
			$title = urlencode(html_entity_decode(get_the_title()));
			
			$fb_URI='https://www.facebook.com/sharer.php?u='.$Path;
			$tw_URI='https://www.twitter.com/share?url='.$Path . '&text='. $title;
			$email_URI = 'mailto:?subject='.$title.'&amp;body='.$Path;
			
			if (is_page()) {
				if ($this->fb_page_box || $this->tw_page_box || $this->email_page_box) {  $content .= '<div style="text-align:center;"><div class="social-b" style="display: inline-block;">'; }
					if ($this->fb_page_box) {
						$content .= '<p class="facebook_button alignleft"><a target="_blank" href="' . $fb_URI . '"><img style="border: none; background: none; margin-right: 35px;" src="' . esc_attr($this->fb_button_page) . '" /></a></p>';
					}
					if ($this->tw_page_box) {
						$content .= '<p class="twitter_button alignleft"><a target="_blank" href="' . $tw_URI . '"><img style="border: none; background: none; margin-right: 35px;" src="' . esc_attr($this->tw_button_page) . '" /></a></p>';
					}
					if ($this->email_page_box) {
						$content .= '<p class="email_button alignleft"><a target="_blank" href="' . $email_URI . '"><img style="border: none; background: none;" src="' . esc_attr($this->email_button_page) . '" /></a></p>';
					}
				if ($this->fb_page_box || $this->tw_page_box || $this->email_page_box) { $content .= '</div></div>'; }
			}
			if (is_single()) {
				if ($this->fb_post_box || $this->tw_post_box || $this->email_post_box) {  $content .= '<div style="text-align:center;"><div class="social-b" style="display: inline-block;">'; }
					if ($this->fb_post_box) {
						$content .= '<p class="facebook_button alignleft"><a target="_blank" href="' . $fb_URI . '"><img style="border: none; background: none; margin-right: 35px;" src="' . esc_attr($this->fb_button_post) . '" /></a></p>';
					}
					if ($this->tw_post_box) {
						$content .= '<p class="twitter_button alignleft"><a target="_blank" href="' . $tw_URI . '"><img style="border: none; background: none; margin-right: 35px;" src="' . esc_attr($this->tw_button_post) . '" /></a></p>';
					}
					if ($this->email_post_box) {
						$content .= '<p class="email_button alignleft"><a target="_blank" href="' . $email_URI . '"><img style="border: none; background: none;" src="' . esc_attr($this->email_button_post) . '" /></a></p>';
					}
				if ($this->fb_post_box || $this->tw_post_box || $this->email_post_box) { $content .= '</div></div>'; }
			}
		}
		return $content;
	}
	
	
	/**** Add theme options ***/
	
	
	public function fb_create_menu() {
		add_submenu_page('options-general.php', 'Soundst Social Buttons', 'Soundst Social Buttons', 'edit_themes', 'soundst-sb-options',  array($this, 'theme_options_admin'));
	}
	
	function plugin_settings_link($links) {
		$url = get_admin_url() . 'options-general.php?page=soundst-sb-options';
		$settings_link = '<a href="'.$url.'">' . __( 'Settings', 'textdomain' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
	
	
	function theme_options_admin () {
		if (!current_user_can('edit_themes')) {
			wp_die('No privileges to edit this page!');
		}
		if (isset($_POST['options_submit']) && $_POST['options_submit'] == 'yes') {
			$fb_theme_options = array();
			$fb_theme_options['fb_button_page'] = $_POST['fb_button_page'];
			$fb_theme_options['fb_button_post'] = $_POST['fb_button_post'];
			$fb_theme_options['fb_page_box'] = (bool)$_POST['fb_page_box'];
			$fb_theme_options['fb_post_box'] = (bool)$_POST['fb_post_box'];
			$fb_theme_options['tw_button_page'] = $_POST['tw_button_page'];
			$fb_theme_options['tw_button_post'] = $_POST['tw_button_post'];
			$fb_theme_options['tw_page_box'] = (bool)$_POST['tw_page_box'];
			$fb_theme_options['tw_post_box'] = (bool)$_POST['tw_post_box'];
			$fb_theme_options['email_button_page'] = $_POST['email_button_page'];
			$fb_theme_options['email_button_post'] = $_POST['email_button_post'];
			$fb_theme_options['email_page_box'] = (bool)$_POST['email_page_box'];
			$fb_theme_options['email_post_box'] = (bool)$_POST['email_post_box'];
	
			update_option('fb_theme_options', $fb_theme_options);
		}
		$this->init_params();
		?>
		<div class="wrap">
			<h2>Social Share Buttons</h2>
			<hr>
				<form method="post">
					<input type="hidden" name="options_submit" value="yes" />
						<table>
						<tr>
							<td><label for="fb_button_page"><span>Facebook Button for Pages</span></label></td>
							<td><input style="width: 450px;" id="fb_button_page" type="text" size="20" name="fb_button_page" value="<?php  echo esc_attr($this->fb_button_page); ?>" /></td>
						</tr>
						<tr>
							<td><label for="fb_button_post"><span>Facebook Button for Posts</span></label></td>
							<td><input style="width: 450px;" id="fb_button_post" type="text" size="20" name="fb_button_post" value="<?php  echo esc_attr($this->fb_button_post); ?>" /></td>
						</tr>
						<tr>
							<td><label for="fb_page_box"><span>Display on pages?</span></label></td>
							<td><input type="checkbox" name="fb_page_box" id="fb_page_box" value="true" <?php echo ( 'true' == $this->fb_page_box ) ? 'checked="checked"' : ''; ?> /></td>
						</tr>
						<tr>
							<td><label for="fb_post_box"><span>Display on posts?</span></label><hr></td>
							<td><input type="checkbox" name="fb_post_box" id="fb_post_box" value="true" <?php echo ( 'true' == $this->fb_post_box ) ? 'checked="checked"' : ''; ?> /><hr></td>
						</tr>
						<tr>
							<td><label for="tw_button_page"><span>Twitter Button for Pages</span></label></td>
							<td><input style="width: 450px;" id="tw_button_page" type="text" size="20" name="tw_button_page" value="<?php  echo esc_attr($this->tw_button_page); ?>" /></td>
						</tr>
						<tr>
							<td><label for="tw_button_post"><span>Twitter Button for Posts</span></label></td>
							<td><input style="width: 450px;" id="tw_button_post" type="text" size="20" name="tw_button_post" value="<?php  echo esc_attr($this->tw_button_post); ?>" /></td>
						</tr>
						<tr>
							<td><label for="tw_page_box"><span>Display on pages?</span></label></td>
							<td><input type="checkbox" name="tw_page_box" id="tw_page_box" value="true" <?php echo ( 'true' == $this->tw_page_box ) ? 'checked="checked"' : ''; ?> /></td>
						</tr>
						<tr>
							<td><label for="tw_post_box"><span>Display on posts?</span></label><hr></td>
							<td><input type="checkbox" name="tw_post_box" id="tw_post_box" value="true" <?php echo ( 'true' == $this->tw_post_box ) ? 'checked="checked"' : ''; ?> /><hr></td>
						</tr>
						<tr>
							<td><label for="email_button_page"><span>Email Button for Pages</span></label></td>
							<td><input style="width: 450px;" id="email_button_page" type="text" size="20" name="email_button_page" value="<?php  echo esc_attr($this->email_button_page); ?>" /></td>
						</tr>
						<tr>
							<td><label for="email_button_post"><span>Email Button for Posts</span></label></td>
							<td><input style="width: 450px;" id="email_button_post" type="text" size="20" name="email_button_post" value="<?php  echo esc_attr($this->email_button_post); ?>" /></td>
						</tr>
						<tr>
							<td><label for="email_page_box"><span>Display on pages?</span></label></td>
							<td><input type="checkbox" name="email_page_box" id="email_page_box" value="true" <?php echo ( 'true' == $this->email_page_box ) ? 'checked="checked"' : ''; ?> /></td>
						</tr>
						<tr>
							<td><label for="email_post_box"><span>Display on posts?</span></label></td>
							<td><input type="checkbox" name="email_post_box" id="email_post_box" value="true" <?php echo ( 'true' == $this->email_post_box ) ? 'checked="checked"' : ''; ?> /></td>
						</tr>
						<tr> 
							<td></td>
							<td><input type="submit" class="button-primary" value="Update Settings" /></td>
						</tr>
				</table>
				</form>	
			</div>
		<?php
		
	}
	
}
new Add_social_buttons();

