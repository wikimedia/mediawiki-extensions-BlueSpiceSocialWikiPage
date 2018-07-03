<?php


namespace BlueSpice\Social\WikiPage\EntityConfig;

use BlueSpice\Services;
use BlueSpice\Social\EntityConfig\Text;
use BlueSpice\Social\Data\Entity\Schema;
use BlueSpice\Data\FieldType;
use BlueSpice\Social\WikiPage\Entity\Stash as Entity;

/**
 * Stash class for BSSocial extension
 * @package BlueSpiceStashs
 * @subpackage BSSocial
 */
class Stash extends Text{

	public function addGetterDefaults() {
		return [];
	}

	public function get_EntityClass() {
		return "\\BlueSpice\\Social\\WikiPage\\Entity\\Stash";
	}

	protected function get_Renderer() {
		return 'socialentitystash';
	}

	protected function get_ModuleScripts() {
		$dropzone = Services::getInstance()->getBSExtensionFactory()
			->getExtension( 'BlueSpiceMultiUpload' )
			? ["ext.bluespice.social.entity.stash.dropzone"]
			: [];
		$insertFile = Services::getInstance()->getBSExtensionFactory()
			->getExtension( 'BlueSpiceInsertFile' )
			? ["ext.bluespice.social.entity.stash.insertfile"]
			: [];
		$insertFile = []; //InserFile dialog is not working currently
		$insertLink = Services::getInstance()->getBSExtensionFactory()
			->getExtension( 'BlueSpiceInsertLink' )
			? ["ext.bluespice.social.entity.stash.insertlink"]
			: [];
		$insertLink = []; //InserLink is also not working currently
		return array_merge(
			parent::get_ModuleScripts(),
			[ 'ext.bluespice.social.entity.stash' ],
			$dropzone,
			$insertFile,
			$insertLink
		);
	}

	protected function get_ModuleStyles() {
		$insertFile = Services::getInstance()->getBSExtensionFactory()
			->getExtension( 'BlueSpiceInsertFile' )
			? ["ext.bluespice.insertFile.styles"]
			: [];
		return array_merge(
			parent::get_ModuleStyles(),
			$insertFile
		);
	}

	protected function get_TypeMessageKey() {
		return 'bs-socialwikipage-stashtype';
	}

	protected function get_HeaderMessageKeyCreateNew() {
		return 'bs-socialwikipage-entitystash-header-create';
	}

	protected function get_HeaderMessageKey() {
		return 'bs-socialwikipage-entitystash-header';
	}

	protected function get_VarMessageKeys() {
		return array_merge(
			parent::get_VarMessageKeys(),
			[
				Entity::ATTR_WIKI_PAGE_ID => 'bs-socialwikipage-var-wikipageid'
			]
		);
	}

	protected function get_AttributeDefinitions() {
		return array_merge(
			parent::get_AttributeDefinitions(),
			[
				Entity::ATTR_WIKI_PAGE_ID => [
					Schema::FILTERABLE => true,
					Schema::SORTABLE => true,
					Schema::TYPE => FieldType::INT,
					Schema::INDEXABLE => true,
					Schema::STORABLE => true,
				],
			]
		);
	}

}