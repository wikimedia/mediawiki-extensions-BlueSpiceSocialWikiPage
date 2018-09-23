<?php

/**
 * WikiPage class for BSSocial
 *
 * add desc
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice for MediaWiki
 * For further information visit http://bluespice.com
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BlueSpiceSocial
 * @subpackage BSSocialWikiPage
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */
namespace BlueSpice\Social\WikiPage\EntityConfig;
use BlueSpice\Social\EntityConfig\Page;
use BlueSpice\Social\Data\Entity\Schema;
use BlueSpice\Data\FieldType;
use BlueSpice\Social\WikiPage\Entity\WikiPage as Entity;

/**
 * WikiPage class for BSSocial extension
 * @package BlueSpiceSocial
 * @subpackage BSSocialWikiPage
 */
class WikiPage extends Page {
	public function addGetterDefaults() {
		return array();
	}
	public function get_EntityClass() {
		return "\\BlueSpice\\Social\\WikiPage\\Entity\\WikiPage";
	}

	protected function get_Renderer() {
		return 'socialentitywikipage';
	}

	protected function get_EntityTemplateDefault() {
		return 'BlueSpiceSocialWikiPage.Entity.WikiPage.Default';
	}
	protected function get_EntityTemplatePage() {
		return 'BlueSpiceSocialWikiPage.Entity.WikiPage.Page';
	}
	protected function get_ModuleStyles() {
		return array_merge( parent::get_ModuleStyles(), [
			'ext.bluespice.social.articles.styles'
		]);
	}
	protected function get_ModuleScripts() {
		return array_merge( parent::get_ModuleScripts(), [
			'ext.bluespice.social.entity.wikipage',
		]);
	}
	protected function get_CanHaveChildren() {
		return true;
	}
	protected function get_TypeMessageKey() {
		return 'bs-socialwikipage-type';
	}
	protected function get_HeaderMessageKey() {
		return 'bs-socialwikipage-entitywikipage-header';
	}
	protected function get_HeaderMessageKeyCreateNew() {
		return 'bs-socialwikipage-entitywikipage-header-create';
	}
	protected function get_HeaderWithTitleMessageKey() {
		return 'bs-socialwikipage-entitywikipage-withtitleheader';
	}
	protected function get_IsCreatable() {
		return true;
	}
	protected function get_IsDeleteable() {
		return false;
	}
	protected function get_IsTagable() {
		return false;
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
				Entity::ATTR_NAMESPACE => [
					Schema::FILTERABLE => true,
					Schema::SORTABLE => true,
					Schema::TYPE => FieldType::INT,
					Schema::INDEXABLE => true,
					Schema::STORABLE => true,
				],
				Entity::ATTR_TITLE_TEXT => [
					Schema::FILTERABLE => true,
					Schema::SORTABLE => true,
					Schema::TYPE => FieldType::STRING,
					Schema::INDEXABLE => true,
					Schema::STORABLE => true,
				],
			]
		);
	}
}