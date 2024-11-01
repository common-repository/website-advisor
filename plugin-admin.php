<?php

class Website_Advisor_Admin extends Website_Advisor {
	/**
	 * Error messages to diplay
	 *
	 * @var array
	 */
	private $_messages = array();
	

	/**
	 * Class constructor
	 *
	 */
	public function __construct() {
		$this->_plugin_dir   = DIRECTORY_SEPARATOR . str_replace(basename(__FILE__), null, plugin_basename(__FILE__));
		$this->_settings_url = 'options-general.php?page=' . plugin_basename(__FILE__);;
		
		//$this->setup_css_imports();

		$allowed_options = array(
			
	);
		
		
		if(array_key_exists('option_name', $_GET) && array_key_exists('option_value', $_GET)
			&& in_array($_GET['option_name'], $allowed_options)) {
			update_option($_GET['option_name'], $_GET['option_value']);
			
			header("Location: " . $this->_settings_url);
			die();	
		
		} else {
			// register installer function
			register_activation_hook(WA_LOADER, array(&$this, 'activateWebsiteAdvisor'));
			
			// add plugin "Settings" action on plugin list
			add_action('plugin_action_links_' . plugin_basename(WA_LOADER), array(&$this, 'add_plugin_actions'));
			
			// add links for plugin help, donations,...
			add_filter('plugin_row_meta', array(&$this, 'add_plugin_links'), 10, 2);
			
			// push options page link, when generating admin menu
			add_action('admin_menu', array(&$this, 'adminMenu'));
	
		}
	}
	
	/**
	 * Add "Settings" action on installed plugin list
	 */
	public function add_plugin_actions($links) {
		array_unshift($links, '<a href="options-general.php?page=' . plugin_basename(__FILE__) . '">' . __('Settings') . '</a>');
		
		return $links;
	}

  
  
  
  
  	/**
	 * Add links on installed plugin list
	 */
	public function add_plugin_links($links, $file) {
		if($file == plugin_basename(WA_LOADER)) {
			$links[] = '<a href="http://MyWebsiteAdvisor.com">Premium Plugins</a>';
		}
                               
                               
		
		return $links;
	}
	
	/**
	 * Add menu entry 
	 */
	public function adminMenu() {		
		// add option in admin menu, for setting options
		
		add_menu_page( 'Website Advisor', 'Website Advisor', 8, __FILE__ ); 
    
  		$plugin_page = add_submenu_page(__FILE__,'Website Advisor','Website Advisor', 8, __FILE__, array(&$this, 'optionsPage'));  
		$validator_page = add_submenu_page(__FILE__,'Sitemap Validator','Sitemap Validator', 8, 'sitemap-validator', array(&$this, 'sitemapValidator'));
    		$checkup_page = add_submenu_page(__FILE__,'Website Checkup','Website Checkup', 8, 'website-checkup', array(&$this, 'websiteCheckup'));
    
		//$plugin_page = add_options_page('Website Advisor', 'Website Advisor', 8, __FILE__, array(&$this, 'optionsPage'));
    
    		add_action('admin_print_styles-' . $plugin_page, array(&$this, 'installStyles'));
    
    
	}
	

