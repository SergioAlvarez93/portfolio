<?php

$options = array(
	'content'     => __( 'URLs in page content ( posts, pages, custom post types, revisions)', 'update-urls' ),
	'excerpts'    => __( 'URLs in excerpts', 'update-urls' ),
	'attachments' => __( 'URLs for attachments ( images, documents, general media )', 'update-urls' ),
	'links'       => __( 'URLs in links', 'update-urls' ),
	'custom'      => __( 'URLs in custom fields and meta boxes', 'update-urls' ),
	'guids'       => sprintf( __( 'Update ALL GUIDs GUIDs for posts should only be changed on development sites. <a href="%s">Learn More</a>.', 'update-urls' ), 'http://codex.wordpress.org/Changing_The_Site_URL#Important_GUID_Note' )
);

if ( isset( $_POST['kc_uu_settings_submit'] ) && ! check_admin_referer( 'kc_uu_submit', 'kc_uu_nonce' ) ) {
	if ( isset( $_POST['kc_uu_oldurl'] ) && isset( $_POST['kc_uu_newurl'] ) ) {
		$kc_uu_oldurl = esc_url_raw( wp_unslash( $_POST['kc_uu_oldurl'] ) );
		$kc_uu_newurl = esc_url_raw( wp_unslash( $_POST['kc_uu_newurl'] ) );
	}
	echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'ERROR', 'update-urls' ) . ' - ' . esc_html__( 'Please try again.', 'update-urls' ) . '</strong></p></div>';
} elseif ( isset( $_POST['kc_uu_settings_submit'] ) && ! isset( $_POST['kc_uu_update_links'] ) ) {
	if ( isset( $_POST['kc_uu_oldurl'] ) && isset( $_POST['kc_uu_newurl'] ) ) {
		$kc_uu_oldurl = esc_url_raw( wp_unslash( $_POST['kc_uu_oldurl'] ) );
		$kc_uu_newurl = esc_url_raw( wp_unslash( $_POST['kc_uu_newurl'] ) );
	}
	echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'ERROR', 'update-urls' ) . ' - ' . esc_html__( 'Your URLs have not been updated.', 'update-urls' ) . '</p></strong><p>' . esc_html__( 'Please select at least one checkbox.', 'update-urls' ) . '</p></div>';
} elseif ( isset( $_POST['kc_uu_settings_submit'] ) ) {

$kc_uu_update_links = isset( $_POST['kc_uu_update_links'] ) ? (array) $_POST['kc_uu_update_links'] : array();

$kc_uu_update_links = array_map( 'esc_attr', $kc_uu_update_links );

if ( isset( $_POST['kc_uu_oldurl'] ) && isset( $_POST['kc_uu_newurl'] ) ) {
	$kc_uu_oldurl = esc_url_raw( wp_unslash( $_POST['kc_uu_oldurl'] ) );
	$kc_uu_newurl = esc_url_raw( wp_unslash( $_POST['kc_uu_newurl'] ) );
}
if ( ( $kc_uu_oldurl && $kc_uu_oldurl != 'http://www.oldurl.com' && trim( $kc_uu_oldurl ) != '' ) && ( $kc_uu_newurl && $kc_uu_newurl != 'http://www.newurl.com' && trim( $kc_uu_newurl ) != '' ) ) {
$results = \Kaizencoders\Update_Urls\Helper::update_urls( $kc_uu_update_links, $kc_uu_oldurl, $kc_uu_newurl );


$empty       = true;
$emptystring = '<strong>' . __( 'Why do the results show 0 URLs updated?', 'update-urls' ) . '</strong><br/>' . __( 'This happens if a URL is incorrect OR if it is not found in the content. Check your URLs and try again.', 'update-urls' );

$resultstring = '';
foreach ( $results as $result ) {
	$empty        = ( $result[0] != 0 || $empty == false ) ? false : true;
	$resultstring .= '<br/><strong>' . $result[0] . '</strong> ' . $result[1];
}

if ( $empty ) :
?>
<div id="message" class="error fade">
    <table>
        <tr>
            <td><p><strong>
						<?php _e( 'ERROR: Something may have gone wrong.', 'update-urls' ); ?>
                    </strong><br/>
					<?php _e( 'Your URLs have not been updated.', 'update-urls' ); ?>
                </p>
				<?php
				else :
				?>
                <div id="message" class="updated fade">
                    <table>
                        <tr>
                            <td><p><strong>
										<?php _e( 'Success! Your URLs have been updated.', 'update-urls' ); ?>
                                    </strong></p>
								<?php
								endif;
								?>
                                <p><u>
										<?php _e( 'Results', 'update-urls' ); ?>
                                    </u><?php echo $resultstring; ?></p>
								<?php echo ( $empty ) ? '<p>' . $emptystring . '</p>' : ''; ?></td>
                            <td width="60"></td>
                            <td align="center"><?php if ( ! $empty ) : ?>
                                    <p>
									<?php // You can now uninstall this plugin.<br/> ?>
								<?php endif; ?></td>
                        </tr>
                    </table>
                </div>
				<?php
				} else {
					echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'ERROR', 'update-urls' ) . ' - ' . esc_html__( 'Your URLs have not been updated.', 'update-urls' ) . '</p></strong><p>' . esc_html_e( 'Please enter values for both the old url and the new url.', 'update-urls' ) . '</p></div>';
				}
				}
				?>


