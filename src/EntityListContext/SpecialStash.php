<?php

namespace BlueSpice\Social\WikiPage\EntityListContext;

use BlueSpice\Data\Filter\ListValue;
use BlueSpice\Data\Filter\Numeric;
use BlueSpice\Social\WikiPage\Entity\WikiPage;
use BlueSpice\Social\WikiPage\Entity\Stash;
use BlueSpice\Services;

class SpecialStash extends \BlueSpice\Social\EntityListContext {

	/**
	 *
	 * @var \Title
	 */
	protected $title = null;

	/**
	 *
	 * @param \IContextSource $context
	 * @param \Config $config
	 */
	public function __construct( \IContextSource $context, \Config $config, \User $user = null, WikiPage $entity = null, \Title $title = null ) {
		parent::__construct( $context, $config, $user, $entity );
		if( $title ) {
			$this->title = $title;
		}
	}

	public function getTitle() {
		return $this->title ? $this->title : $this->context->getTitle();
	}

	public function getLimit() {
		return 15;
	}

	public function getSortProperty() {
		return Stash::ATTR_TIMESTAMP_TOUCHED;
	}

	public function useEndlessScroll() {
		return true;
	}

	public function useMoreScroll() {
		return false;
	}

	public function getLockedFilterNames() {
		return array_merge(
			parent::getLockedFilterNames(),
			[ Stash::ATTR_TYPE ]
		);
	}

	public function getOutputTypes() {
		return array_merge(
			parent::getOutputTypes(),
			[ Stash::TYPE => 'Default']
		);
	}

	protected function getWikiPageIDFilter() {
		return (object)[
			Numeric::KEY_PROPERTY => Stash::ATTR_WIKI_PAGE_ID,
			Numeric::KEY_VALUE => $this->getTitle()->getArticleID(),
			Numeric::KEY_COMPARISON => Numeric::COMPARISON_EQUALS,
			Numeric::KEY_TYPE => 'numeric'
		];
	}

	/**
	 *
	 * @return \stdClass[]
	 */
	protected function getTypeFilter() {
		return (object)[
			ListValue::KEY_PROPERTY => Stash::ATTR_TYPE,
			ListValue::KEY_VALUE => [ Stash::TYPE ],
			ListValue::KEY_COMPARISON => ListValue::COMPARISON_CONTAINS,
			ListValue::KEY_TYPE => \BlueSpice\Data\FieldType::LISTVALUE
		];
	}

	public function getFilters() {
		if( !$this->getTitle() || !$this->getTitle()->exists() ) {
			parent::getFilters();
		};
		return array_merge( 
			parent::getFilters(),
			[ $this->getWikiPageIDFilter() ]
		);
	}

	public function showEntityListMore() {
		return false;
	}

	public function getPreloadedEntities() {
		$preloaded = parent::getPreloadedEntities();
		$stash = Services::getInstance()->getBSEntityFactory()->newFromObject(
			$this->getRawStash()
		);
		if( !$stash instanceof Stash ) {
			return $preloaded;
		}

		$status = $stash->userCan( 'create', $this->getUser() );
		if( !$status->isOK() ) {
			return $preloaded;
		}

		$preloaded[] = $this->getRawStash();
		return $preloaded;
	}

	protected function getRawStash() {
		$stash = [
			Stash::ATTR_TYPE => Stash::TYPE,
		];
		if( $this->getTitle() && $this->getTitle()->exists() ) {
			$stash[Stash::ATTR_WIKI_PAGE_ID] = $this->getTitle()->getArticleID();
			$stash[Stash::ATTR_RELATED_TITLE] = $this->getTitle()->getFullText();
		}
		return (object) $stash;
	}

	public function showEntityListMenu() {
		return true;
	}

}
