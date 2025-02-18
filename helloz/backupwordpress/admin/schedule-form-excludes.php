<div class="hmbkp-exclude-settings">

	<?php if ( $schedule->get_excludes() ) : ?>

		<h3><?php _e( '目前被排除', 'backupwordpress' ); ?></h3>

		<table class="widefat">

			<tbody>

				<?php foreach ( $schedule->get_excludes() as $key => $exclude ) :

					$exclude_path = new SplFileInfo( trailingslashit( $schedule->get_root() ) . ltrim( str_ireplace( $schedule->get_root(), '', $exclude ), '/' ) ); ?>

					<tr>

						<th scope="row">

							<?php if ( $exclude_path->isFile() ) { ?>

								<div class="dashicons dashicons-media-default"></div>

							<?php } elseif ( $exclude_path->isDir() ) { ?>

								<div class="dashicons dashicons-portfolio"></div>

							<?php } ?>

						</th>

						<td>
							<code><?php echo esc_html( str_ireplace( $schedule->get_root(), '', $exclude ) ); ?></code>

						</td>

						<td>

							<?php if ( ( $schedule->get_path() === untrailingslashit( $exclude ) ) || ( in_array( $exclude, $schedule->default_excludes() ) ) ) : ?>

								<?php _e( '默认规则', 'backupwordpress' ); ?>

							<?php elseif ( defined( 'HMBKP_EXCLUDE' ) && strpos( HMBKP_EXCLUDE, $exclude ) !== false ) : ?>

								<?php _e( 'Defined in wp-config.php', 'backupwordpress' ); ?>

							<?php else : ?>

								<a href="<?php echo hmbkp_admin_action_url( 'remove_exclude_rule', array( 'hmbkp_remove_exclude' => $exclude, 'hmbkp_schedule_id' => $schedule->get_id() ) ); ?>" class="delete-action"><?php _e( 'Stop excluding', 'backupwordpress' ); ?></a>

							<?php endif; ?>

						</td>

					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

	<?php endif; ?>

	<h3 id="directory-listing"><?php _e( '目录列表', 'backupwordpress' ); ?></h3>

	<p><?php _e( '这是您网站上的所有文件的目录列表，您可以浏览和排除的文件或文件夹，您不想包括在您的备份。', 'backupwordpress' ); ?></p>

	<?php

	// The directory to display
	$directory = $schedule->get_root();

	if ( isset( $_GET['hmbkp_directory_browse'] )  ) {

		$untrusted_directory = urldecode( $_GET['hmbkp_directory_browse'] );

		// Only allow real sub directories of the site root to be browsed
		if ( strpos( $untrusted_directory, $schedule->get_root() ) !== false && is_dir( $untrusted_directory ) ) {
			$directory = $untrusted_directory;
		}

	}

	$exclude_string = $schedule->exclude_string( 'regex' );

	// Kick off a recursive filesize scan
	$files = $schedule->list_directory_by_total_filesize( $directory );

	if ( $files ) { ?>

		<table class="widefat">

			<thead>

				<tr>
					<th></th>
					<th scope="col"><?php _e( '目录', 'backupwordpress' ); ?></th>
					<th scope="col" class="column-format"><?php _e( '大小', 'backupwordpress' ); ?></th>
					<th scope="col" class="column-format"><?php _e( '权限', 'backupwordpress' ); ?></th>
					<th scope="col" class="column-format"><?php _e( '类型', 'backupwordpress' ); ?></th>
					<th scope="col" class="column-format"><?php _e( '状态', 'backupwordpress' ); ?></th>
				</tr>

				<tr>

					<th scope="row">
						<div class="dashicons dashicons-admin-home"></div>
					</th>

					<th scope="col">

						<?php if ( $schedule->get_root() !== $directory ) { ?>

							<a href="<?php echo remove_query_arg( 'hmbkp_directory_browse' ); ?>"><?php echo esc_html( $schedule->get_root() ); ?></a> <code>/</code>

							<?php $parents = array_filter( explode( '/', str_replace( trailingslashit( $schedule->get_root() ), '', trailingslashit( dirname( $directory ) ) ) ) );

							foreach ( $parents as $directory_basename ) { ?>

								<a href="<?php echo add_query_arg( 'hmbkp_directory_browse', urlencode( substr( $directory, 0, strpos( $directory, $directory_basename ) ) . $directory_basename ) ); ?>"><?php echo esc_html( $directory_basename ); ?></a> <code>/</code>

							<?php } ?>

							<?php echo esc_html( basename( $directory ) ); ?>

						<?php } else { ?>

							<?php echo esc_html( $schedule->get_root() ); ?>

						<?php } ?>

					</th>

					<td class="column-filesize">

						<?php if ( $schedule->is_site_size_being_calculated() ) { ?>

							<span class="spinner"></span>

						<?php } else {

							$root = new SplFileInfo( $schedule->get_root() );

							$size = $schedule->filesize( $root );

							if ( $size !== false ) {

								$size = size_format( $size );

								if ( ! $size ) {
									$size = '0 B';
								} ?>

								<code>

									<?php echo esc_html( $size ); ?>

									<a class="dashicons dashicons-update" href="<?php echo wp_nonce_url( add_query_arg( 'hmbkp_recalculate_directory_filesize', urlencode( $schedule->get_root() ) ), 'hmbkp-recalculate_directory_filesize' ); ?>"><span><?php _e( 'Refresh', 'backupwordpress' ); ?></span></a>

								</code>


							<?php } ?>

						<?php } ?>

					<td>
						<?php echo esc_html( substr( sprintf( '%o', fileperms( $schedule->get_root() ) ), -4 ) ); ?>
					</td>

					<td>

						<?php if ( is_link( $schedule->get_root() ) ) {

							_e( 'Symlink', 'backupwordpress' );

						} elseif ( is_dir( $schedule->get_root() ) ) {

							_e( 'Folder', 'backupwordpress' );

						} ?>

					</td>

					<td></td>

				</tr>

			</thead>

			<tbody>

				<?php foreach ( $files as $size => $file ) {

					$is_excluded = $is_unreadable = false;

					// Check if the file is excluded
					if ( $exclude_string && preg_match( '(' . $exclude_string . ')', str_ireplace( trailingslashit( $schedule->get_root() ), '', HM_Backup::conform_dir( $file->getPathname() ) ) ) ) {
						$is_excluded = true;
					}

					// Skip unreadable files
					if ( ! @realpath( $file->getPathname() ) || ! $file->isReadable() ) {
						$is_unreadable = true;
					} ?>

					<tr>

						<td>

							<?php if ( $is_unreadable ) { ?>

								<div class="dashicons dashicons-dismiss"></div>

							<?php } elseif ( $file->isFile() ) { ?>

								<div class="dashicons dashicons-media-default"></div>

							<?php } elseif ( $file->isDir() ) { ?>

								<div class="dashicons dashicons-portfolio"></div>

							<?php } ?>

						</td>

						<td>

							<?php if ( $is_unreadable ) { ?>

								<code class="strikethrough" title="<?php echo esc_attr( $file->getRealPath() ); ?>"><?php echo esc_html( $file->getBasename() ); ?></code>

							<?php } elseif ( $file->isFile() ) { ?>

								<code title="<?php echo esc_attr( $file->getRealPath() ); ?>"><?php echo esc_html( $file->getBasename() ); ?></code>

							<?php } elseif ( $file->isDir() ) { ?>

								<code title="<?php echo esc_attr( $file->getRealPath() ); ?>"><a href="<?php echo add_query_arg( 'hmbkp_directory_browse', urlencode( $file->getPathname() ) ); ?>"><?php echo esc_html( $file->getBasename() ); ?></a></code>

							<?php } ?>

						</td>

						<td class="column-format column-filesize">

							<?php if ( $file->isDir() && $schedule->is_site_size_being_calculated() ) { ?>

								<span class="spinner"></span>

							<?php } else {

								$size = $schedule->filesize( $file );

								if ( false !== $size ) {

									$size = size_format( $size );

									if ( ! $size ) {
										$size = '0 B';
									} ?>

									<code>

										<?php echo esc_html( $size ); ?>

										<?php if ( $file->isDir() ) { ?>

											<a title="<?php _e( 'Recalculate the size of this directory', 'backupwordpress' ); ?>" class="dashicons dashicons-update" href="<?php echo wp_nonce_url( add_query_arg( 'hmbkp_recalculate_directory_filesize', urlencode( $file->getPathname() ) ), 'hmbkp-recalculate_directory_filesize' ); ?>"><span><?php _e( 'Refresh', 'backupwordpress' ); ?></span></a>

										<?php }  ?>

									</code>


								<?php } else { ?>

									<code>--</code>

								<?php }
							} ?>

						</td>

						<td>
							<?php echo esc_html( substr( sprintf( '%o', $file->getPerms() ), -4 ) ); ?>
						</td>

						<td>

							<?php if ( $file->isLink() ) { ?>

								<span title="<?php echo esc_attr( $file->GetRealPath() ); ?>"><?php _e( 'Symlink', 'backupwordpress' ); ?></span>

							<?php } elseif ( $file->isDir() ) {

								_e( 'Folder', 'backupwordpress' );

							} else {

								_e( 'File', 'backupwordpress' );

							} ?>

						</td>

						<td class="column-format">

							<?php if ( $is_unreadable ) { ?>

								<strong title="<?php _e( 'Unreadable files won\'t be backed up.', 'backupwordpress' ); ?>"><?php _e( 'Unreadable', 'backupwordpress' ); ?></strong>

							<?php } elseif ( $is_excluded ) { ?>

								<strong><?php _e( 'Excluded', 'backupwordpress' ); ?></strong>

							<?php } else {

								$exclude_path = $file->getPathname();

								// Excluded directories need to be trailingslashed
								if ( $file->isDir() ) {
									$exclude_path = trailingslashit( $file->getPathname() );
								} ?>

								<a href="<?php echo wp_nonce_url( add_query_arg( array( 'hmbkp_schedule_id' => $schedule->get_id(), 'action' => 'hmbkp_add_exclude_rule', 'hmbkp_exclude_pathname' => urlencode( $exclude_path ) ), admin_url( 'admin-post.php' ) ), 'hmbkp-add-exclude-rule', 'hmbkp-add-exclude-rule-nonce' ); ?>" class="button-secondary"><?php _e( '排除 &rarr;', 'backupwordpress' ); ?></a>

							<?php } ?>

						</td>

					</tr>

				<?php } ?>

			</tbody>

		</table>

	<?php } ?>

</div>

<p class="submit">
	<a href="<?php echo esc_url( hmbkp_get_settings_url() ) ?>" class="button-primary"><?php _e( '保存', 'backupwordpress' ); ?></a>
</p>