<div class="bg-white>
        <div class="flex flex-auto">

            <form method="post" action="" class="p-10 min-h-full">
                <?php wp_nonce_field( 'kc_uu_submit', 'kc_uu_nonce' ); ?>
                <div class="section bg-gray-100 p-5 mb-5 border-2">
                    <p class="text-xl bold-text text-center mb-5 underline">Important Note</p>
                    <ul>
                        <li>👉 <?php printf( esc_html__( 'After moving a website, %s lets you fix old URLs in content, excerpts, links, and custom fields.', 'update-urls' ), '<strong>Update URLs</strong>' ); ?></li>
                        <li class="text-red-500 bold-text">👉 <?php esc_html_e( 'WE RECOMMEND THAT YOU BACKUP YOUR WEBSITE.', 'update-urls' ); ?></li>
                        <li>👉 <?php esc_html_e( 'You may need to restore it if incorrect URLs are entered in the fields below.', 'update-urls' ); ?></li>
                    </ul>
                </div>

                <div class="flex flex-row border-b border-gray-100">
                    <div class="flex w-1/5">
                        <div class="pt-6 ml-4">
                            <label for="tag-link">
                                <span class="block pt-1 pb-2 pr-4 ml-4 text-sm font-medium text-gray-600"><?php echo __( 'Old URL', 'update-urls' ); ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex w-4/5">
                        <div class="w-full h-10 mt-4 mb-4 ml-16 mr-4">
                            <div class="relative h-10">
                                <input id=""
                                       class="block w-2/3 pl-3 pr-12 border-gray-400 shadow-sm form-input  focus:bg-gray-100 sm:text-sm sm:leading-5"
                                       placeholder="" name="kc_uu_oldurl"
                                       value=""
                                       size="30" maxlength="100"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row border-b border-gray-100">
                    <div class="flex w-1/5">
                        <div class="pt-6 ml-4">
                            <label for="tag-link">
                                <span class="block pt-1 pb-2 pr-4 ml-4 text-sm font-medium text-gray-600"><?php echo __( 'New URL', 'update-urls' ); ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex w-4/5">
                        <div class="w-full h-10 mt-4 mb-4 ml-16 mr-4">
                            <div class="relative h-10">
                                <input id=""
                                       class="block w-2/3 pl-3 pr-12 border-gray-400 shadow-sm form-input  focus:bg-gray-100 sm:text-sm sm:leading-5"
                                       placeholder="" name="kc_uu_newurl"
                                       value=""
                                       size="30" maxlength="100"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row border-b border-gray-100">
                    <div class="flex w-1/5">
                        <div class="pt-2 ml-4">
                            <label for="tag-link">
                                <span class="block pt-1 pb-2 pr-4 ml-4 text-sm font-medium text-gray-600"><?php echo __( 'Where To Update?', 'update-urls' ); ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex w-4/5">
                        <div class="w-full h-10 mt-4 mb-4 ml-16 mr-4">
                                <div class="space-y-6 text-indigo-400 text-xl">
	                                <?php _e( 'Choose which URLs should be updated', 'update-urls' ); ?>
                                </div>
                        </div>
                    </div>
                </div>

                <?php foreach ($options as $option => $desc) { ?>
                    <div class="flex flex-row border-b border-gray-100">
                        <div class="flex w-1/5">
                            <div class="pt-6 ml-4">

                            </div>
                        </div>
                        <div class="flex w-4/5">
                            <div class="w-full h-10 mt-4 mb-4 ml-16 mr-4">
                                <div class="space-y-6">
                                    <div class="relative flex gap-x-3">
                                        <div class="flex h-6 items-center">
                                            <input id="<?php echo $option; ?>" name="kc_uu_update_links[]"
                                                   type="checkbox"
                                                   class="h-4 w-4 rounded border-gray-300 form-checkbox text-indigo-600 focus:ring-indigo-600"
                                                   value="<?php echo $option; ?>" />
                                        </div>
                                        <div class="text-sm">
                                            <label for="<?php echo $option; ?>" class="font-medium text-gray-900"><?php echo ucfirst($option); ?></label>
                                            <p class="text-gray-500"><?php echo $desc; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                <div class="flex flex-row border-b border-gray-100 mt-10">
                    <div class="flex w-1/5">
                        <div class="ml-4">
                            <input class="button-primary" name="kc_uu_settings_submit"
                                   value="<?php esc_attr_e( 'Update URLs NOW', 'update-urls' ); ?>"
                                   type="submit" />
                        </div>
                    </div>
                </div>
            </form>
        </div>







