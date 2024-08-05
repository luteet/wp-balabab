<?php 

$current_cat = get_the_terms(get_the_ID(), "salons");
if($current_cat) {
	wp_redirect(get_term_link($current_cat[0]));
}
