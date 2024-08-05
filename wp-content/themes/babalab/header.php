<!DOCTYPE html>
<html lang="<?php bloginfo("language") ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0158C3">
	<?php wp_head(); ?>
</head>
<?php 
    $home_id = get_option('page_on_front');
    $translations = pll_the_languages( array( 'raw' => 1 ) );
    $current_lang = pll_current_language();
    $background = get_field("main_background", $home_id);
    $custom_logo_id = get_theme_mod('custom_logo');
	$logo = wp_get_attachment_image_src($custom_logo_id, 'full')[0];
?>
<body>
    <div class="wrapper">
        <div class="wrapper-background" style="background-image: url(<?= getClearImage($background["url"]); ?>webp);" aria-hidden="true"></div>
        <header class="header">
            <div class="header__logo">
                <?php if($logo) { ?>
                <div class="header__logo_link">
                    <img src="<?= $logo; ?>" alt="" width="88" height="32" loading="lazy" class="header__logo_img">
                </div>
                <?php } ?>
            </div>
            <?php if($translations) { ?>
            <nav class="header__lang">
                <button class="header__lang-target" type="button" aria-label="<?= ucfirst($current_lang); ?>">
                    <span><?= ucfirst($current_lang); ?></span>
                    <svg width="14" height="8">
                        <use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#drop-down-arrow"></use>
                    </svg>
                </button>
                <ul class="header__lang-list">
                    <?php foreach ($translations as $item) { 
                        $name = $item["slug"];
                        $url = $item["url"];

                        if($name != $current_lang) {
                    ?>
                    <li class="header__lang-item">
                        <a href="<?= $url; ?>" class="header__lang-link">
                            <?= ucfirst($name); ?>
                        </a>
                    </li>
                    <?php } } ?>
                </ul>
            </nav>
            <?php } ?>
        </header>