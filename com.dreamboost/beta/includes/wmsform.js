/*	BME WMS
	Page: Form JS Functions
	Path/File: /includes/wmsform.js
	Version: 1.1
	Build: 1100
	Date: 11-14-2006
*/

if( document.addEventListener ) document.addEventListener( 'DOMContentLoaded', wmsform, false );

function wmsform(){
  // Hide forms
  $( 'form.wmsform' ).hide().end();
  
  // Processing
  $( 'form.wmsform' ).find( 'li/label' ).not( '.nowms' ).each( function( i ){
    var labelContent = this.innerHTML;
    var labelWidth = document.defaultView.getComputedStyle( this, '' ).getPropertyValue( 'width' );
    var labelSpan = document.createElement( 'span' );
        labelSpan.style.display = 'block';
        labelSpan.style.width = labelWidth;
        labelSpan.innerHTML = labelContent;
    this.style.display = '-moz-inline-box';
    this.innerHTML = null;
    this.appendChild( labelSpan );
  } ).end();
  
  // Show forms
  $( 'form.wmsform' ).show().end();
}