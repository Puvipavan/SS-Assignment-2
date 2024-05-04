<?php
$tab_node_selector = ".ba-node-$id";
?>

(function($) {
    $(function() {
        $('<?php echo esc_js($tab_node_selector) ?>').tabs();
    });
})(jQuery);