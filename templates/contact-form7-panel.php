<div id="leads-rocks-panel" class="leads-rocks panel">
	<h2><?php esc_html_e( 'Leads Rocks! Integration', 'leads-rocks' ); ?></h2>
	<fieldset>
		<legend><?php esc_html_e( 'Enable integration between Contact Form 7 and Leads Rocks!', 'leads-rocks' ); ?></legend>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="leads-rocks-campaign"><?php esc_html_e( 'Campaign', 'leads-rocks' ); ?></label>
				</th>
				<td>
					<select name="leads-rocks-campaign" id="leads-rocks-campaign">
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
						<th scope="row">
							<label for="leads-rocks-fields[<?php echo esc_attr( $field->field ); ?>]"><?php echo esc_html( $field->title ); ?></label>
						</th>
						<td>
							<select name="leads-rocks-fields[<?php echo esc_attr( $field->field ); ?>]" id="leads-rocks-fields[<?php echo esc_attr( $field->field ); ?>]">
								<option value="-1"><?php esc_html_e( 'Select tag', 'leads-rocks' ); ?></option>
								<?php foreach ( $tags as $tag ): ?>
									<option value="<?php echo esc_attr( $tag ); ?>" <?php selected( $fields[ $field->field ], $tag ); ?>><?php echo esc_html( '[' . $tag . ']' ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</fieldset>
</div>