bs.social.AddArticle = function( config ) {
	bs.social.AddArticle.super.call( this, config );
};

OO.inheritClass( bs.social.AddArticle, OO.ui.MessageDialog ); 

bs.social.AddArticle.static.name = 'AddArticle';
bs.social.AddArticle.static.title = mw.message(
	'bs-socialwikipage-dialog-label'
).plain();

bs.social.AddArticle.prototype.initialize = function () {
	bs.social.AddArticle.super.prototype.initialize.call( this );
	var ns = mw.config.get( 'wgNamespaceNumber', 0 );

	this.addArticleLabel = new OO.ui.LabelWidget( {
		label: mw.message( 'bs-socialwikipage-dialog-label' ).plain()
	});
	this.addArticle = new OO.ui.TextInputWidget({
		classes: [ 'oo-ui-messageDialog-message' ],
		value: ns > 0 ? bs.util.getNamespaceText( ns ) + ':' : ''
	});
	this.text.$element.append(
		//'<br>',
		//this.addArticleLabel.$element,
		'<br>',
		this.addArticle.$element
	);
};