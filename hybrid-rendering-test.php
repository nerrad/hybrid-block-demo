<?php
/**
 * Plugin Name:     Hybrid Rendering Test
 * Description:     Example block written with ESNext standard and JSX support – build step required.
 * Version:         0.1.0
 * Author:          The WordPress Contributors
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     create-block
 *
 * @package         create-block
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

require 'vendor/autoload.php';

function hybrid_rendering_get_attributes() {
	return [
		'postId' => [
			'type'    => 'string',
			'default' => '',
		],
		'className' => [
			'type' => 'string',
			'default' => '',
		]
		];
}

function hybrid_block_get_html_data_attributes( array $attributes ) {
		$data = [];

		foreach ( $attributes as $key => $value ) {
			if ( is_bool( $value ) ) {
				$value = $value ? 'true' : 'false';
			}
			if ( ! is_scalar( $value ) ) {
				$value = wp_json_encode( $value );
			}
			$data[] = 'data-' . esc_attr( strtolower( preg_replace( '/(?<!\ )[A-Z]/', '-$0', $key ) ) ) . '="' . esc_attr( $value ) . '"';
		}

		return implode( ' ', $data );
	}

function hybrid_rendering_test_block_render( $attributes, $content = '' ) {
	$post_title = ! empty( $attributes['postId'] ) ? get_the_title( $attributes['postId'] ) : '';
	if ( $post_title === '' || $content === '' ) {
		return '';
	}
	$attributes['postTitle'] = $post_title;
	return '<div id="hydrate-block" ' . hybrid_block_get_html_data_attributes( $attributes ) . '">' . ( new Mustache_Engine( [ 'entity_flags' => ENT_QUOTES ] ) )->render( $content, [ 'postTitle' => $post_title ] ) . '</div>';
}

function create_block_hybrid_rendering_test_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	$frontend_asset_path = "$dir/build/frontend.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/hybrid-rendering-test" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require $script_asset_path;
	wp_register_script(
		'create-block-hybrid-rendering-test-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	$frontend_asset = require $frontend_asset_path;
	if ( ! is_admin() ) {
		wp_register_script(
			'create-block-hybrid-rendering-test-block-editor-frontend',
			plugins_url( 'build/frontend.js', __FILE__ ),
			$frontend_asset['dependencies'],
			$frontend_asset['version'],
			true
		);
	}

	$editor_css = 'editor.css';
	wp_register_style(
		'create-block-hybrid-rendering-test-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'style.css';
	wp_register_style(
		'create-block-hybrid-rendering-test-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'create-block/hybrid-rendering-test', array(
		'editor_script' => 'create-block-hybrid-rendering-test-block-editor',
		'editor_style'  => 'create-block-hybrid-rendering-test-block-editor',
		'script' => 'create-block-hybrid-rendering-test-block-editor-frontend',
		'style'         => 'create-block-hybrid-rendering-test-block',
		'render_callback' => 'hybrid_rendering_test_block_render',
		'attributes'      => hybrid_rendering_get_attributes(),
	) );
}
add_action( 'init', 'create_block_hybrid_rendering_test_block_init' );
