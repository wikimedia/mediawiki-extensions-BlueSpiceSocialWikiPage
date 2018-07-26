<?php

namespace BlueSpice\Social\WikiPage\EntityListContext;

use BlueSpice\Data\Filter\ListValue;
use BlueSpice\Social\WikiPage\Entity\WikiPage;

class SpecialWikiPages extends \BlueSpice\Social\EntityListContext {

	public function getLimit() {
		return 10;
	}

	public function getLockedFilterNames() {
		return array_merge(
			parent::getLockedFilterNames(),
			[ WikiPage::ATTR_TYPE ]
		);
	}

	public function getSortProperty() {
		return WikiPage::ATTR_TIMESTAMP_CREATED;
	}

	protected function getTypeFilter() {
		return (object)[
			ListValue::KEY_PROPERTY => WikiPage::ATTR_TYPE,
			ListValue::KEY_VALUE => [ WikiPage::TYPE ],
			ListValue::KEY_COMPARISON => ListValue::COMPARISON_CONTAINS,
			ListValue::KEY_TYPE => \BlueSpice\Data\FieldType::LISTVALUE
		];
	}

	/**
	 *
	 * @return boolean
	 */
	public function showEntitySpawner() {
		return false;
	}

}
