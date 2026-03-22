/**
 * navigation.js – Script pentru meniu mobil responsiv
 */
( function() {
    'use strict';

    document.addEventListener( 'DOMContentLoaded', function() {
        var toggle = document.querySelector( '.menu-toggle' );
        var nav    = document.querySelector( '.main-navigation' );

        if ( ! toggle || ! nav ) return;

        toggle.addEventListener( 'click', function() {
            var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
            toggle.setAttribute( 'aria-expanded', String( ! expanded ) );
            nav.classList.toggle( 'toggled' );
        } );

        // Închide meniul la click în afara lui
        document.addEventListener( 'click', function( event ) {
            if ( nav.classList.contains( 'toggled' ) &&
                 ! nav.contains( event.target ) &&
                 ! toggle.contains( event.target ) ) {
                nav.classList.remove( 'toggled' );
                toggle.setAttribute( 'aria-expanded', 'false' );
            }
        } );
    } );
} )();
