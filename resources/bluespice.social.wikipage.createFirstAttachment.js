
$( document ).bind( 'BSSocialEntityListInit', function( event, EntityList, $el ) {
	new bs.social.CreateFirstAttachmentAfterContent(
		$el,
		EntityList
	);
});

