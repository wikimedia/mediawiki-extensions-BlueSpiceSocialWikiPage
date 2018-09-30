
bs.social.EntityEditorStash = function ( config, entity ) {
	config.visualEditor = false; //do not allow visual editor here
	bs.social.EntityEditorText.call( this, config, entity );
};
OO.initClass( bs.social.EntityEditorStash );
OO.inheritClass( bs.social.EntityEditorStash, bs.social.EntityEditorText );

bs.social.EntityEditorStash.prototype.makeFields = function() {
	var fields = bs.social.EntityEditorStash.super.prototype.makeFields.apply(
		this
	);

	//overwrite the text widget to make in an actual hidden field
	this.text = new OO.ui.HiddenInputWidget( {
		value: this.getEntity().data.get( 'text', '' )
	});

	for( var i in this.getEntity().data.get( 'attachments' ).images ) {
		
	}
	this.attachments = new bs.ui.widget.TextInputAttachments( {
		attachments: this.getEntity().data.get( 'attachments' )
	} );
	fields.attachments = this.attachments;

	/*if( bs.ui.widget.TextInputMultiUpload ) {
		this.dropzone =  new bs.ui.widget.TextInputMultiUpload( {} );
		fields.dropzone = this.dropzone;
	}

	if( bs.ui.widget.TextInputFileSelect ) {
		this.insertfile = new bs.ui.widget.TextInputFileSelect( {} );
		fields.insertfile = this.insertfile;
	}

	if( bs.ui.widget.TextInputLinkSelect ) {
		this.insertlink = new bs.ui.widget.TextInputLinkSelect( {} );
		fields.insertlink = this.insertlink;
	}*/

	var disabled = false;
	var wikipageid = this.getEntity().data.get(
		'wikipageid',
		0
	);
	var titleText = this.getEntity().data.get(
		'relatedtitle',
		''
	);
	if( wikipageid > 0 && titleText !== '' ) {
		disabled = true;
	}
	var option = '', localData = [];
	if( wikipageid > 0 ) {
		option = '<option selected="selected" value="' + wikipageid + '">' + titleText + '</option>';
		localData.push({
			text: titleText,
			id: wikipageid
		});
	}
	//fake oojs item - use working js
	this.wikipageid = {
		select2: true,
		$element: $(
			'<div class="bs-social-field">'
				+ '<label>'
					/*+ this.getVarLabel( 'wikipageid' )*/
					+ '<select style="width:100%">'
						+ option
					+ '</select>'
				+ '</label>'
			+ '</div>'
		),
		setElementGroup: function(){}
	};
	var namespaces = mw.config.get( 'wgNamespaceIds' );
	var ns = [];
	for( var i in namespaces ) {
		if( namespaces[i] < 1 || namespaces[i]%2 !== 0 ) {
			continue;
		}
		ns.push( namespaces[i] );
	}
	this.wikipageid.$element.find( 'select' ).select2({
		data: localData,
		placeholder: this.getVarLabel( 'wikipageid' ),
		label: this.getVarLabel( 'wikipageid' ),
		allowClear: true,
		disabled: disabled,
		ajax: {
			url: mw.util.wikiScript( 'api' ),
			dataType: 'json',
			tape: 'POST',
			data: function (params) {
				return {
					action: 'bs-socialtitlequery-store',
					query: params.term,
					options: JSON.stringify({
						namespaces: ns
					})
				};
			},
			processResults: function (data) {
				var results = [];
				$.each(data.results, function (index, result) {
					results.push({
						id: result.page_id,
						text: result.prefixedText
					});
				});
				return {
					results: results
				};
			}
		},
		initSelection: function(element, callback) {
			return callback( localData );
		},
		minimumInputLength: 1
	});
	fields.wikipageid = this.wikipageid;

	return fields;
};
bs.social.EntityEditorStash.prototype.addContentFieldsetItems = function() {
	this.contentfieldset.addItems( [
		new OO.ui.FieldLayout( this.text, {} ),
		this.wikipageid
	]);
	bs.social.EntityEditorStash.super.prototype.addContentFieldsetItems.apply(
		this
	);
};

bs.social.EntityEditorStash.prototype.getShortModeField = function() {
	return this.dropzone;
};
