<?php
		
	/*
	We are not going to do any deletes server side for uninstalls because it leaves
	too many scenarios open where accounts and payout records could be deleted by "mistake".
	*/
		
	####
	## Remove the plugin options from the database
	####
	
	delete_option('ccpa_active');
	delete_option('ccpa_prodsperpost');
	delete_option('ccpa_key');

?>
