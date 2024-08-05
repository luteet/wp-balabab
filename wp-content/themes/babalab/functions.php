<?php

function custom_log($message, $clear_log=false) {
    if (WP_DEBUG === true) {
        $log_file = WP_CONTENT_DIR . '/debug.log';

        if (!is_string($message)) {
            $message = print_r($message, true);
        }

        $backtrace = debug_backtrace();
        $caller_file = isset($backtrace[0]['file']) ? basename($backtrace[0]['file']) : 'unknown file';
    
        $formatted_message = date('Y-m-d H:i:s') . " - " . $caller_file . " - " . $message . PHP_EOL;

        $write_mode = $clear_log ? 0 : FILE_APPEND;

        file_put_contents($log_file, $formatted_message, $write_mode);
    }
}

// --- FRONTEND SCRIPTS ---


add_action('wp_enqueue_scripts', 'theme_add_scripts');
function theme_add_scripts()
{

	$main_css_file = get_template_directory_uri() . '/assets/css/style.min.css';
	$libs_js_file = get_template_directory_uri() . '/assets/js/libs.min.js';
	$main_js_file = get_template_directory_uri() . '/assets/js/main.js';
    $version = "1.0.15";

	wp_enqueue_style('style', $main_css_file, array(), $version);
	wp_enqueue_script('libs', $libs_js_file, array(), $version, true);
	wp_enqueue_script('main', $main_js_file, array(), $version, true);
	
}

// --- /FRONTEND SCRIPTS ---


