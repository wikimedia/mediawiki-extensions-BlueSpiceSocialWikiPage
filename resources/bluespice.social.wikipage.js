/**
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BluespiceSocial
 * @subpackage BSSocialEntityListAddArticles
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 */

$( document ).bind( 'BSSocialEntitySpawnerInit', function( event, EntitySpawner, $el ) {
	var ns = mw.config.get( 'wgNamespaceNumber', 0 );
	if( ns !== 0 && ns % 2 === 1 ) {
		return;
	}
	var $content = EntitySpawner.$el.children(
		'.bs-social-entityspawner-actions'
	).children(
		'.bs-social-entityspawner-actions-content'
	);

	$content.append(
		"<a href='#' class='bs-social-entityspawner-article'>"
		+ mw.message( 'bs-socialwikipage-label' ).plain()
		+ "</a>"
	);
	$content.find( 'a.bs-social-entityspawner-article' ).click(function(e) {
		var AddArticleDialog = new bs.social.AddArticle( {
			size: 'medium'
		});

		var windowManager = new OO.ui.WindowManager();
		$( 'body' ).append( windowManager.$element );

		windowManager.addWindows( [ AddArticleDialog ] );


		windowManager.openWindow( AddArticleDialog ).then( function ( opened ) {
			return opened.then( function ( closing ) {
				return closing.then( function ( data ) {
					return $.Deferred().resolve(
						!!( data && data.action === 'accept' )
					);
				} );
			});
		}).done( function ( result ) {
			if( !result ) {
				return;
			}
			if( !AddArticleDialog.addArticle.getValue() ) {
				return;
			}
			if( AddArticleDialog.addArticle.getValue() === "" ) {
				return;
			}
			window.location = mw.util.getUrl(
				AddArticleDialog.addArticle.getValue(),
				{ action: 'edit' }
			);
		});

		e.preventDefault();
		return false;
	});
});

$( document ).bind( 'BSSocialEntityListMenuInit', function( event, EntityListMenu ) {
	return; //currently not in use, but fully functional! //TODO: May add an
	//option and let the user decide
	EntityListMenu.makeAddArticleButton = function() {
		var tpl = mw.template.get(
			'ext.bluespice.templates',
			'BSSocialActivityStream.EntityListMenuButton.mustache'
		);
		var $button = $(tpl.render( {
			classes: 'bs-entitylist-menu-item-addarticles'
		}));
		return $button;
	};
	EntityListMenu.$addArticleButton = EntityListMenu.makeAddArticleButton();
	EntityListMenu.getEl().append( EntityListMenu.$addArticleButton );
	EntityListMenu.$addArticleButton.on( 'click', function() {

		var AddArticleDialog = new bs.social.AddArticle( {
			size: 'medium'
		});


		var windowManager = new OO.ui.WindowManager();
		$( 'body' ).append( windowManager.$element );

		windowManager.addWindows( [ AddArticleDialog ] );


		windowManager.openWindow( AddArticleDialog ).then( function ( opened ) {
			return opened.then( function ( closing ) {
				return closing.then( function ( data ) {
					return $.Deferred().resolve(
						!!( data && data.action === 'accept' )
					);
				} );
			});
		}).done( function ( result ) {
			if( !result ) {
				return;
			}
			if( !AddArticleDialog.addArticle.getValue() ) {
				return;
			}
			if( AddArticleDialog.addArticle.getValue() === "" ) {
				return;
			}
			window.location = mw.util.getUrl(
				AddArticleDialog.addArticle.getValue(),
				{ action: 'edit' }
			);
		});

		return false;
	});
});