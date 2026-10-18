<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wp_version;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo __( 'BCTheme PageBuilder', 'xintheme-pagebuilder' ) . ' | ' . get_the_title(); ?></title>
	<?php wp_head(); ?>
	<script>
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
    </script>
    <script type="text/javascript">
        function onLoadHandler(){
            if( typeof  document.getElementById('xtpbMainIframeLoadingWrap') != 'undefined' ){
                document.getElementById('xtpbMainIframeLoadingWrap').style.display='none';
            }
        }
    </script>
</head>
<body class=" wp-version-<?php echo str_replace( '.', '-', $wp_version ); ?> xtpb-frontend-active">
    <div class="xtpb-iframe-loading-wrap" id="xtpbMainIframeLoadingWrap" >
        <img src="<?php echo XTPB_DIR_URL.'assets/img/loading-md.svg'; ?>" alt="loading"/>
    </div>
    <div id="xtpb-page-builder">
        <?php $load_iframe_url = add_query_arg( array('load_for' => 'xtpb_editor_iframe') , get_the_permalink(get_the_ID())); ?>
        <iframe width="100%" height="400" id="xtpb-builder-view" data-page-id="<?php echo get_the_ID(); ?>" name="xtpb-builder-view" src="<?php echo $load_iframe_url; ?>" onload="onLoadHandler();"></iframe>
        <div id="xtpb-builder-page-tools" class="xtpb-builder-page-tools"></div>
    </div>
    <?php
        wp_footer();
        do_action( 'admin_print_footer_scripts' );
    ?>
</body>
</html>
