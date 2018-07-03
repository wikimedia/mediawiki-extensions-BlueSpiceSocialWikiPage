bs = bs || {};
bs.ui = bs.ui || {};
bs.ui.widget = bs.ui.widget || {};

bs.ui.widget.TextInputMultiUpload = function ( config ) {
	OO.ui.MultilineTextInputWidget.call( this, config );
	var me = this;
	me.field = config.field || false;
};
OO.initClass( bs.ui.widget.TextInputMultiUpload );
OO.inheritClass( bs.ui.widget.TextInputMultiUpload, OO.ui.MultilineTextInputWidget );

bs.ui.widget.TextInputMultiUpload.prototype.init = function() {
	var me = this;
	mw.loader.using( [ 'ext.bluespice.upload' ] ).done( function () {
		var id = me.field.$element.attr( 'id' ) || 'bs-textinput-multiupload';
		id += '-' + Ext.id();

		me.field.$element.append(
			'<div id="' + id + '" style = "min-height: 40px; text-align: center; cursor: pointer; margin: 5px 0px; border: 3px dashed; padding: 2% 5px; overflow: hidden; white-space: normal;">'
			+ '</div>'
		);
		function _showDialog( upldr, files ) {
			upldr.disableBrowse( true );

			Ext.require( 'BS.dialog.MultiUpload', function () {
				var mud = new BS.dialog.MultiUpload( {
					uploader: upldr,
					files: files
				} );
				mud.on( 'uploadcomplete', me.onUploadComplete, me ),
				mud.show();
			} );
		};

		var uploader = bs.upload.makeUploader( {
			drop_element: id,
			browse_button: id,
			dragdrop: true
		} );
		uploader.bind( 'FilesAdded', _showDialog );

		$( '#' + id ).html(
			mw.message( 'bs-uploader-drop-or-click' ).text()
		);
	} );

	bs.ui.widget.TextInputMultiUpload.prototype.onUploadComplete = function( uploader, data ) {
		for( var i = 0; i < data.length; i++ ) {
			//TODO: check status somehow!
			this.field.setValue(
				this.field.getValue() + "\n"
				+ "[[File:" + data[i].uploadApiMeta.filename + "]]"
			);
		}
	};
};
