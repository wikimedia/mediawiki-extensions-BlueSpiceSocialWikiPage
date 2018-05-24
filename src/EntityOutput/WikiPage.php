<?php
/**
 *
 * Part of BlueSpice for MediaWiki
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BlueSpiceSocial
 * @subpackage BSSocialWikiPage
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 */

namespace BlueSpice\Social\WikiPage\EntityOutput;

use MediaWiki\MediaWikiServices;
use BlueSpice\Social\EntityOutput;
use BlueSpice\Social\Entity;
use BlueSpice\DynamicFileDispatcher\Params;
use BlueSpice\DynamicFileDispatcher\ArticlePreviewImage;

/**
 * This view renders the a single item.
 * @package BlueSpiceSocial
 * @subpackage BSSocialWikiPage
 */
class WikiPage extends EntityOutput{
	/**
	 * Constructor
	 */
	public function __construct( Entity $oEntity ) {
		parent::__construct( $oEntity );
		$this->aArgs['basetitlecontent'] = $oEntity->getBaseTitleContent();
	}

	protected function render_children( $mVal, $sType = 'Default' ) {
		if( $sType !== 'Page' ) {
			return '';//parent::render_children( $mVal, $sType );
		}
		return '';
	}

	public function render_userimage( $mVal, $sType = 'Default' ) {
		$iSize = 200;
		if( $sType !== 'Page' ) {
			$iSize = 50;
		}

		$title = $this->getEntity()->getRelatedTitle();
		$params = [
			Params::MODULE => 'articlepreviewimage',
			ArticlePreviewImage::WIDTH=> $iSize,
			ArticlePreviewImage::TITLETEXT => $title->getFullText(),
		];
		$dfdUrlBuilder = MediaWikiServices::getInstance()->getService(
			'BSDynamicFileDispatcherUrlBuilder'
		);
		$url = $dfdUrlBuilder->build(
			new Params( $params )
		);

		$sOut = \Html::openElement( 'a', [
			'href' => $title->getLocalURL()
		]);
		$sOut .= \Html::element( 'img', [
			'src' => $url,
			'alt' => $title->getFullText()
		]);
		$sOut .= \Html::closeElement( 'a' );
		return $sOut;
	}
}