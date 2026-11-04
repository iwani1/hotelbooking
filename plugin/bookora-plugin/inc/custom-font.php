<?php 
/**
 * Add Multiple Font Groups to Elementor
 */
add_filter( 'elementor/fonts/groups', 'prefix_elementor_custom_fonts_group', 10, 1 );
function prefix_elementor_custom_fonts_group( $font_groups ) {
	$font_groups['Tondu Beta'] = __( 'Tondu Beta' );
	$font_groups['Wulkan'] = __( 'Wulkan' );
	return $font_groups;
}

/**
 * Add Multiple Group Fonts to Elementor
 */
add_filter( 'elementor/fonts/additional_fonts', 'prefix_elementor_custom_fonts', 10, 1 );
function prefix_elementor_custom_fonts( $additional_fonts ) {
	// Font name => Font group
	$additional_fonts['Tondu Beta'] = 'Tondu Beta';
	$additional_fonts['Wulkan'] = 'Wulkan';
	return $additional_fonts;
}
