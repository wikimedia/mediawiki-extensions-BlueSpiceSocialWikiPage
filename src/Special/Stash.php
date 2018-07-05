<?php

namespace BlueSpice\Social\WikiPage\Special;

use BlueSpice\Context;
use BlueSpice\Services;
use BlueSpice\Renderer\Params;
use BlueSpice\Social\WikiPage\EntityListContext\SpecialStash;

class Stash extends \BsSpecialPage {

	public function __construct() {
		parent::__construct( 'WikiPageStash', 'read' );
	}

	public function execute( $par ) {
		$this->checkPermissions();

		$this->getOutput()->setPageTitle(
			wfMessage( 'bs-socialwikipage-special-stash-heading' )->plain()
		);

		$title = $entiy = null;
		if( !empty( $par ) ) {
			$title = \Title::newFromText( $par );
			$factory =  Services::getInstance()->getService(
				'BSSocialWikiPageEntityFactory'
			);
			$entiy = $factory->newFromTitle( $title );
		}

		$context = new SpecialStash(
			new Context(
				$this->getContext(),
				$this->getConfig()
			),
			$this->getConfig(),
			$this->getContext()->getUser(),
			$entiy,
			$title
		);
		$renderer = Services::getInstance()->getBSRendererFactory()->get(
			'entitylist',
			new Params( [ 'context' => $context ] )
		);

		$this->getOutput()->addHTML( $renderer->render() );
	}
}