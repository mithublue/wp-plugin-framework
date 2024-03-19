<?php
namespace App\includes\core\traits;

trait Fields {
	public function text( $arg ) {
		?>
		<div><?php esc_html_e( $arg['title'] ); ?></div>
		<input type="text" name="<?php esc_attr_e( $arg['name'] ); ?>" value="<?php esc_html_e( $arg['value'] ); ?>">
		<div class="description"><?php esc_html_e( $arg['desc'] ); ?></div>
		<?php
	}

	public function textarea( $arg ) {
		?>
		<div><?php esc_html_e( $arg['title'] ); ?></div>
		<textarea name="<?php esc_attr_e( $arg['name'] ); ?>" id="" cols="30" rows="10"><?php esc_html_e( $arg['value'] ); ?></textarea>
		<div class="description"><?php esc_html_e( $arg['desc'] ); ?></div>
		<?php
	}

	public function checkbox( $arg ) {
		?>
		<div><?php esc_html_e( $arg['title'] ); ?></div>
		<input type="checkbox" name="<?php esc_attr_e( $arg['name'] ); ?>"
		       value="<?php esc_html_e( $arg['field_value'] ); ?>"
		       <?php echo esc_html_e( $arg['field_value'] ) == esc_html_e( $arg['value'] ) ? 'checked' : ''; ?>
		>
		<div class="description"><?php esc_html_e( $arg['desc'] ); ?></div>
		<?php
	}

	public function radio( $arg ) {
		?>
		<div><?php esc_html_e( $arg['title'] ); ?></div>
		<input type="radio" name="<?php esc_attr_e( $arg['name'] ); ?>"
		       value="<?php esc_html_e( $arg['field_value'] ); ?>"
			<?php echo esc_html_e( $arg['field_value'] ) == esc_html_e( $arg['value'] ) ? 'checked' : ''; ?>
		>
		<div class="description"><?php esc_html_e( $arg['desc'] ); ?></div>
		<?php
	}

	public function select( $arg ) {
		?>
		<div><?php esc_html_e( $arg['title'] ); ?></div>
		<select name="<?php esc_attr_e( $arg['name'] ); ?>" id="<?php esc_attr_e( $arg['name'] ); ?>">
			<?php
			foreach ( $arg['options'] as $value => $label ) {
				echo '<option name="' . $value . '" ' . ( $value == $arg['value'] ? 'selected' : '' ) . ' >' . $label . '</option>';
			}
			?>
		</select>
		<div class="description"><?php esc_html_e( $arg['desc'] ); ?></div>
		<?php
	}

	public function number( $arg ) {
		?>
		<div><?php esc_html_e( $arg['title'] ); ?></div>
		<input type="number" name="<?php esc_attr_e( $arg['name'] ); ?>" value="<?php esc_html_e( $arg['value'] ); ?>">
		<div class="description"><?php esc_html_e( $arg['desc'] ); ?></div>
		<?php
	}
}
