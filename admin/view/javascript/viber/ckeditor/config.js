/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */


CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.extraPlugins = 'wordcount,notification,typograf,youtube,fontawesome';

   config.youtube_responsive = true;
   config.contentsCss = 'view/javascript/font-awesome/css/font-awesome.min.css';
   config.allowedContent = true;
};