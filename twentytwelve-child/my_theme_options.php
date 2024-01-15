<?php
if ( ! function_exists( 'add_action' )) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Setup Form
$form = tr_form()->useJson()->setGroup( $this->getName() );
?>

<h1>Настройки</h1>
<div class="typerocket-container">
    <?php
    echo $form->open();

    // About
    $svodka = function() use ($form) {
        echo $form->text('mrot')->setLabel('МРОТ');
        echo $form->text('cbrrf')->setLabel('Ставка ЦБ РФ');
    };

    $themeUI = function() use ($form) {
        echo $form->image('main_logo')->setLabel('Лого');
    };

    // API
    $api = function() use ($form) {
        $help = '<a target="blank" href="https://developers.googl..com/maps/documentation/embed/guide#api_key">Get Your Google Maps API</a>.';
        echo $form->password( 'Google Maps API Key')
            ->setHelp($help)
            ->setAttribute('autocomplete', 'new-password');
    };

    // Save
    $save = $form->submit( 'Сохранить' );

    // Layout
    tr_tabs()->setSidebar( $save )
        ->addTab( 'info', $svodka )
        ->addTab( 'ui', $themeUI )
        //->addTab( 'APIs', $api )
        ->render( 'box' );

    echo $form->close();

    ?>

</div>
