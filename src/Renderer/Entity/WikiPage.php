<?php

namespace BlueSpice\Social\WikiPage\Renderer\Entity;

use MediaWiki\Linker\LinkRenderer;
use BlueSpice\Renderer\Params;
use BlueSpice\DynamicFileDispatcher\Params as DFDParams;
use BlueSpice\DynamicFileDispatcher\ArticlePreviewImage;

class WikiPage extends \BlueSpice\Social\Renderer\Entity\Page {

	public function __construct( \Config $config, Params $params, LinkRenderer $linkRenderer = null ) {
		parent::__construct( $config, $params, $linkRenderer );
		$this->aArgs['basetitlecontent'] = '';
	}

	protected function render_children( $val ) {
		return '';
	}

	public function render_userimage( $val ) {
		$iSize = 200;
		if( $this->renderType !== static::RENDER_TYPE_PAGE ) {
			$iSize = 50;
		}

		$title = $this->getEntity()->getRelatedTitle();
		$params = [
			DFDParams::MODULE => 'articlepreviewimage',
			ArticlePreviewImage::WIDTH => $iSize,
			ArticlePreviewImage::TITLETEXT => $title->getFullText(),
		];
		$dfdUrlBuilder = $this->getServices()->getService(
			'BSDynamicFileDispatcherUrlBuilder'
		);
		$url = $dfdUrlBuilder->build(
			new DFDParams( $params )
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