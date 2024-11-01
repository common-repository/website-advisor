<?php

class WP_Scraper{


	public $developer_name = "MyWebsiteAdvisor";
	public $link_target = "_blank";
	public $plugin_limit = 10;


	function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {
   		foreach ($array as $subarray) {
        		$keys[] = $subarray[$subkey];
    		}
    		array_multisort($keys, $sortType, $array);

		return $array;
	}


	function get_dev_info(){
		
		$finished_html = get_option('wp_dev_stats_html');
		$wp_dev_stats_timestamp = get_option('wp_dev_stats_timestamp');
		$cahe_time_limit = $wp_dev_stats_timestamp + (60 * 30);

		$plugin_limit = $this->plugin_limit;

		$wp_dev_array = array();	

		$i = 0;

		//if(!isset($wp_dev_stats_html) || ($wp_dev_stats_timestamp < $cahe_time_limit)){
				
			$finished_html= "";
			$html_output = "";

			require_once('simple_html_dom.php');
			
			$html = file_get_html('http://wordpress.org/extend/plugins/profile/'. $this->developer_name.'/');
			
			$plugin_divs = $html->find('div.plugin-block');
		

			foreach($plugin_divs as $plugin_div){

				foreach($plugin_div->find('a') as $plugin_a){
					$plugin_a->target="_blank";
				}

				foreach($plugin_div->find('ul') as $plugin_ul){
					foreach($plugin_ul->find('li') as $plugin_li){
						if($plugin_li->find('span', 0)->innertext == "Downloads"){
							$downloads = $plugin_li->plaintext;
						}
						if($plugin_li->find('span', 0)->innertext == "Version"){
							//$plugin_li->outertext = "";
						}
						if($plugin_li->find('span', 0)->innertext == "Updated"){
							//$plugin_li->outertext = "";
						}
					}
		
				}

				$html_output .= $plugin_div;

				$wp_dev_array[$i]['html'] = $plugin_div;
				$download_count = ereg_replace('Downloads ', '', $downloads);
				
				$wp_dev_array[$i]['downloads'] = $download_count;
				$i++;
			}


			$wp_dev_array_sorted = $this->sortBySubkey($wp_dev_array, "downloads");

			$j = 0;
			foreach($wp_dev_array_sorted as $plugin){
				if($j < $plugin_limit){
					$finished_html .= $plugin['html'];
				}
				$j++;
			}
			
			
			$wp_dev_stats_timestamp = time();				

			update_option('wp_dev_stats_html', $finished_html);
			update_option('wp_dev_stats_timestamp', $wp_dev_stats_timestamp);

			$html->clear(); 
			unset($html);

		//}	


		return "<div class='website_advisor'>" . $finished_html . "</div>";

	}


}


?>