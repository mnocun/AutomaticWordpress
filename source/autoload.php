<?php

defined( 'ABS' ) or die( "This file cannot be used directly" );

ob_start();
$include_classes = array_diff( scandir( __DIR__.'/class/' ), [ '.', '..' ] );
foreach( $include_classes as $include_class )
    if( !include( __DIR__.'/class/'.$include_class ) )
        exit( "Error! Cannot include $include_class" );
ob_clean();