function enqueue_admin_login_script() {
    if (is_user_logged_in()) {
        wp_enqueue_script('save-admin', get_template_directory_uri() . '/assets/js/admin.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_login_script');




function get_img($image, $arguments=false) {

	if($image) {
		if($image["url"]) {

			$imageSrc = $image["url"];

			$imageWidth = $image["width"];
			$imageHeight = $image["height"];
			$class = "";
			$lazy = ' loading="lazy"';
			$attributes = "";

			if($arguments) {
				if(isset($arguments["size"])) {
					$imageWidth = $arguments["size"];
					$imageHeight = $arguments["size"];
				}
				if(isset($arguments["width"])) $imageWidth = $arguments["width"];
				if(isset($arguments["height"])) $imageHeight = $arguments["height"];
				if(isset($arguments["class"])) $class = ' class="' . $arguments["class"] . '"';
				if(isset($arguments["lazy"])) {
					if($arguments["lazy"] == false) $lazy = '';
				}
				if(isset($arguments["attributes"])) {
					foreach ($arguments["attributes"] as $key => $value) {
						$attributes .= ' ' . $key . '="' . $value . '"';
					}
				}
				
			}

			$img = '<img src="' . $imageSrc . '" alt="' . $image["alt"] . '" width="' . $imageWidth . '" height="' . $imageHeight . '"' . $lazy . '' . $attributes . ' ' . $class . '>';
			
			if($image["subtype"] == "svg+xml" || $image["subtype"] == "webp" || $image["subtype"] == "avif") {
				return $img;
			} else {
				return '
				<picture>
					<source srcset="' . getClearImage($imageSrc) . 'webp" type="image/webp">
					'. $img . '
				</picture>
				';
			}

		} else return "";
	} else return "";

}



// --- GUTENBERG ---

function enqueue_gutenberg_editor_assets() {
    wp_enqueue_script('jquery');
}

//add_action('enqueue_block_editor_assets', 'enqueue_gutenberg_editor_assets');

// --- /GUTENBERG ---



// --- THEME ---

add_theme_support('custom-logo');
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_filter( 'excerpt_more', fn() => '...' );


// --- /THEME ---



// --- ACF ---

function remove_plugin_updates($value)
{
	unset($value->response['advanced-custom-fields-pro-master/acf.php']);
	return $value;
}

add_filter('site_transient_update_plugins', 'remove_plugin_updates');

// --- /ACF ---

function replaceString($string, $tag="span") {
	$result = str_replace("{", "<$tag>", $string);
	$result = str_replace("}", "</$tag>", $result);
	
	return $result;
}

function getClearImage($imageStr) {
	$image = $imageStr;
	$format = substr(strrchr($image, "."), 1);
	$image = strstr($image, $format, true); 
	
	return $image;
}


// --- REMOVE MENU ITEMS ---

add_action('admin_menu', 'remove_admin_menu');
function remove_admin_menu() {
	remove_menu_page('edit.php');
}

// --- /REMOVE MENU ITEMS ---



// --- AJAX ---

// Adding a new column to the comments list
function add_grade_to_comment_column($columns) {
    $columns['grade'] = "Grade";
    return $columns;
}
add_filter('manage_edit-comments_columns', 'add_grade_to_comment_column');

// Filling a new column with a value from the comment meta field
function grade_comment_column_content($column, $comment_ID) {
    if ($column == 'grade') {
        $grade = get_comment_meta($comment_ID, 'rating', true);
        echo $grade ? esc_html($grade) : '—';
    }
}
add_action('manage_comments_custom_column', 'grade_comment_column_content', 10, 2);

function is_member_comment_column($columns) {
    $columns['is-member'] = "Is member";
    return $columns;
}
add_filter('manage_edit-comments_columns', 'is_member_comment_column');

// Filling a new column with a value from the comment meta field
function is_member_comment_column_content($column, $comment_ID) {
    if ($column == 'is-member') {
        $grade = get_comment_meta($comment_ID, 'is-member', true);
		if($grade) {
			if($grade == "true") {
				echo esc_html("+");
			}
		} else if($grade != "true") {
			echo esc_html("-");
		}
        
    }
}
add_action('manage_comments_custom_column', 'is_member_comment_column_content', 10, 2);



// Calculating the average rating value from all comments on a post
function get_rating_average($post_id) {
    $comments = get_comments(array('post_id' => $post_id, 'status' => 'approve'));
    $sum = 0;
    $count = 0;
    foreach ($comments as $comment) {
        $rating_number = intval(get_comment_meta($comment->comment_ID, 'rating', true));
        if ($rating_number) {
            $sum += $rating_number;
            $count++;
        }
    }
    return $count > 0 ? round($sum / $count, 2) : 0;
}

// Adding a new column to the list of posts
function add_rating_column($columns) {
	$columns['rating'] = "Rating";
	return $columns;
}
add_filter('manage_posts_columns', 'add_rating_column');

// Change the content of a new column to display the average value
function rating_average_column_content($column_name, $post_id) {
    if ($column_name == 'rating') {
        echo "<b style=\"font-size: 16px;\">" . get_rating_average(pll_get_post($post_id)) . "</b>";
    }
}
add_action('manage_masters_posts_custom_column', 'rating_average_column_content', 10, 2);



function change_name($name) {
	return get_bloginfo("name");
}
 
add_filter('wp_mail_from_name','change_name');

add_action( 'wp_ajax_rating', 'popup_guide_form' );
add_action( 'wp_ajax_nopriv_rating', 'rating' );

function popup_guide_form() {
	
	$ID = $_POST["master-id"];
	$meta = ["rating" => intval($_POST["rating"])];
	if($_POST["is-member"]) {
		$meta["is-member"] = "true";
	}
	
	$commentdata = array(
		'comment_post_ID' => pll_get_post($ID),
		'comment_author'  => $_POST["name"] ? $_POST["name"] : pll__("No name"),
		'comment_content' => $_POST["message"],
		'comment_meta'    => $meta,
	);

	if(wp_insert_comment($commentdata)) {
		echo "success";
	}

	die;
}

// --- /AJAX ---


add_action( 'init', 'register_post_types' );

function register_post_types() {

	register_taxonomy( 'salons', [ 'masters' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Salons',
			'singular_name'     => 'Salon',
			'search_items'      => 'Search salon',
			'all_items'         => 'All salons',
			'view_item '        => 'View salon',
			'parent_item'       => 'Parent salon',
			'parent_item_colon' => 'Parent salon:',
			'edit_item'         => 'Edit salon',
			'update_item'       => 'Update salon',
			'add_new_item'      => 'Add new salon',
			'new_item_name'     => 'Name new salon',
			'menu_name'         => 'Salons',
			'back_to_items'     => '← Back to salons',
		],
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => null,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_tagcloud'         => true,
		// 'show_in_quick_edit'    => null,
		'hierarchical'          => true,

		'rewrite'               => true,
		'capabilities'          => array(),
		'meta_box_cb'           => null,
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'rest_base'             => null,

	] );

	register_taxonomy( 'city', [ 'masters' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Cities',
			'singular_name'     => 'City',
			'search_items'      => 'Search city',
			'all_items'         => 'All cities',
			'view_item '        => 'View city',
			'parent_item'       => 'Parent city',
			'parent_item_colon' => 'Parent city:',
			'edit_item'         => 'Edit city',
			'update_item'       => 'Update city',
			'add_new_item'      => 'Add new city',
			'new_item_name'     => 'Name new city',
			'menu_name'         => 'Cities',
			'back_to_items'     => '← Back to cities',
		],
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => null,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_tagcloud'         => true,
		// 'show_in_quick_edit'    => null,
		'hierarchical'          => true,

		'rewrite'               => true,
		'capabilities'          => array(),
		'meta_box_cb'           => null,
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'rest_base'             => null,

	] );

	register_post_type('masters', array(
		'labels'             => array(
			'name'               => 'Masters',
			'singular_name'      => 'Master',
			'add_new'            => 'Add master',
			'add_new_item'       => 'Add master',
			'edit_item'          => 'Edit master',
			'new_item'           => 'Add new',
			'view_item'          => 'View',
			'search_items'       => 'Search',
			'not_found'          => 'Not found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Masters'

		  ),
		'show_in_nav_menus'	 => true,
		'has_archive' 		 => true,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-groups', 
		'hierarchical'       => true,
		'menu_position'      => null,
		'taxonomies' 		 => ["products-category"],
		'supports' => array( 'title', 'editor', 'custom-fields')

	) );

}


