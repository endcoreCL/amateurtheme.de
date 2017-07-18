<?php
if(get_post_type() == 'post' || get_post_type() == 'location') {
    dynamic_sidebar(get_post_type());
} else {
    dynamic_sidebar('standard');
}
?>