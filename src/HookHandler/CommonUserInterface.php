<?php

namespace BlueSpice\Social\WikiPage\HookHandler;

use BlueSpice\Social\WikiPage\Component\AfterContent;
use MWStake\MediaWiki\Component\CommonUserInterface\Hook\MWStakeCommonUIRegisterSkinSlotComponents;

class CommonUserInterface implements MWStakeCommonUIRegisterSkinSlotComponents {

	/**
	 * @inheritDoc
	 */
	public function onMWStakeCommonUIRegisterSkinSlotComponents( $registry ): void {
		$registry->register(
			'DataAfterContent',
			[
				'social-stash' => [
					'factory' => static function () {
						return new AfterContent();
					}
				]
			]
		);
	}
}
