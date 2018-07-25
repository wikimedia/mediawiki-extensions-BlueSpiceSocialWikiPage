<?php


namespace BlueSpice\Social\WikiPage\Entity;

use BlueSpice\Social\Entity\Text;
use BlueSpice\Services;

/**
 * Stash class for BSSocial extension
 * @package BlueSpiceSocial
 * @subpackage BSSocialMicroBlog
 */
class Stash extends Text {
	const TYPE = 'stash';

	const ATTR_WIKI_PAGE_ID = 'wikipageid';

	/**
	 * Gets the attributes formated for the api
	 * @return object
	 */
	public function getFullData( $a = [] ) {
		return parent::getFullData( array_merge(
			$a,
			[
				static::ATTR_WIKI_PAGE_ID => $this->get(
					static::ATTR_WIKI_PAGE_ID,
					0
				),
			]
		));
	}

	public function setValuesByObject( \stdClass $o ) {
		if( !empty( $o->{static::ATTR_WIKI_PAGE_ID} ) ) {
			$this->set(
				static::ATTR_WIKI_PAGE_ID,
				$o->{static::ATTR_WIKI_PAGE_ID}
			);
		}
		parent::setValuesByObject( $o );
	}

	public function getHeader( $msg = null ) {
		$msg = parent::getHeader( $msg );
		return $msg->params([
			$this->getRelatedTitle()->getText(),
			$this->getRelatedTitle()->getNamespace(),
			\BsNamespaceHelper::getNamespaceName(
				$this->getRelatedTitle()->getNamespace()
			),
			$this->getRelatedTitle()->getFullText(),
		]);
	}

	public function getRelatedTitle() {
		if( $this->get( static::ATTR_WIKI_PAGE_ID, 0 ) < 1 ) {
			return parent::getRelatedTitle();
		}
		$title = \Title::newFromID(
			$this->get( static::ATTR_WIKI_PAGE_ID, 0 )
		);
		return $title instanceof \Title ? $title : parent::getRelatedTitle();
	}

	public function save( \User $user = null, $options = [] ) {
		$title = \Title::newFromID(
			$this->get( static::ATTR_WIKI_PAGE_ID, 0 )
		);
		if( !$title|| !$title->exists() ) {
			return \Status::newFatal( wfMessage(
				'bs-socialwikipage-entity-fatalstatus-save-invalidtitle'
			));
		}
		$status = \Status::newGood();
		try {
			$factory = Services::getInstance()->getService(
				'BSSocialWikiPageEntityFactory'
			);
			$entity = $factory->newFromTitle( $title );
			if( !$entity->exists() ) {
				$status = $entity->save( $user );
			}
		} catch( \Exception $e ) {
			return \Status::newFatal( $e->getMessage() );
		}
		if( !$status->isOK() ) {
			return $status;
		}
		return parent::save( $user, $options );
	}

	public function invalidateCache() {
		parent::invalidateCache();
		$title = \Title::newFromID(
			$this->get( static::ATTR_WIKI_PAGE_ID, 0 )
		);
		if( !$title|| !$title->exists() ) {
			return;
		}
		$factory = Services::getInstance()->getService(
			'BSSocialWikiPageEntityFactory'
		);
		$entity = $factory->newFromTitle( $title );
		if( $entity && $entity->exists() ) {
			$entity->invalidateCache();
		}
	}

}