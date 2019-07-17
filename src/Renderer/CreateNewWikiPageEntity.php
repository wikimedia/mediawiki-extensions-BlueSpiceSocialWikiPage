<?php

namespace BlueSpice\Social\WikiPage\Renderer;

use BlueSpice\Services;
use MediaWiki\Linker\LinkRenderer;
use BlueSpice\Renderer\Params;

class CreateNewWikiPageEntity extends \BlueSpice\Renderer {
	const PARAM_CONTEXT = 'context';

	/**
	 *
	 * @var \IContextSource
	 */
	protected $context = null;

	/**
	 *
	 * @param \Config $config
	 * @param Params $params
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( \Config $config, Params $params, LinkRenderer $linkRenderer = null ) {
		parent::__construct( $config, $params, $linkRenderer );
		$this->context = $params->get(
			static::PARAM_CONTEXT,
			false
		);
		if ( !$this->context instanceof \IContextSource ) {
			$this->context = \RequestContext::getMain();
		}
	}

	/**
	 * Returns a rendered template as HTML markup
	 * @return string - HTML
	 */
	public function render() {
		$content = '';
		$content .= $this->getOpenTag();
		$content .= $this->makeTagContent();
		$content .= $this->getCloseTag();

		return $content;
	}

	protected function makeTagContent() {
		$content = '';
		\OutputPage::setupOOUI();
		$msg = \Message::newFromKey( 'bs-socialwikipage-nowikipageentity' );
		$title = $this->getContext()->getTitle();
		$factory = Services::getInstance()->getService(
			'BSSocialWikiPageEntityFactory'
		);
		$entity = $factory->newFromTitle( $title );
		if( !$entity->userCan( 'create', $this->getContext()->getUser() )->isOK() ) {
			$content .= new \OOUI\LabelWidget( [
				'label' => $msg->pLain(),
			] );
			return $content;
		}

		$btn = new \OOUI\ButtonWidget( [
			'infusable' => false,
			'label' => \Message::newFromKey(
				'bs-socialwikipage-entitywikipage-header-create'
			)->plain(),
			'href' => '#',
			'flags' => [
				'primary',
				'progressive'
			],
			'href' => $title->getLocalURL( [
				'action' => 'edit',
			] )
		] );
		$btn->addClasses( [
			'bs-socialwikipage-wikipage-create'
		] );
		$btn->setValue(
			$this->getContext()->getTitle()->getArticleID()
		);
		$label = new \OOUI\LabelWidget( [
			'label' => $msg->plain(),
			'input' => $btn
		] );
		$content .= $label;
		$content .= $btn;
		return $content;
	}

	/**
	 *
	 * @return \IContextSource
	 */
	public function getContext() {
		return $this->context;
	}

}
