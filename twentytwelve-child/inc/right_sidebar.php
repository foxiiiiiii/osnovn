<?php

use AmbExpress\ViewModels\SectionsViewModel;
use Timber\Timber;

$context             = [];
$args                = [
	'taxonomy'     => 'section',
	'numberposts'  => - 1,
	'parent'       => 0,
	'hierarchical' => true,
];
//$context['sections'] = Timber::get_terms( $args );

$vm = new SectionsViewModel();
$vm->LoadSections();
$context['hsections'] = $vm->Sections;

$context['banner4'] = get_option('banner4', false);
Timber::render( 'sidebars/right.twig', $context );