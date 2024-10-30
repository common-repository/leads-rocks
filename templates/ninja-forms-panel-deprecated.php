<tr>
	<th scope="row">
		<label for="settings-campaign"><?php esc_html_e( 'Campaign', 'leads-rocks' ); ?>
			<span class="lr_required">*</span></label></th>
	<td>
		<select name="settings[campaign]" id="settings-campaign">
			<option value="-1"><?php esc_html_e( 'Select campaign', 'leads-rocks' ); ?></option>
			<?php foreach ( $campaigns as $campaign ): ?>
				<option value="<?php echo esc_attr( $campaign->_id ); ?>" <?php selected( $campaign_id, $campaign->_id ); ?>><?php echo esc_html( $campaign->name ); ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>
<?php if ( isset( $current_campaign ) ) : ?>
	<?php foreach ( $current_campaign->fields as $field ) : ?>
		<?php $field_value = Ninja_Forms()->notification( $id )->get_setting( 'field_' . $field->field ); ?>
		<tr>
			<th scope="row">
				<label for="settings-field_<?php echo esc_attr( $field->field ); ?>"><?php echo esc_html( $field->title ); ?></label>
			</th>
			<td>
				<input name="settings[field_<?php echo esc_attr( $field->field ); ?>]" type="text" id="settings-field_<?php echo esc_attr( $field->field ); ?>" value="<?php echo $field_value; ?>" class="nf-tokenize" data-token-limit="1" data-key="field_<?php echo esc_attr( $field->field ); ?>" data-type="all"/>
			</td>
		</tr>
	<?php endforeach; ?>

	<script>
		<?php foreach ( $current_campaign->fields as $field ) : $field_data = $this->get_value( $id, 'field_' . esc_attr( $field->field ), $form_id ); ?>
		nf_notifications.tokens[ 'field_<?php echo esc_js( $field->field ); ?>' ] = <?php echo json_encode( $field_data ); ?>;
		<?php endforeach; ?>
	</script>
<?php endif; ?>
