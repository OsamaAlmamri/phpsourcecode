<?php

// Refresh the schedules from the database to make sure we have the latest changes
HMBKP_Schedules::get_instance()->refresh_schedules();

$schedules = HMBKP_Schedules::get_instance()->get_schedules();

if ( ! empty( $_GET['hmbkp_schedule_id'] ) ) {
	$current_schedule = new HMBKP_Scheduled_Backup( sanitize_text_field( $_GET['hmbkp_schedule_id'] ) );
} else {
	$current_schedule = reset( $schedules );
} ?>

<h2 class="nav-tab-wrapper">

	<?php foreach ( $schedules as $schedule ) : ?>

		<a class="nav-tab<?php if ( $schedule->get_status() ) { ?> hmbkp-running<?php } ?><?php if ( $schedule->get_id() === $current_schedule->get_id() ) { ?> nav-tab-active<?php } ?>" <?php if ( $schedule->get_status() ) { ?>title="<?php echo esc_attr( strip_tags( $schedule->get_status() ) ); ?>"<?php } ?> href="<?php echo esc_url( add_query_arg( 'hmbkp_schedule_id', $schedule->get_id(), HMBKP_ADMIN_URL ) ); ?> "><?php echo esc_html( hmbkp_translated_schedule_title( $schedule->get_slug(), $schedule->get_name() ) ); ?> <span class="count">(<?php echo esc_html( count( $schedule->get_backups() ) ); ?>)</span></a>

	<?php endforeach; ?>

	<a class="nav-tab<?php if ( ! HMBKP_Schedules::get_instance()->get_schedule( $current_schedule->get_id() ) ) { ?> nav-tab-active<?php } ?>" href="<?php echo esc_url( add_query_arg( array( 'hmbkp_add_schedule' => '1', 'action' => 'hmbkp_edit_schedule', 'hmbkp_schedule_id' => time(), 'hmbkp_panel' => 'hmbkp_edit_schedule_settings' ), HMBKP_ADMIN_URL ) ); ?>"> + <?php _e( '计划任务', 'backupwordpress' ); ?></a>

	<?php if ( get_option( 'hmbkp_enable_support' ) ) { ?>

		<a id="intercom" class="add-new-h2" href="mailto:support@hmn.md"><?php _e( '支持', 'backupwordpress' ); ?></a>

	<?php } else {

		add_thickbox(); ?>

		<a id="intercom-info" class="thickbox add-new-h2" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'load_enable_support', 'width' => '600', 'height' => '420' ), is_multisite() ? admin_url( 'admin-ajax.php' ) : network_admin_url( 'admin-ajax.php' ) ), 'hmbkp_nonce' ); ?>"><span class="dashicons dashicons-admin-users"></span>&nbsp;<?php _e( '支持', 'backupwordpress' ); ?></a>

	<?php } ?>

</h2>

<?php // Don't continue if we don't have a schedule
if ( ! $schedule = $current_schedule ) {
	return;
} ?>

<div data-hmbkp-schedule-id="<?php echo esc_attr( $schedule->get_id() ); ?>" class="hmbkp_schedule">

	<?php require( HMBKP_PLUGIN_PATH . 'admin/schedule-sentence.php' ); ?>

	<?php require( HMBKP_PLUGIN_PATH . 'admin/backups-table.php' ); ?>

</div>