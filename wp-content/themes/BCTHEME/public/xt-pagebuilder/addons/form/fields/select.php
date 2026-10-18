<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( isset( $field['select_options'] ) ){
	$options = $field['select_options'];
	if (count($options)){
		?>
		<span class="xtpb-form-field-item xtpb-form-field-select">
			<select name="xtpb_default_form[<?php echo $fieldIndex; ?>]" <?php echo $fieldAttr; ?> >
				<?php
				foreach ($options as $key => $option){
					?>
					<option value="<?php echo esc_attr( $option['label'] ); ?>"><?php echo esc_html( $option['label'] ); ?></option>
					<?php
				}
				?>
			</select>
		</span>
		<?php
	}
}
