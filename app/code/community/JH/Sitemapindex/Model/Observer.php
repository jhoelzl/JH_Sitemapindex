<?php
class JH_Sitemapindex_Model_Observer {
    
	public function createIndex() {
	
		// Create XML Sitemap Index file in magento root folder based on all separate XML sitemaps in mysql table
    	$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		
		$collection = Mage::getModel('xsitemap/sitemap')->getCollection();		
		$results = $readConnection->fetchAll($collection->getSelect());
		
		$sitemap_content = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		foreach ($results as $data) {	
			$sitemap_content.= '<sitemap><loc>'.trim(Mage::getStoreConfig('web/unsecure/base_url'),'/').$data['sitemap_path'].$data['sitemap_filename'].'</loc><lastmod>'.$data['sitemap_time'].'</lastmod></sitemap>';           
		}
		
		$sitemap_content.= '</sitemapindex>';
    	
		$sitemap_index_file = Mage::getBaseDir()."/sitemap.xml";
    	
		// Write file
		$handle = fopen($sitemap_index_file, 'w') or die('Cannot open file: '.$sitemap_index_file);
		fwrite($handle, $sitemap_content);

    	}
    
	
}