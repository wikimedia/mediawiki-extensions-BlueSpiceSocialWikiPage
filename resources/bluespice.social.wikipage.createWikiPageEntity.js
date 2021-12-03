
$( document ).bind( 'BSSocialEntityListInit', function( event, EntityList, $el ) {
	new bs.social.CreateWikiPageEntity(
		$el,
		EntityList
	);
});

