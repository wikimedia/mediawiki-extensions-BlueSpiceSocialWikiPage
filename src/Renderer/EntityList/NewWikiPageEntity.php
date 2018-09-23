<?php

namespace BlueSpice\Social\WikiPage\Renderer\EntityList;

use BlueSpice\Services;
use MediaWiki\Linker\LinkRenderer;
use BlueSpice\Renderer\Params;

class NewWikiPageEntity extends \BlueSpice\Social\Renderer\EntityList {

	/**
	 *
	 * @param \Config $config
	 * @param Params $params
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( \Config $config, Params $params, LinkRenderer $linkRenderer = null ) {
		parent::__construct( $config, $params, $linkRenderer );
		if( !$this->getContext()->getTitle()->exists() ) {
			$this->args[static::PARAM_CLASS] .= ' nowikipage';
		}
	}

	/**
	 * Returns a rendered template as HTML markup
	 * @return string - HTML
	 */
	public function render() {
		$content = '';
		if( $this->args[ static::PARAM_SHOW_HEADLINE ] ) {
			$content .= $this->renderEntityListHeadline();
		}
		$content .= $this->getOpenTag();
		$content .= $this->makeTagContent();
		$content .= $this->getCloseTag();
		if( $this->args[ static::PARAM_SHOW_ENTITY_LIST_MORE ] ) {
			$content .= $this->renderEntityListMore();
		}

		return $content;
	}

	protected function makeTagContent() {
		$content = '';
		$content .= \Html::openElement( 'li' );
		$content .= $this->renderNewWikiPageEntity();
		$content .= \Html::closeElement( 'li' );
		return $content;
	}

	protected function renderNewWikiPageEntity() {
		$renderer = Services::getInstance()->getBSRendererFactory()->get(
			'socialwikipagecreatenewwikipageentity',
			new Params( [ 'context' => $this->getContext() ] )
		);
		return $renderer->render();
	}

}
