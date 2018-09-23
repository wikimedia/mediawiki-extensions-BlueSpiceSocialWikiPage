<?php

namespace BlueSpice\Social\WikiPage\Hook\BSInsertMagicAjaxGetData;

use BlueSpice\InsertMagic\Hook\BSInsertMagicAjaxGetData;

class AddNoStashSwitch extends BSInsertMagicAjaxGetData {

	protected function skipProcessing() {
		return $this->type !== 'switches';
	}

	protected function doProcess() {
		$this->response->result[] = (object) [
			'id' => 'bs:nostash',
			'type' => 'switch',
			'name' => 'NOSTASH',
			'desc' => \Message::newFromKey(
				'bs-socialwikipage-switch-nostash-description'
			)->plain(),
			'code' => $this->getCode(),
			'previewable' => false,
			'helplink' => $this->getHelpLink(),
		];
		return true;
	}

	protected function getCode() {
		return '__NOSTASH__';
	}

	protected function getHelpLink() {
		$extensions = \ExtensionRegistry::getInstance()->getAllThings();
		if( !isset( $extensions['BlueSpiceSocialWikiPage'] ) ) {
			return '';
		}
		return $extensions['BlueSpiceSocialWikiPage']['url'];
	}
}
