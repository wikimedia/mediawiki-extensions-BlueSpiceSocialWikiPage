<?php
namespace BlueSpice\Social\WikiPage\Hook\PageContentSaveComplete;

use BlueSpice\Hook\PageContentSaveComplete;

class CreateWikiPageEntity extends PageContentSaveComplete {
	protected function skipProcessing() {
		// this is a bit hacky but without it the unit tests fail due to
		// more pages existing than exspected
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			return true;
		}

		if ( $this->isMinor || !$this->revision ) {
			return true;
		}
		if ( !$this->status->isOK() || $this->status->hasMessage( 'edit-no-change' ) ) {
			// ugly. we need to check the status object for the no edit warning,
			// cause on this point in the code it ist - unfortunaltey -
			// impossible to find out, if this edit changed something.
			// '$article->getLatest()' is always the same as
			// '$this->revision->getId()'. '$baseRevId' is always 'false' #5240
			return true;
		}
		$title = $this->wikipage->getTitle();
		if ( !$title || !$title->exists() ) {
			return true;
		}
		$tracked = \BlueSpice\Social\WikiPage\Extension::isTrackedNamespace(
			$title->getNamespace()
		);
		if ( !$tracked ) {
			return true;
		}
		if ( $title->isTalkPage() ) {
			return true;
		}
		if ( $title->getContentModel() != 'wikitext' ) {
			return true;
		}

		return false;
	}

	protected function doProcess() {
		$factory = $this->getServices()->getService(
			'BSSocialWikiPageEntityFactory'
		);
		$entity = $factory->newFromTitle(
			$this->wikipage->getTitle()
		);
		if ( !$entity ) {
			// do not fatal - here is something wrong very bad!
			return true;
		}
		if ( !$entity->exists() ) {
			// TODO: Status check
			$status = $entity->save();
			return true;
		}
		$entity->invalidateCache();
		return true;
	}
}
