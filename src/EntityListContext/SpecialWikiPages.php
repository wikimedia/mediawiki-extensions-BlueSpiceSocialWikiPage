<?php

namespace BlueSpice\Social\WikiPage\EntityListContext;

use BlueSpice\Social\EntityListContext;
use BlueSpice\Social\WikiPage\Entity\WikiPage;
use MWStake\MediaWiki\Component\DataStore\FieldType;
use MWStake\MediaWiki\Component\DataStore\Filter\ListValue;

class SpecialWikiPages extends EntityListContext {

	/**
	 *
	 * @return int
	 */
	public function getLimit() {
		return 10;
	}

	/**
	 *
	 * @return array
	 */
	public function getLockedFilterNames() {
		return array_merge(
			parent::getLockedFilterNames(),
			[ WikiPage::ATTR_TYPE ]
		);
	}

	/**
	 *
	 * @return string
	 */
	public function getSortProperty() {
		return WikiPage::ATTR_TIMESTAMP_CREATED;
	}

	/**
	 *
	 * @return \stdClass
	 */
	protected function getTypeFilter() {
		return (object)[
			ListValue::KEY_PROPERTY => WikiPage::ATTR_TYPE,
			ListValue::KEY_VALUE => [ WikiPage::TYPE ],
			ListValue::KEY_COMPARISON => ListValue::COMPARISON_CONTAINS,
			ListValue::KEY_TYPE => FieldType::LISTVALUE
		];
	}

	/**
	 *
	 * @return bool
	 */
	public function showEntitySpawner() {
		return false;
	}

}
