<?php

namespace BlueSpice\Social\WikiPage\Renderer\Entity;

use MediaWiki\Linker\LinkRenderer;
use BlueSpice\Renderer\Params;
use BlueSpice\Social\Entity\Text as EntityText;

class Stash extends \BlueSpice\Social\Renderer\Entity\Text {
	const START_EDITOR = 'starteditor';

	public function __construct( \Config $config, Params $params, LinkRenderer $linkRenderer = null ) {
		parent::__construct( $config, $params, $linkRenderer );
		$this->args['content'] = '';
		$this->args['attachments'] = $this->getEntity()->get(
			EntityText::ATTR_ATTACHMENTS
		);
		$this->args[static::START_EDITOR] = false;
	}

	protected function render_content( $val ) {
		return '';
	}

	public function startEditor( $startEditor = true ) {
		//Hacky workaround to restart the editor on the client side
		$this->args[static::START_EDITOR] = $startEditor ? true : false;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	protected function makeTagAttribs() {
		$attribs = parent::makeTagAttribs();
		if( $this->args[static::START_EDITOR] ) {
			$attribs["data-".static::START_EDITOR] = static::START_EDITOR;
		}
		return $attribs;
	}
}
