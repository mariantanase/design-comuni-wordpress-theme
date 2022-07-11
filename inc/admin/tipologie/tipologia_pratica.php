<?php

/**
 * Definisce post type Pratica
 */
add_action( 'init', 'dci_register_post_type_pratica', 100 );
function dci_register_post_type_pratica() {

    $labels = array(
        'name'                  => _x( 'Pratica', 'Post Type General Name', 'design_comuni_italia' ),
        'singular_name'         => _x( 'Pratica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'add_new'               => _x( 'Aggiungi una Pratica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'add_new_item'               => _x( 'Aggiungi una Pratica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'featured_image' => __( 'Logo Identificativo della Pratica', 'design_comuni_italia' ),
        'edit_item'      => _x( 'Modifica la Pratica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'view_item'      => _x( 'Visualizza la Pratica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'set_featured_image' => __( 'Seleziona Immagine Pratica' ),
        'remove_featured_image' => __( 'Rimuovi Immagine Pratica' , 'design_comuni_italia' ),
        'use_featured_image' => __( 'Usa come Immagine Pratica' , 'design_comuni_italia' ),
    );
    $args = array(
        'label'                 => __( 'Pratica', 'design_comuni_italia' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'hierarchical'          => false,
        'public'                => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-text-page',
        'has_archive'           => true,
        'capability_type' => array('pratica', 'pratiche'),
        'map_meta_cap'    => true,
        'description'    => __( "Struttura delle informazioni utili a presentare un dataset", 'design_comuni_italia' ),

    );
    register_post_type( 'pratica', $args );

    remove_post_type_support( 'pratica', 'editor');
}

/**
* Aggiungo label sotto il titolo
*/
add_action( 'edit_form_after_title', 'dci_pratica_add_content_after_title' );
function dci_pratica_add_content_after_title($post) {
    if($post->post_type == "pratica")
        _e('<span><i>il <b>Titolo</b> è il <b>Titolo della Pratica</b>.</i></span><br><br><br> ', 'design_comuni_italia' );
}

/**
 * Crea i metabox del post type Pratica
 */
add_action( 'cmb2_init', 'dci_add_pratica_metaboxes' );
function dci_add_pratica_metaboxes() {
    $prefix = '_dci_pratica_';


    $cmb_stato = new_cmb2_box( array(
        'id'           => $prefix . 'box_stato',
        'title'        => __( 'Stato della Pratica *', 'design_comuni_italia' ),
        'object_types' => array( 'pratica' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb_stato->add_field( array(
        'id' => $prefix . 'stato_pratica',
        'desc' => __( 'Stato della Pratica', 'design_comuni_italia' ),
        'type'             => 'taxonomy_radio_hierarchical',
        'taxonomy'       => 'stati_pratica',
        'show_option_none' => false,
        'remove_default' => 'true',
        'attributes'    => array(
            'required'    => 'required'
        ),
    ) );

    //numero protocollo
    $cmb_protocollo = new_cmb2_box( array(
        'id'           => $prefix . 'box_protocollo',
        'title'        => __( 'Numero di protocollo *', 'design_comuni_italia' ),
        'object_types' => array( 'pratica' ),
        'context'      => 'normal',
        'priority'     => 'high',
    ) );
    $cmb_protocollo->add_field( array(
        'id'         => $prefix . 'numero_protocollo',
        'desc' => __( 'Codice univoco che identifica la pratica in esame' , 'design_comuni_italia' ),
        'type'       => 'text',
        'attributes'    => array(
            'maxlength'  => '255',
            'required'    => 'required'
        ),
    ) );

    //Descrizione
    $cmb_descrizione = new_cmb2_box( array(
        'id'           => $prefix . 'box_descrizione',
        'title'        => __( 'Descrizione', 'design_comuni_italia' ),
        'object_types' => array( 'pratica' ),
        'context'      => 'normal',
        'priority'     => 'high',
    ) );
    $cmb_descrizione->add_field( array(
        'id' => $prefix . 'descrizione',
        'name'        => __( 'Descrizione *', 'design_comuni_italia' ),
        'desc' => __( 'Descrizione della pratica' , 'design_comuni_italia' ),
        'type' => 'wysiwyg',
        'attributes'    => array(
            'required'    => 'required'
        ),
        'options' => array(
            'media_buttons' => false, // show insert/upload button(s)
            'textarea_rows' => 4, // rows="..."
            'teeny' => true, // output the minimal editor config used in Press This
        ),
    ) );
    $cmb_descrizione->add_field( array(
        'id' => $prefix . 'ufficio_riferimento',
        'name'    => __( 'Ufficio di riferimento', 'design_comuni_italia' ),
        'desc' => __( 'Ufficio a cui fa riferiemnento la pratica (es: anagrafe, riscossione tributi, pagamenti, etc) e link alla scheda ufficio di riferimento della pratica' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('unita_organizzativa'),
        'attributes' => array(
            'placeholder' =>  __( ' Seleziona le unità organizzative', 'design_comuni_italia' ),
        )
    ) );
    $cmb_descrizione->add_field( array(
        'id' => $prefix . 'servizio_pratica',
        'name'    => __( 'Servizio che origina la pratica', 'design_comuni_italia' ),
        'desc' => __( 'Servizio che genera la pratica (es: pagare qualcosa, effettuare un\'iscrizione, richiedere un documento specifico)' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('servizio'),
        'attributes' => array(
            'placeholder' =>  __( ' Seleziona i servizi', 'design_comuni_italia' ),
        )
    ) );

    //DOCUMENTI
    $cmb_documenti = new_cmb2_box( array(
        'id'           => $prefix . 'box_documenti',
        'title'        => __( 'Documenti', 'design_comuni_italia' ),
        'object_types' => array( 'pratica' ),
        'context'      => 'normal',
        'priority'     => 'high',
    ) );

    $cmb_documenti->add_field( array(
        'id' => $prefix . 'documenti_pubblici',
        'name'        => __( 'Documenti pubblici collegati', 'design_comuni_italia' ),
        'desc' => __( 'Eventuali documenti pubblici allegati alla pratica' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('documento_pubblico'),
        'attributes' => array(
            'placeholder' =>  __( ' Seleziona i documenti pubblici', 'design_comuni_italia' ),
        )
    ) );

    $cmb_documenti->add_field( array(
        'id' => $prefix . 'documenti_privati',
        'name'        => __( 'Documenti privati collegati', 'design_comuni_italia' ),
        'desc' => __( 'Eventuali documenti privati allegati alla pratica' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('documento_privato'),
        'attributes' => array(
            'placeholder' =>  __( ' Seleziona i documenti privati', 'design_comuni_italia' ),
        )
    ) );


    //argomenti
    $cmb_argomenti = new_cmb2_box( array(
        'id'           => $prefix . 'box_argomenti',
        'title'        => __( 'Argomenti ', 'design_comuni_italia' ),
        'object_types' => array( 'pratica' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );
    $cmb_argomenti->add_field( array(
        'id' => $prefix . 'argomenti',
        'type'             => 'taxonomy_multicheck_hierarchical',
        'taxonomy'       => 'argomenti',
        'show_option_none' => false,
        'remove_default' => 'true',
    ) );
}


/**
 * aggiungo js per controllo compilazione campi
 */
add_action( 'admin_print_scripts-post-new.php', 'dci_pratica_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'dci_pratica_admin_script', 11 );

function dci_pratica_admin_script() {
    global $post_type;
    if( 'pratica' == $post_type )
        wp_enqueue_script( 'pratica-admin-script', get_stylesheet_directory_uri() . '/inc/admin-js/pratica.js' );
}