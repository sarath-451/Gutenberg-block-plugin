<?php

// Create id attribute allowing for custom "anchor" value.
$id = 'header_block-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'header_block';
if ( ! empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
}

$header_title = get_field( 'header_title' ) ?: 'Your header title...';
$header_text = get_field( 'header_text' ) ?: 'Your header text';

?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
<div class="container">
        <h1 class="tes-header-title"><?php echo $header_title; ?></h1>
        <p class="tes-header-text"><?php echo $header_text; ?></p>
</div>
</div>