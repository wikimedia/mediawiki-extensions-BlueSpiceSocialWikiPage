<?php

namespace BlueSpice\Social\WikiPage\Renderer;

use BlueSpice\Renderer\Params;
use Config;
use IContextSource;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPageFactory;
use OutputPage;
use RequestContext;

class CreateFirstAttachmentAfterContent extends \BlueSpice\Renderer {
	public const PARAM_CONTEXT = 'context';

	/**
	 * @var SpecialPageFactory
	 */
	protected $specialPageFactory = null;

	/**
	 * Constructor
	 * @param Config $config
	 * @param Params $params
	 * @param LinkRenderer|null $linkRenderer
	 * @param IContextSource|null $context
	 * @param string $name
	 * @param SpecialPageFactory|null $specialPageFactory
	 */
	protected function __construct( Config $config, Params $params,
		LinkRenderer $linkRenderer = null, IContextSource $context = null,
		$name = '', SpecialPageFactory $specialPageFactory = null ) {
		$this->specialPageFactory = $specialPageFactory;
		parent::__construct( $config, $params, $linkRenderer, $context, $name );

		$this->context = $params->get(
			static::PARAM_CONTEXT,
			false
		);
		if ( !$this->context instanceof IContextSource ) {
			$this->context = RequestContext::getMain();
		}
	}

	/**
	 * @param string $name
	 * @param MediaWikiServices $services
	 * @param Config $config
	 * @param Params $params
	 * @param IContextSource|null $context
	 * @param LinkRenderer|null $linkRenderer
	 * @param SpecialPageFactory|null $specialPageFactory
	 * @return Renderer
	 */
	public static function factory( $name, MediaWikiServices $services, Config $config,
		Params $params, IContextSource $context = null, LinkRenderer $linkRenderer = null,
		SpecialPageFactory $specialPageFactory = null ) {
		if ( !$context ) {
			$context = $params->get(
				static::PARAM_CONTEXT,
				false
			);
			if ( !$context instanceof IContextSource ) {
				$context = \RequestContext::getMain();
			}
		}
		if ( !$linkRenderer ) {
			$linkRenderer = $services->getLinkRenderer();
		}
		if ( !$specialPageFactory ) {
			$specialPageFactory = $services->getSpecialPageFactory();
		}

		return new static(
			$config,
			$params,
			$linkRenderer,
			$context,
			$name,
			$specialPageFactory
		);
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
		OutputPage::setupOOUI();

		$title = $this->getContext()->getTitle();
		$factory = MediaWikiServices::getInstance()->getService(
			'BSSocialWikiPageEntityFactory'
		);
		$entity = $factory->newFromTitle( $title );
		if ( !$entity->userCan( 'create', $this->getContext()->getUser() )->isOK() ) {
			$msg = $this->msg( 'bs-socialwikipage-noattachmentsadded' );
			$content .= new \OOUI\LabelWidget( [
				'label' => $msg->plain(),
			] );
			return $content;
		}

		$label = $this->msg(
			'bs-socialwikipage-entitywikipage-header-create'
		)->plain();
		$btn = new \OOUI\ButtonWidget( [
			'infusable' => false,
			'label' => $label,
			'title' => $label,
			'framed' => false,
			'icon' => 'add',
			'invisibleLabel' => true,
			'flags' => [
				'primary',
				'progressive'
			],
			'href' => $this->specialPageFactory->getPage( 'WikiPageStash' )
				->getTitleFor( 'WikiPageStash', $title->getFullText() )->getFullURL(),
		] );
		$btn->addClasses( [
			'bs-socialwikipage-attachment-create-first'
		] );
		$btn->setValue(
			$this->getContext()->getTitle()->getArticleID()
		);
		$content .= $btn;
		return $content;
	}

}
