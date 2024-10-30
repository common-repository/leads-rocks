<table class="form-table frm-no-margin">
	<tbody>
	<tr>
		<th><label for="campaign"><?php esc_html_e( 'Campaign', 'leads-rocks' ); ?></label></th>
		<td>
			<select name="<?php echo esc_attr( $this->get_field_name( 'campaign' ) ) ?>" id="camapign">
				<option value="-1"><?php esc_html_e( 'Select campaign', 'leads-rocks' ); ?></option>
				<?php foreach ( $campaigns as $campaign ) : ?>
					<option value="<?php esc_attr_e( $campaign->_id ); ?>" <?php selected( $campaign_id, $campaign->_id ); ?>><?php esc_html_e( $campaign->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<?php if ( ! is_null( $current_campaign ) ) : ?>
		<?php foreach ( $current_campaign->fields as $field ) : ?>
			<tr>
				<th><label for="<?php echo esc_attr( $field->field ); ?>"><?php echo esc_html( $field->title ); ?></label></th>
				<td>
					<select name="<?php echo esc_attr( $this->get_field_name( $field->field ) ) ?>" id="<?php echo esc_attr( $field->field ); ?>">
						<option value="-1"><?php esc_html_e( 'Select tag', 'leads-rocks' ); ?></option>
						<?php foreach ( $form_fields as $form_field ) : ?>
							<option value="<?php echo esc_attr( $form_field->id ) ?>" <?php selected( $fields_values[ $field->field ], $form_field->id ); ?>><?php echo FrmAppHelper::truncate( $form_field->name, 40 ) ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>