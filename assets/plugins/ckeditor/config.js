/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

/*CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 //config.language = 'fr';
	 //config.uiColor = '#AADC6E';
	  config.extraPlugins = 'imagebrowser';
};*/
CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here. For example:


         CKEDITOR.config.resize_enabled = true;
        
         CKEDITOR.config.toolbar_Full =
[
    [,'-','Save','NewPage','Preview','-','Templates'],
    
   
//  ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'], // here i disable the form tools
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   
    '/',
    ['Styles','Format','Font','FontSize'],
  
];
};