//REMOVE GUTENBERG BLOCK LIBRARY CSS FROM LOADING ON FRONTEND

function remove_wp_block_library_css(){
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' ); // REMOVE THEME.JSON
}

add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );

add_action('init', function() {
	pll_register_string('no-name', 'No name');
});

// --- GraphQl ---

global $home_id;
$home_id = get_option("page_on_front");

function get_global_field($value, $lang="en") {
	global $home_id;
	return get_field($value, pll_get_post($home_id, $lang));
}

add_action( 'graphql_register_types', function() {

	register_graphql_field( 'RootQuery', 'logo', [
        'type'        => 'String',
        'resolve'     => function( $root, $args, $context, $info ) {
			$custom_logo_id = get_theme_mod('custom_logo');
			$logo = wp_get_attachment_image_src($custom_logo_id, 'full')[0];
            return $logo;
        }
    ] );

	register_graphql_field( 'RootQuery', 'salonBackground', [
        'type'        => 'String',
		'args' => [
            'salon' => [
                'type' => 'id',
                'description' => 'Salon id',
            ]
        ],
        'resolve' => function( $root, $args, $context, $info ) {
			$salon_id = $args["salon"] ?? 0;
			if($salon_id) {
				$salon = get_term($salon_id, "salons");
            	return "".getClearImage(get_field("salon_background", $salon)["url"])."webp";
			}
        }
    ] );

	register_graphql_object_type('LanguageItem', [
        'fields' => [
           'title' => [
                'type' => 'String',
            ],
			'url' => [
                'type' => 'String',
            ]
        ],
    ]);

	register_graphql_field( 'RootQuery', 'languageList', [
		'type' => [
            'list_of' => 'LanguageItem',
        ],
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {

			$translations = pll_the_languages( array( 'raw' => 1 ) );
			$current_lang = $args["lang"];
			$list = [];

			foreach ($translations as $item) {
				if(strtolower($current_lang) != $item["slug"]) {
					array_push($list, [
						"url" => $item["url"],
						"title" => ucfirst($item["slug"]),
					]);
				}
			}

            return $list;
        }
    ] );

    register_graphql_field( 'RootQuery', 'raitingFormTitle', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
			$lang = $args["lang"];
            return replaceString(get_global_field("rating_block_title", $lang), "i");
        }
    ] );

	register_graphql_object_type('MasterObject', [
        'fields' => [
            'id' => [
                'type' => 'ID',
            ],
			'avatar' => [
                'type' => 'String',
            ],
			'avatarWebp' => [
                'type' => 'String',
            ],
			'avatarAlt' => [
                'type' => 'String',
            ],
            'name' => [
                'type' => 'String',
            ]
        ],
    ]);

	register_graphql_object_type('CustomLinkObject', [
        'fields' => [
            'title' => [
                'type' => 'String',
            ],
			'url' => [
                'type' => 'String',
            ],
			'target' => [
                'type' => 'String',
            ]
        ],
    ]);

	register_graphql_field( 'RootQuery', 'mastersList', [
        'type' => [
            'list_of' => 'MasterObject',
        ],
		'args' => [
            'salon' => [
                'type' => 'id',
                'description' => 'Salon id',
			],
			'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve' => function( $root, $args, $context, $info ) {
			
			$current_salon_id = $args['salon'] ?? 0;
			$lang = $args["lang"] ?? "en";
			$arguments = [
				"posts_per_page" => -1,
				"post_type" => "masters",
			];

			if($current_salon_id) {
				$arguments["tax_query"] = [
					'relation' => 'AND',
					[
						'taxonomy' => 'salons',
						'field'    => 'id',
						'terms'    => array( $current_salon_id ),
					]
				];
			}

			$query = new WP_Query($arguments);

			$masters = [];
			if($query->have_posts()) {
				while($query->have_posts()) {
					$query->the_post();
					$ID = get_the_ID();
					$avatar = get_field("avatar", $ID);
					array_push($masters, [
						'id'      => $ID,
						'name'   => get_the_title(),
						'avatar'  => $avatar["url"],
						'avatar_webp'  => ''. getClearImage($avatar["url"]) .'webp',
						'avatar_alt'  => $avatar["alt"],
					]);
				}
				wp_reset_postdata();

				return $masters;
			} else {
				return null;
			}
        }
    ] );

	register_graphql_field( 'RootQuery', 'ratingInputNamePlaceholder', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
            return get_global_field("rating_block_input_name", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'ratingInputTextareaPlaceholder', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
            return get_global_field("rating_block_textarea_name", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'ratingSubmitButtonText', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
            return get_global_field("rating_block_submit_text", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksExtendTitle', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
			],
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
            return get_global_field("thanks_extend_title", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksExtendText', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
            return get_global_field("thanks_extend_text", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksExtendGoogleButtonText', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
            return get_global_field("thanks_extend_google_button", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksExtendGoogleFeedbackURL', [
        'type'        => 'String',
		'args' => [
            'salon' => [
                'type' => 'id',
                'description' => 'Salon id',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
			$salon_id = $args['salon'] ?? 0;
			if($salon_id) {
				$salon = get_term($salon_id, "salons");
            	return get_field("google_feedback", $salon);
			}
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksExtendLink', [
		'type' => [
            'list_of' => 'CustomLinkObject',
        ],
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
			$link = get_global_field("thanks_extend_link", $args["lang"]);
			if($link) {
				return [
					[
						"title" => $link["title"],
						"url" => $link["url"],
						"target" => $link["target"] ?? "_self"
					]
				];
			}
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksTitle', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
			return get_global_field("thanks_standard_title", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksText', [
        'type'        => 'String',
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
			return get_global_field("thanks_standard_text", $args["lang"]);
        }
    ] );

	register_graphql_field( 'RootQuery', 'thanksButton', [
		'type' => [
            'list_of' => 'CustomLinkObject',
        ],
		'args' => [
            'lang' => [
                'type' => 'String',
            ]
        ],
        'resolve'     => function( $root, $args, $context, $info ) {
			$link = get_global_field("thanks_standard_button", $args["lang"]);
			if($link) {
				return [[
					"text" => $link["title"],
					"url" => $link["url"],
					"target" => $link["target"] ?? "_self",
				]];
			}
        }
    ] );
});

// --- /GraphQl ---
