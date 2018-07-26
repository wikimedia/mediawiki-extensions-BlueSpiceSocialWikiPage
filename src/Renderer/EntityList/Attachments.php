<?php

namespace BlueSpice\Social\WikiPage\Renderer\EntityList;

use BlueSpice\Social\Entity;
use BlueSpice\Social\EntityAttachment;

class Attachments extends \BlueSpice\Social\Renderer\EntityList {

	protected function makeTagContent() {
		$content = '';
		$content .= \Html::openElement( 'li' );
		$content .= \Html::openElement( 'div', [
			'class' => 'bs-social-entity-content-attachments'
		]);
		foreach( $this->getEntities() as $entity ) {
			$content .= $this->renderEntitiy( $entity );
		}
		$content .= \Html::closeElement( 'div' );
		$content .= \Html::closeElement( 'li' );
		return $content;
	}

	/**
	 *
	 * @param Entity $entity
	 * @return string
	 */
	protected function renderEntitiy( Entity $entity, $out = '' ) {
		if( !$entity->getConfig()->get( 'CanHaveAttachments' ) ) {
			return $out;
		}
		$availableAttachments = $entity->getConfig()->get(
			'AvailableAttachments'
		);

		$attachmentTypes = $entity->get( $entity::ATTR_ATTACHMENTS, [] );
		foreach( $attachmentTypes as $type => $attachments ) {
			if( !in_array( $type, $availableAttachments ) ) {
				continue;
			}
			if( $type === 'images' ) {
				foreach( $attachments as $image ) {
					if( !$title = \Title::newFromText( $image, NS_FILE ) ) {
						continue;
					}
					if( !$file = wfFindFile( $title ) ) {
						continue;
					}

					$entityAttachment = EntityAttachment::factory(
						$entity,
						$file,
						strpos( $file->getMimeType(), 'image' ) === false
							? 'file'
							: 'image'
					);
					$out .= $entityAttachment->render();
				}
			}
			if( $type === 'links' ) {
				foreach( $attachments as $link ) {
					if( !$title = \Title::newFromText( $link ) ) {
						continue;
					}

					$entityAttachment = EntityAttachment::factory(
						$entity,
						$title,
						'link'					);
					$out .= $entityAttachment->render();
				}
			}
		}
		return $out;
	}
}
