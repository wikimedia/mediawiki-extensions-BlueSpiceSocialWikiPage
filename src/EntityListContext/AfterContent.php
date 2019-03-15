<?php

namespace BlueSpice\Social\WikiPage\EntityListContext;

use BlueSpice\Data\Filter\ListValue;
use BlueSpice\Data\Filter\Numeric;
use BlueSpice\Data\Filter\Boolean;
use BlueSpice\Social\WikiPage\Entity\Stash;
use BlueSpice\Services;

class AfterContent extends \BlueSpice\Social\EntityListContext {

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
	public function __construct( \IContextSource $context, \Config $config, \User $user = null, Entity $entity = null, \Title $title = null ) {
		parent::__construct( $context, $config, $user, $entity );
		if( $title ) {
			$this->title = $title;
		}
	}

	public function getTitle() {
		return $this->title ? $this->title : $this->context->getTitle();
	}

	public function getLimit() {
		return 999;
	}

	public function getSortProperty() {
		return Stash::ATTR_TIMESTAMP_TOUCHED;
	}

	public function useEndlessScroll() {
		return false;
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

	protected function getStashTitleIDFilter() {
		return (object)[
			Numeric::KEY_PROPERTY => Stash::ATTR_WIKI_PAGE_ID,
			Numeric::KEY_VALUE => $this->getTitle()->getArticleID(),
			Numeric::KEY_COMPARISON => Numeric::COMPARISON_EQUALS,
			Numeric::KEY_TYPE => 'numeric'
		];
	}

	protected function getArchiveFilter() {
		return (object)[
			Boolean::KEY_PROPERTY => Stash::ATTR_ARCHIVED,
			Boolean::KEY_VALUE => false,
			Boolean::KEY_COMPARISON => Boolean::COMPARISON_EQUALS,
			Boolean::KEY_TYPE => 'boolean'
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
		return array_merge( 
			parent::getFilters(),
			[
				$this->getStashTitleIDFilter(),
				$this->getArchiveFilter()
			]
		);
	}

	public function getMoreLink() {
		$special = \SpecialPage::getTitleFor(
			'WikiPageStash',
			$this->getTitle()->getFullText()
		);
		return Services::getInstance()->getLinkRenderer()->makeKnownLink(
			$special,
			new \HtmlArmor( $this->getMoreLinkMessage()->text() )
		);
	}

	protected function getMoreLinkMessage() {
		return \Message::newFromKey( 'bs-social-entitylistmore-linklabel' );
	}

	public function showEntityListMore() {
		return true;
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
		$talkPage = $this->getTitle();
		return (object) [
			Stash::ATTR_TYPE => Stash::TYPE,
			Stash::ATTR_WIKI_PAGE_ID => $talkPage->getArticleID(),
			Stash::ATTR_RELATED_TITLE => $talkPage->getFullText(),
		];
	}

	public function showEntityListMenu() {
		return false;
	}

	public function showHeadline() {
		return true;
	}

	public function getHeadlineMessageKey() {
		return 'bs-socialwikipage-aftercontent-heading';
	}
}
