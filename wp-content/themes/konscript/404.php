<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site.
 */

header( 'HTTP/1.1 404 Not found', false, 404 );
header( 'Location: ' .  get_bloginfo( 'wpurl' ) ) ;

?>