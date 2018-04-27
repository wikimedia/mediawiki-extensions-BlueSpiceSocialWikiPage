/**
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BluespiceSocial
 * @subpackage BSSocialGroups
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 */

bs.social.EntityWikiPage = function( $el, type, data ) {
	bs.social.Entity.call( this, $el, type, data );
	var me = this;
};
OO.initClass( bs.social.EntityWikiPage );
OO.inheritClass( bs.social.EntityWikiPage, bs.social.Entity );

bs.social.EntityWikiPage.static.name = "\\BlueSpice\\Social\\WikiPage\\Entity\\WikiPage";
bs.social.factory.register( bs.social.EntityWikiPage );