
bs.social.EntityEditorStash = function ( config, entity ) {
	bs.social.EntityEditorText.call( this, config, entity );
};
OO.initClass( bs.social.EntityEditorStash );
OO.inheritClass( bs.social.EntityEditorStash, bs.social.EntityEditorText );

bs.social.EntityEditorStash.prototype.makeFields = function() {
	var fields = bs.social.EntityEditorStash.super.prototype.makeFields.apply(
		this
	);

	if( bs.ui.widget.TextInputMultiUpload ) {
		fields.dropzone = new bs.ui.widget.TextInputMultiUpload( {
			field: fields.text
		});
	}

	if( bs.ui.widget.TextInputFileSelect ) {
		fields.insertfile = new bs.ui.widget.TextInputFileSelect( {
			field: fields.text
		});
	}

	if( bs.ui.widget.TextInputLinkSelect ) {
		fields.insertlink = new bs.ui.widget.TextInputLinkSelect( {
			field: fields.text
		});
	}

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
		new OO.ui.FieldLayout( this.text, {
			label: this.getVarLabel( 'text' ),
			align: 'top'
		}),
		this.wikipageid
	]);
	bs.social.EntityEditorStash.super.prototype.addContentFieldsetItems.apply(
		this
	);
};