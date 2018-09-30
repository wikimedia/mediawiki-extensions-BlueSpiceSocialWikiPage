bs = bs || {};
bs.ui = bs.ui || {};
bs.ui.widget = bs.ui.widget || {};

bs.ui.widget.TextInputAttachments = function ( config ) {
	OO.ui.InputWidget.call( this, config );
	var me = this;
	me.attachments = config.attachments || false;
	me.api = new mw.Api();
};
OO.initClass( bs.ui.widget.TextInputAttachments );
OO.inheritClass( bs.ui.widget.TextInputAttachments, OO.ui.InputWidget );

bs.ui.widget.TextInputAttachments.prototype.init = function() {
	var me = this;
	me.$element = $( '<div></div>' );
	for( var i in me.attachments.images ) {
		var id = 'attachmentEditor-' + bs.social.generateUniqueId();
		var $attachment = me.makeAttachmentEditor( me.attachments.images[i], id );
		me.$element.append( $attachment );
		me.renderAttachment( me.attachments.images[i], id );
	}
};

bs.ui.widget.TextInputAttachments.prototype.makeAttachmentEditor = function( attachment, id ) {
	
	var $attachment = $( '<div><img id="' + id + '" src=""</img></div>' );
	return $attachment;
};

bs.ui.widget.TextInputAttachments.prototype.renderAttachment = function( attachment, id ) {
	var me = this;
	this.api.get({
		action:'query',
		format: 'json',
		titles: 'File:' + attachment,
		prop: 'imageinfo',
		iiprop: 'url',
		iiurlwidth: 200
	} ).done( function( data ) {
		me.$element.find( '#' + id ).first().attr(
			"src",
			data.query.pages[Object.keys(data.query.pages)[0]].imageinfo[0].thumburl
		);
	} );
};

bs.ui.widget.TextInputAttachments.prototype.getValue = function() {
	return null;
};
