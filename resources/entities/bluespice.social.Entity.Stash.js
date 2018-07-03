
bs.social.EntityStash = function( $el, type, data ) {
	bs.social.EntityText.call( this, $el, type, data );
	var me = this;
};
OO.inheritClass( bs.social.EntityStash, bs.social.EntityText );

bs.social.EntityStash.prototype.reset = function( data ) {
	return bs.social.EntityStash.super.prototype.reset.apply( this, [data] );
};

bs.social.EntityStash.prototype.makeEditor = function() {
	if( this.editor ) {
		return this.editor;
	}
	this.editor = new bs.social.EntityEditorStash(
		this.getEditorConfig(),
		this
	);
	var me = this;
	this.editor.on( 'submit', function( editor, data ) {
		me.save( data );
		return false;
	});
	this.editor.on( 'cancel', function( editor, data ) {
		me.removeEditMode( data );
		return false;
	});
	return this.editor;
};

bs.social.EntityStash.static.name = "\\BlueSpice\\Social\\WikiPage\\Entity\\Stash";
bs.social.factory.register( bs.social.EntityStash );