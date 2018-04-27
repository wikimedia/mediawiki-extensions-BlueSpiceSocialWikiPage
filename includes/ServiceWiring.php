<?php

use MediaWiki\MediaWikiServices;

return [

	'BSSocialWikiPageEntityFactory' => function ( MediaWikiServices $services ) {
		return new \BlueSpice\Social\WikiPage\WikiPageFactory(
			$services->getService( 'BSEntityRegistry' ),
			$services->getService( 'BSEntityConfigFactory' ),
			$services->getConfigFactory()->makeConfig( 'bsg' )
		);
	},
];