	/**
	 * Include styles used by Plugin
	 */
	public function installStyles() {
		wp_enqueue_style('website_advisor', WP_PLUGIN_URL . $this->_plugin_dir . 'style.css');
	}	

  
  
  
  
  
  
  
                               
  
  
  
    public function websiteCheckup(){
   // if user clicked "Save Changes" save them
		if(isset($_POST['Submit'])) {
			foreach($this->_options as $option => $value) {
				if(array_key_exists($option, $_POST)) {
					update_option($option, $_POST[$option]);
				} else {
					update_option($option, $value);
				}
			}

			$this->_messages['updated'][] = 'Options updated!';
		}

	
		
	
		foreach($this->_messages as $namespace => $messages) {
			foreach($messages as $message) {
?>
<div class="<?php echo $namespace; ?>">
	<p>
		<strong><?php echo $message; ?></strong>
	</p>
</div>
<?php
			}
		}
?>
<script type="text/javascript">var wpurl = "<?php bloginfo('wpurl'); ?>";</script>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Website Checkup</h2>

	

<?php
       echo "<h3>WordPress Version Information</h3>";                                            
      	echo "<p>";
        echo "<b>Current Wordpress Version:</b> ".get_bloginfo("version");                                           
                                                    
       echo "</p>";      
                                                    
        echo "<hr>";
                                                    
                                                    
        echo "<h3>PHP Version Information</h3>";                                            
      	echo "<p>";
           echo "<b>Current PHP Version:</b> " . phpversion();
           echo "<br>";

            if(phpversion() >= 5){
               echo "<b>Analysis:</b> Excellent, PHP is newer than 5.0!";
                                                    
            }else{
            	echo "<b>Analysis:</b> You Should consider upgrading PHP to version 5+";
                                                    
            }
                                                    
      	echo "</p>";
                                                    
        echo "<hr>";
                                                    
                                                    
	echo "<h3>PHP Memory Information</h3>";
      	echo "<p>";                                                   
          echo "<b>PHP Memory Limit:</b> ".ini_get("memory_limit"); 
          echo "<br>"; 

                                                    
          $memory_limit = ini_get("memory_limit"); 
          $memory_limit = ereg_replace("M", "", $memory_limit);   
                                                    
                                                    
          if($memory_limit > 32){
              echo "<b>Analysis:</b> Excellent, PHP Memory Limit is greater than 32 MB!";
          }else{
               echo "<b>Analysis:</b> Warning, You should consider increasing PHP Memory Limit!";
          }
                                                    
      	echo "</p>";
          
                                                    
                                                    
        echo "<p>";
                                             
            	$current_mem = number_format((memory_get_usage()/(1024*1024)),0); 
          	$use_ratio = ($current_mem / $memory_limit) * 100; 
                 $use_ratio = number_format($use_ratio, 0);
           echo "<b>Current PHP Memory Use:</b> " . $current_mem . "M of " . $memory_limit . "M (" . $use_ratio . "%)";
    	echo "<br>";                                                     
                                                    
 
          if($use_ratio <= 70){
              echo "<b>Analysis:</b> Excellent, PHP has plenty of available memory!";
          }else{
              echo "<b>Analysis:</b> Warning, You should consider increasing PHP Memory Limit!";
          }
                                                    
      	echo "</p>";
                                                    
          echo "<hr>";          
                                                    
                                                    
        echo "<h3>File Upload and POST Size Limit Information</h3>";                                            
                                                    
       $upload_max_filesize = intval(ini_get('upload_max_filesize'));
	$post_max_size = intval(ini_get('post_max_size'));                                                     
   
 
     	echo "<p>";
           echo "<b>Current PHP POST Limit:</b> " . $post_max_size;
           echo "<br>";

            if($post_max_size >= 8){
               echo "<b>Analysis:</b> Excellent, PHP POST Limit is over $post_max_size M";                            
            }else{
            	echo "<b>Analysis:</b> You Should consider increasing the PHP POST limit to a value above $post_max_size M";
                                                    
            }
                                                    
      	echo "</p>";
                                                    
 
                                                    
          echo "<p>";
           echo "<b>Current PHP Upload Filesize Limit:</b> " . $upload_max_filesize;
           echo "<br>";

            if($upload_max_filesize >= 8){
               echo "<b>Analysis:</b> Excellent, PHP Upload Filesize Limit is over $upload_max_filesize M";                            
            }else{
            	echo "<b>Analysis:</b> You Should consider increasing the PHP Upload Filesize limit to a value above $upload_max_filesize M";
                                                    
            }
                                                    
      	echo "</p>";
                                                    
           echo "<hr>";
                                                 

                                                    
    ?>
      
      

      

		
</div>
<?php
    
    
    
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  public function optionsPage(){
   // if user clicked "Save Changes" save them
		if(isset($_POST['Submit'])) {
			foreach($this->_options as $option => $value) {
				if(array_key_exists($option, $_POST)) {
					update_option($option, $_POST[$option]);
				} else {
					update_option($option, $value);
				}
			}

			$this->_messages['updated'][] = 'Options updated!';
		}

	
		
	
		foreach($this->_messages as $namespace => $messages) {
			foreach($messages as $message) {
?>
<div class="<?php echo $namespace; ?>">
	<p>
		<strong><?php echo $message; ?></strong>
	</p>
</div>
<?php
			}
		}
?>
<script type="text/javascript">var wpurl = "<?php bloginfo('wpurl'); ?>";</script>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Website Advisor from MyWebsiteAdvisor.com</h2>
                                                    

<div class="mwa_news">
<h3>Recent News from MyWebsiteAdvisor.com</h3>

<?php

$html = file_get_contents("http://mywebsiteadvisor.com/category/wordpress/feed/rss");

$xml = new SimpleXMLElement($html);

foreach($xml->channel->item as $article){

  $title = $article->title;
  $link = $article->link;
                                                      
  echo "<h3><a href='$link'>$title</a></h3>";
  echo "<p>";
            echo $article->description;
            echo "  <a href='$link'>Read More &raquo</a>";                                       
  echo "</p>";

}



?>


</div>
                                                    
                                                    
                                             
	<div class='mwa_plugins'>
                                                    <h3>Popular WordPress Plugins By MyWebsiteAdvisor.com</h3>
                                                    
                                                    

<?php
      
      require_once("wp_scraper.class.php");
	$wp = new WP_Scraper();
	$wp_dev_info = $wp->get_dev_info();
	echo $wp_dev_info;
    
    
    ?>
      
  </div>    

      

		
</div>
<?php
    
    
    
  }
  
  
  
  
  
  
  
	
	/**
	 * Display options page
	 */
	public function sitemapValidator() {
		// if user clicked "Save Changes" save them
		if(isset($_POST['Submit'])) {
			foreach($this->_options as $option => $value) {
				if(array_key_exists($option, $_POST)) {
					update_option($option, $_POST[$option]);
				} else {
					update_option($option, $value);
				}
			}

			$this->_messages['updated'][] = 'Options updated!';
		}

	
		
	
		foreach($this->_messages as $namespace => $messages) {
			foreach($messages as $message) {
?>
<div class="<?php echo $namespace; ?>">
	<p>
		<strong><?php echo $message; ?></strong>
	</p>
</div>
<?php
			}
		}
?>
<script type="text/javascript">var wpurl = "<?php bloginfo('wpurl'); ?>";</script>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Website Advisor</h2>

	<form method="post" action="">

<?php
      
      $site_url = get_option('siteurl');
      $sitemap_url = $site_url . "/sitemap.xml";
    
   
      
      if(!file_get_contents($sitemap_url)){
        $info = "<b>Error: Sitemap Not Found <br>$sitemap_url</b><br><a href=''>Try Again</a><br>";
  }else{
  
        $validator_base = "http://www.w3.org/2001/03/webdata/xsv?docAddrs=" . $sitemap_url . "&warnings=on&keepGoing=on&style=text#"; 
        
        $html = file_get_contents("$validator_base");
        
        $xml = new SimpleXMLElement($html);
        $attrs = $xml->attributes();
        $schemaErrors = $attrs['schemaErrors'];
        $validation_type= $attrs['validation'];
        $validation_target= $attrs['target'];
        $sitemap_size= $attrs['size'];
        
      
      	$info = "<h2>Site Map Validation</h2>";
        $info .= "<b>Sitemap:</b> " . $validation_target . "<br><hr>";
        $info .= "<b>Validation Errors:</b> " . $schemaErrors . "<br>";
        $info .= "<b>Validation Type:</b> " . $validation_type . "<br>";
        $info .= "<b>Sitemap Size:</b> " . $sitemap_size . "<br>";
        
      
        $html = file_get_contents($sitemap_url);
        $xml = new SimpleXMLElement($html);
        $sitemap_count = count($xml->url);
        
      	
        $info .= "<b>Sitemap Page Count:</b> " . $sitemap_count . "<br>";
        $info .= "<br><hr>";
      	$info .= "<h2>Site Map Submission</h2>";
        $info .= "Send Sitemap to Google: <a href='http://www.google.com/webmasters/sitemaps/ping?sitemap=$sitemap_url' target='_blank'>Click Here</a><br>";
        //$info .= "Send Sitemap to Yahoo: <a href='http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=$sitemap_url' target='_blank'>Click Here</a><br>";						
        $info .= "Send Sitemap to Bing and Yahoo: <a href='http://www.bing.com/webmaster/ping.aspx?siteMap=$sitemap_url' target='_blank'>Click Here</a><br>";
        $info .= "Send Sitemap to Ask: <a href='http://submissions.ask.com/ping?sitemap=$sitemap_url' target='_blank'>Click Here</a><br>";					
        
        $domain = ereg_replace("http://","",$url);
  
        echo $info;

      	echo "<br>";
	echo "<br><hr>";
      	echo "<h2>Your Site Map</h2>";
        
        
      	echo "<iframe src='$sitemap_url' width='100%' height='600px' style='border:1px solid #eee;'></iframe>";

      
	}
					
    
    ?>

      



			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
			</p>
			
		</form>
		
</div>
<?php
	}


}

$website_advisor = new Website_Advisor_Admin();
?>