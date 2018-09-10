<?php
/**
 * Hook handler base class for MediaWiki hook SkinTemplateOutputPageBeforeExec
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
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
 * This file is part of BlueSpice MediaWiki
 * For further information visit http://bluespice.com
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BlueSpiceSocial
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */
namespace BlueSpice\Social\WikiPage\Hook\SkinTemplateOutputPageBeforeExec;

use BlueSpice\Context;
use BlueSpice\Renderer\Params;
use BlueSpice\Hook\SkinTemplateOutputPageBeforeExec;
use BlueSpice\Social\WikiPage\EntityListContext\AfterContent;

class AddTimeline extends SkinTemplateOutputPageBeforeExec {

	protected function skipProcessing() {
		if( !$this->skin->getTitle()->exists() ) {
			return true;
		}
		if( !$this->getConfig()->get( 'SocialWikiPageTimelineAfterContentShow' ) ) {
			return true;
		}
		$namespace = $this->skin->getTitle()->getNamespace();
		$nsBlackList = $this->getConfig()->get(
			'SocialWikiPageTimelineAfterContentNamespaceBlackList'
		);

		if( in_array( $namespace, $nsBlackList ) ) {
			return true;
		}

		if( $this->skin->getTitle()->isTalkPage() ) {
			return true;
		}

		$action = $this->getContext()->getRequest()->getVal( 'action', 'view' );
		if( $action != 'view' && $action != 'submit' ) {
			return true;
		}

		$prop = \BsArticleHelper::getInstance( $this->skin->getTitle() )
			->getPageProp( 'bs_nostash' );
		if( !is_null( $prop ) ) {
			return true;
		}

		return false;
	}

	protected function doProcess() {
		$context = new AfterContent(
			new Context(
				$this->getContext(),
				$this->getConfig()
			),
			$this->getConfig(),
			$this->getContext()->getUser(),
			null,
			$this->skin->getTitle()
		);
		$renderer = $this->getServices()->getBSRendererFactory()->get(
			'entitylistattachments',
			new Params( [ 'context' => $context ])
		);
		$item = [
			'socialwikipagestash' => $renderer->render(),
		];

		$this->mergeSkinDataArray(
			\BlueSpice\SkinData::AFTER_CONTENT,
			$item
		);
		return true;
	}

}