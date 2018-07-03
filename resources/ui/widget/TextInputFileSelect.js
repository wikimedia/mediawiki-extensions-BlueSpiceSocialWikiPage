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
	mw.loader.using( [ 'ext.bluespice.insertFile' ] ).done( function () {
		var id = me.field.$element.attr( 'id' ) || 'bs-textinput-insertfile';
		id += '-' + Ext.id();

		me.field.$element.append(
			'<div id="' + id + '" style = "min-height: 40px; text-align: center; cursor: pointer; margin: 5px 0px; border: 3px dashed; padding: 2% 5px; overflow: hidden; white-space: normal;">'
			+ '</div>'
		);
		$(document).on( 'click', '#' + id, function( e ){
			e.preventDefault();
			Ext.require('BS.BlueSpiceInsertFile.FileDialog', function(){
				BS.BlueSpiceInsertFile.FileDialog.clearListeners();
				//BS.BlueSpiceInsertFile.FileDialog.on( 'cancel', bs.util.selection.reset );
				BS.BlueSpiceInsertFile.FileDialog.on( 'ok', function( dialog, data ) {
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
					BS.BlueSpiceInsertFile.FileDialog.setData({});
				});

				BS.BlueSpiceInsertFile.FileDialog.show( me );
				BS.BlueSpiceInsertFile.FileDialog.setData( {} );
			});

			return false;
		});

		$( '#' + id ).html(
			'file'
		);
	} );
};
