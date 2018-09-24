bs.social.CreateWikiPageEntity = function( $el ) {

	bs.social.El.call( this, $el );
	var me = this;
	me.BUTTON_SECTION = 'bs-socialwikipage-wikipage-create';
	me.data = {};
	me.makeUiID();

	me.$button = me.getEl().find( '.' + me.BUTTON_SECTION + ' a' ).first();
	if( !me.$button || me.$button.length < 1 ) {
		return;
	}

	me.$button.on( 'click', function( e ) {
		me.createDiscussion( $( this ).parent().attr( 'value' ) );
		e.stopPropagation();
		return false;
	});
	$(document).trigger( 'BSSocialCreateWikiPageEntityInit', [ me, $el ] );

};
OO.initClass( bs.social.CreateWikiPageEntity );
OO.inheritClass( bs.social.CreateWikiPageEntity, bs.social.El );

bs.social.CreateWikiPageEntity.prototype.createDiscussion = function( id ) {
	var dfd = $.Deferred();
	var taskData = {
		'wikipageid': id,
		'type': 'wikipage'
	};

	this.showLoadMask();
	var me = this;
	bs.api.tasks.execSilent( 'social', 'editEntity', taskData )
	.done( function( response ) {
		//ignore errors for now
		//me.replaceEL( response.payload.view );
		if( !response.success ) {
			if( response.message && response.message !== '' ) {
				OO.ui.alert( response.message );
			}
			me.hideLoadMask();
			dfd.resolve( me );
			return;
		}
		me.reloadPage();
		dfd.resolve( me );
	});

	return dfd;
};

bs.social.CreateWikiPageEntity.prototype.reloadPage = function() {
	window.location = mw.util.getUrl(
		mw.config.get( 'wgPageName' )
	);
};
