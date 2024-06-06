<?php
// Obtener las subcategorías
$subcategories = get_categories( array(
    'parent' => get_queried_object_id(), // Obtener el ID de la categoría actual
) );

// Verificar si hay productos directamente en la categoría principal
$main_category_args = array(
    'posts_per_page' => -1,
    'category__in' => get_queried_object_id(), // Obtener el ID de la categoría principal
    'category__not_in' => wp_list_pluck( $subcategories, 'term_id' ), // Excluir las subcategorías
);
$main_category_posts = get_posts( $main_category_args );

// Si hay productos directamente en la categoría principal, mostrar la grilla
if ( $main_category_posts ) {
    echo '<div class="grid-container">';
    $counter_main = 0; // Contador para controlar el número de columnas
    $post_count_main = count( $main_category_posts ); // Número total de productos en la categoría principal
    foreach ( $main_category_posts as $post ) {
        setup_postdata( $post );
        // Obtener la imagen destacada
        $thumbnail_main = get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); // Cambia 'thumbnail' al tamaño que desees

        // Obtener el título del producto con el enlace
        $title_main = '<p><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></p>';

        // Mostrar la imagen destacada, el título y el enlace
        echo '<div class="grid-item">';
        $thumbnail_main = get_the_post_thumbnail( get_the_ID(), 'large' );
        echo '<div class="fusion-image-element">' . $thumbnail_main . '</div>'; // Aquí se muestra la imagen destacada
        echo '<div class="contenido">' . $title_main . '</div>'; // Aquí se muestra el título con el enlace
        echo '</div>';

        // Incrementar el contador
        $counter_main++;
        // Cerrar fila y abrir una nueva si el contador es divisible por 4 (4 columnas) o si es el último producto y no se alcanza a completar las 4 columnas
        if ( $counter_main % 4 == 0 || $counter_main == $post_count_main ) {
            echo '<div class="clearfix"></div>'; // Limpiar el float
        }
    }
    echo '</div>'; // Cerrar contenedor de grilla de la categoría principal
    wp_reset_postdata();
}

// Ahora, procesar las subcategorías
foreach ( $subcategories as $subcategory ) {
    $subcategory_id = $subcategory->term_id; // ID de la subcategoría
    $subcategory_name = $subcategory->name; // Nombre de la subcategoría
    $subcategory_slug = $subcategory->slug; // Slug de la subcategoría
    
    $args = array(
        'posts_per_page' => -1,
        'cat' => $subcategory->term_id, // Obtener el ID de la subcategoría
    );
    $subcategory_posts = get_posts( $args );

    if ( $subcategory_posts ) {
        echo '<p class="subcategory-heading">' . esc_html( $subcategory->name ) . '</p>';
        echo '<div class="grid-container">';
        $counter = 0; // Contador para controlar el número de columnas
        $post_count = count( $subcategory_posts ); // Número total de productos
        foreach ( $subcategory_posts as $post ) {
            setup_postdata( $post );
            // Obtener la imagen destacada
            $thumbnail = get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); // Cambia 'thumbnail' al tamaño que desees

            // Obtener el título del producto con el enlace
            $title = '<p><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></p>';

            // Mostrar la imagen destacada, el título y el enlace
            echo '<div class="grid-item">';
            $thumbnail = get_the_post_thumbnail( get_the_ID(), 'large' );
            echo '<div class="fusion-image-element">' . $thumbnail . '</div>'; // Aquí se muestra la imagen destacada
            echo '<div class="contenido">' . $title . '</div>'; // Aquí se muestra el título con el enlace
            echo '</div>';

            // Incrementar el contador
            $counter++;
            // Cerrar fila y abrir una nueva si el contador es divisible por 4 (4 columnas) o si es el último producto y no se alcanza a completar las 4 columnas
            if ( $counter % 4 == 0 || $counter == $post_count ) {
                echo '<div class="clearfix"></div>'; // Limpiar el float
            }
        }
        echo '</div>'; // Cerrar contenedor de grilla
        wp_reset_postdata();
    }
}
