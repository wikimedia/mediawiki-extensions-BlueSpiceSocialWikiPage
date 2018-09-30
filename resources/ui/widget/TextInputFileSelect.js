bs = bs || {};
bs.ui = bs.ui || {};
bs.ui.widget = bs.ui.widget || {};

bs.ui.widget.TextInputFileSelect = function ( config ) {
	OO.ui.MultilineTextInputWidget.call( this, config );
	var me = this;
	me.field = config.field || false;
};
OO.initClass( bs.ui.widget.TextInputFileSelect );
OO.inheritClass( bs.ui.widget.TextInputFileSelect, OO.ui.MultilineTextInputWidget );

bs.ui.widget.TextInputFileSelect.prototype.init = function() {
	var me = this;
	mw.loader.using( [ 'ext.bluespice.extjs' ] ).done( function () {
		Ext.Loader.setPath(
			'BS.SocialWikiPage',
			bs.em.paths.get( 'BlueSpiceSocialWikiPage' ) + '/resources/BS.SocialWikiPage'
		);
		var id = me.field.$element.attr( 'id' ) || 'bs-textinput-insertfile';
		id += '-' + Ext.id();

		me.field.$element.append(
			'<div id="' + id + '" style = "min-height: 40px; text-align: center; cursor: pointer; margin: 5px 0px; border: 3px dashed; padding: 2% 5px; overflow: hidden; white-space: normal;">'
			+ '</div>'
		);
		$(document).on( 'click', '#' + id, function( e ){
			e.preventDefault();
			Ext.require( 'BS.SocialWikiPage.InsertFile.Dialog', function() {
				var diag = new BS.SocialWikiPage.InsertFile.Dialog();
				diag.on( 'ok', function( btn, data ) {
					console.log( diag.getData() );
					return;
					var formattedNamespaces = mw.config.get('wgFormattedNamespaces');
					if( data.nsText == 'media' ) {
						data.nsText = formattedNamespaces[bs.ns.NS_MEDIA];
					} else {
						data.nsText = formattedNamespaces[bs.ns.NS_FILE];
					}
					data.caption = data.displayText;
					delete( data.src );
					var wikiLink = new bs.wikiText.Link( data );
					me.field.setValue(
						me.field.getValue() + "\n" + wikiLink
					);
				});
				diag.show( me );
			});

			return false;
		});

		$( '#' + id ).html(
			'file'
		);
	} );
};
