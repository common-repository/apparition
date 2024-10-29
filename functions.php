<?php
class sjApparition {
	static $instance = false;
	private $text_domain = 'apparition';
	private $debug = true;

	private $categories;
	private $category;
	private $category_array;
	private $bookmark;
	
	protected function __construct() {
		$this->get_categories();
		$this->get_category();
		$this->trigger_hooks();

		add_action('admin_menu', array(&$this, 'trigget_admin_menu'));
		add_action('admin_bar_menu', array(&$this, 'admin_bar_menu'), 999);
	}

	public static function getInstance() {
		if (!self::$instance)
			self::$instance = new self;

		return self::$instance;
	}

	private function trigger_hooks() {
		add_action('admin_menu', array(&$this, 'trigget_admin_menu'));
		add_action('admin_bar_menu', array(&$this, 'admin_bar_menu'), 999);
		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
	}

	private function get_categories() {
		$taxonomy = 'link_category';
		$args = array('hide_empty' => false);
		$this->categories = get_terms( $taxonomy, $args );
	}

	private function get_category() {
		$this->category = get_option('sj-apparition');
		$this->category_array = explode(',', $this->category);
		$this->bookmark = get_bookmarks(array('category' => $this->category));
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style('sujin_apparition', plugin_dir_url( __FILE__ ) . '/style.css');
	}

	public function trigget_admin_menu() {
		add_options_page(__('Apparition!', $this->text_domain), __('Apparition!', $this->text_domain), 'manage_options', 'apparition', array(&$this, 'admin_menu'));
	}

	public function admin_menu() {
		if (isset($_POST['action']) && check_admin_referer($this->text_domain)) {
			$categories = array();
			foreach ($_POST['cat'] as $key => $val) {
				$categories[] = $key;
			}

			$categories = implode(',', $categories);

			update_option('sj-apparition', $categories);
			$this->get_category();
			$this->redirect();
		}

		$this->print_admin_page();
	}

	private function print_admin_page() {
  		?>

		<div class="wrap sjApparition">
			<div class="icon32" id="icon-options-general"><br></div><h2><?php _e('Apparition!', $this->text_domain); ?></h2>
			<p><?php _e('Check Link-Category below. The links that included checked category will appear at admin-bar.', $this->text_domain); ?></p>

			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donation">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>

			<form method="post" id="sjTagForm">
				<input type="hidden" value="apparition" name="option_page">
				<input type="hidden" value="update" name="action">

				<h3 id="link_category"><?php _e('Link Category', $this->text_domain); ?></h3>

				<?php foreach($this->categories as $category) { ?>

				<div class="col_wrapper">
					<input type="checkbox" id="cat-<?php echo $category->term_id ?>" name="cat[<?php echo $category->term_id ?>]" <?php if (in_array($category->term_id, $this->category_array)) echo 'checked="checked"' ?> /> <label for="cat-<?php echo $category->term_id ?>"><?php echo $category->name ?></label>
				</div>

				<?php } ?>

				<?php wp_nonce_field($this->text_domain) ?>

				<p class="submit">
					<input type="submit" value="<?php _e('Save Changes', $this->text_domain); ?>" class="button button-primary" id="submit" name="submit">
					<a href="<?php echo $_SERVER['REQUEST_URI'] ?>" class="button"><?php _e('Cancel', $this->text_domain); ?></a>
				</p>
			</form>
		</div>

		<?php
	}

	public function admin_bar_menu($wp_admin_bar) {
		global $wp_admin_bar;

		$args = array(
			'id' => 'sj-apparition',
			'title' => __('Apparition!', $this->text_domain),
			'href' => get_site_url() . '/wp-admin/options-general.php?page=apparition'
		);
		$wp_admin_bar->add_node($args);

		foreach ($this->bookmark as $bookmark) {
			$args = array(
				'id' => 'bookmark-' . $bookmark->link_id,
				'title' => $bookmark->link_name,
				'href' => $bookmark->link_url,
				'parent' => 'sj-apparition',
				'meta' => array('target' => $bookmark->link_target)
			); 
			$wp_admin_bar->add_node($args);
		}
	}

	private function redirect($url) {
		if (!$url) $url = $_SERVER['HTTP_REFERER'];

		echo '<script>window.location="' . $url . '"</script>';
		die;
	}

	private function debug($array) {
		$style = ($this->debug == false) ? 'style="display:none;"' : '';
		echo '<pre ' . $style . '>';
		print_r($array);
		echo '</pre>';
	}
}

$sjApparition = sjApparition::getInstance();