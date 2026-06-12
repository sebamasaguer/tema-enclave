<?php
/**
 * Custom post types and taxonomies.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'eu_register_post_types');
function eu_register_post_types() {
    register_post_type('eu_project', array(
        'labels' => array(
            'name'               => __('Proyectos', 'enclave-urbano'),
            'singular_name'      => __('Proyecto', 'enclave-urbano'),
            'add_new'            => __('Agregar proyecto', 'enclave-urbano'),
            'add_new_item'       => __('Agregar nuevo proyecto', 'enclave-urbano'),
            'edit_item'          => __('Editar proyecto', 'enclave-urbano'),
            'new_item'           => __('Nuevo proyecto', 'enclave-urbano'),
            'view_item'          => __('Ver proyecto', 'enclave-urbano'),
            'search_items'       => __('Buscar proyectos', 'enclave-urbano'),
            'not_found'          => __('No se encontraron proyectos', 'enclave-urbano'),
            'menu_name'          => __('Proyectos', 'enclave-urbano'),
        ),
        'public'       => true,
        'has_archive'  => 'proyectos',
        'rewrite'      => array('slug' => 'proyectos', 'with_front' => false),
        'menu_icon'    => 'dashicons-location-alt',
        'supports'     => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    ));

    register_post_type('eu_value', array(
        'labels' => array(
            'name'          => __('Valores', 'enclave-urbano'),
            'singular_name' => __('Valor', 'enclave-urbano'),
            'add_new_item'  => __('Agregar valor', 'enclave-urbano'),
            'edit_item'     => __('Editar valor', 'enclave-urbano'),
            'menu_name'     => __('Valores', 'enclave-urbano'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-awards',
        'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    ));

    register_post_type('eu_team', array(
        'labels' => array(
            'name'          => __('Equipo', 'enclave-urbano'),
            'singular_name' => __('Miembro del equipo', 'enclave-urbano'),
            'add_new_item'  => __('Agregar miembro', 'enclave-urbano'),
            'edit_item'     => __('Editar miembro', 'enclave-urbano'),
            'menu_name'     => __('Equipo', 'enclave-urbano'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    ));

    register_post_type('eu_alliance', array(
        'labels' => array(
            'name'          => __('Alianzas', 'enclave-urbano'),
            'singular_name' => __('Alianza', 'enclave-urbano'),
            'add_new_item'  => __('Agregar alianza', 'enclave-urbano'),
            'edit_item'     => __('Editar alianza', 'enclave-urbano'),
            'menu_name'     => __('Alianzas', 'enclave-urbano'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-networking',
        'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    ));

    register_post_type('eu_inquiry', array(
        'labels' => array(
            'name'          => __('Consultas', 'enclave-urbano'),
            'singular_name' => __('Consulta', 'enclave-urbano'),
            'add_new_item'  => __('Agregar consulta', 'enclave-urbano'),
            'edit_item'     => __('Ver consulta', 'enclave-urbano'),
            'menu_name'     => __('Consultas', 'enclave-urbano'),
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt2',
        'supports'            => array('title', 'editor'),
        'capability_type'     => 'post',
        'exclude_from_search' => true,
        'show_in_rest'        => false,
    ));
}

add_action('init', 'eu_register_taxonomies');
function eu_register_taxonomies() {
    register_taxonomy('eu_alliance_type', array('eu_alliance'), array(
        'labels' => array(
            'name'          => __('Tipos de alianza', 'enclave-urbano'),
            'singular_name' => __('Tipo de alianza', 'enclave-urbano'),
            'add_new_item'  => __('Agregar tipo', 'enclave-urbano'),
            'edit_item'     => __('Editar tipo', 'enclave-urbano'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
}
