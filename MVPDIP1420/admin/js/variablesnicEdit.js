	/**
	* tplText
	* @description: Inserta texto predefinido
	* @requires: nicCore, nicPane, nicAdvancedButton
	* @author: Alberto JC
	* @version: 1.0.0
	*/

	/* START CONFIG */
	var tplTextOptions = {
		buttons : {
			'Ciudadanos' : {name : __('Datos Ciudadanos'), type : 'nicEditorCiudadanosSelect', command : 'InsertText'},
			'Ciudadanos_Usuarios' : {name : __('Datos Ciudadanos Usuarios'), type : 'nicEditorCiudadanosUsuariosSelect', command : 'InsertText'},
			'Ciudadanos_Cartografia' : {name : __('Datos Ciudadanos Cartografías'), type : 'nicEditorCiudadanosCartografiasSelect', command : 'InsertText'},
			'Datos_Plataforma' : {name : __('Datos Plataforma'), type : 'nicEditorDatosCorreoSelect', command : 'InsertText'},
			'Datos_Correo_Fecha_Hora' : {name : __('Datos Correo Fecha Hora'), type : 'nicEditorDatosCorreoFechaHoraSelect', command : 'InsertText'},
		}/* NICEDIT_REMOVE_START */,
		/* NICEDIT_REMOVE_END */
	};

	function addDataText(content,id){//content: contenido a colocar, id: id del text area
		var editor = nicEditors.findEditor(id);
		var editingArea = editor.e;
		var userSelection = editor.getSel();
		// IE.
		if (document.selection) {
			editingArea.focus();
			userSelection.createRange().text = content;
		}else {
			// Convert selection to range.
			var range;
			// W3C compatible.
			if (userSelection.getRangeAt) {
				range = userSelection.getRangeAt(0);
			}
			// Safari.
			else {
			  range = editingArea.ownerDocument.createRange();
			  range.setStart(userSelection.anchorNode, userSelection.anchorOffset);
			  range.setEnd(userSelection.focusNode, userSeletion.focusOffset);
			}
			// The code below doesn't work in IE, but it never gets here.
			var fragment = editingArea.ownerDocument.createDocumentFragment();
			// Fragments don't support innerHTML.
			var wrapper = editingArea.ownerDocument.createElement('div');
			wrapper.innerHTML = content;
			while (wrapper.firstChild) {
			  fragment.appendChild(wrapper.firstChild);
			}
			range.deleteContents();
			// Only fragment children are inserted.
			range.insertNode(fragment);
		}
	}
	//////Campos personalizados cambrano
	var nicEditorCiudadanosSelect = nicEditorSelect.extend({
		sel : {
			'Tipo_Ciudadano' : 'Tipo Ciudadano',
			'Nombre_Completo' : 'Nombre Completo',
			'Nombre' : 'Nombre',
			'Apellido_Paterno' : 'Apellido Paterno',
			'Apellido_Materno' : 'Apellido Materno',
			'Fecha_Nacimiento' : 'Fecha Nacimiento',
			'Fecha_Nacimiento_Texto' : 'Fecha Nacimiento Texto',
			'Edad' : 'Edad',
			'Sexo' : 'Sexo',
			'Relacionado' : 'Ciudadano Relacionado',
			'Whatsapp' : 'Whatsapp',
			'Telefono' : 'Teléfono',
			'Celular' : 'Celular',
			'Correo_Electronico' : 'Correo Electrónico',
		},
		init : function() {
			this.setDisplay('Datos Ciudadanos');
			for(itm in this.sel) {
				this.add(itm,this.sel[itm]);
			}
		},

		update : function(elm) {
			if (this.options.command == 'InsertText') {
				switch(elm){
					case 'Tipo_Ciudadano':content="[*__Tipo_Ciudadano__*]";break;
					case 'Nombre_Completo':content="[*__Nombre_Completo__*]";break;
					case 'Nombre':content="[*__Nombre__*]";break;
					case 'Apellido_Paterno':content="[*__Apellido_Paterno__*]";break;
					case 'Apellido_Materno':content="[*__Apellido_Materno__*]";break;
					case 'Fecha_Nacimiento':content="[*__Fecha_Nacimiento__*]";break;
					case 'Fecha_Nacimiento_Texto':content="[*__Fecha_Nacimiento_Texto__*]";break;
					case 'Edad':content="[*__Edad__*]";break;
					case 'Sexo':content="[*__Sexo__*]";break;
					case 'Relacionado':content="[*__Relacionado__*]";break;
					case 'Whatsapp':content="[*__Whatsapp__*]";break;
					case 'Telefono':content="[*__Telefono__*]";break;
					case 'Celular':content="[*__Celular__*]";break;
					case 'Correo_Electronico':content="[*__Correo_Electronico__*]";break;
				}
				addDataText(content,this.ne.selectedInstance.e.id);
			}else{
				this.ne.nicCommand(this.options.command,elm);
			}
			this.close();
		}
	});
	//////SELECT END
	//////Campos personalizados cambrano
	var nicEditorCiudadanosUsuariosSelect = nicEditorSelect.extend({
		sel : {
			'Usuario' : 'Usuario',
			'Password' : 'Password',
			'Status' : 'Estatus',
		},
		init : function() {
			this.setDisplay('Datos Ciudadanos Usuarios');
			for(itm in this.sel) {
				this.add(itm,this.sel[itm]);
			}
		},

		update : function(elm) {
			if (this.options.command == 'InsertText') {
				switch(elm){
					case 'Usuario':content="[*__Usuario__*]";break;
					case 'Password':content="[*__Password__*]";break;
					case 'Status':content="[*__Status__*]";break;
				}
				addDataText(content,this.ne.selectedInstance.e.id);
			}else{
				this.ne.nicCommand(this.options.command,elm);
			}
			this.close();
		}
	});
	//////SELECT END
	//////Campos personalizados cambrano
	var nicEditorCiudadanosCartografiasSelect = nicEditorSelect.extend({
		sel : {
			'Estado' : 'Estado',
			'Municipio' : 'Municipio',
			'Localidad' : 'Localidad',
			'Distrito_Local' : 'Distrito Local',
			'Distrito_Federal' : 'Distrito Federal',
			'Seccion' : 'Sección',
		},
		init : function() {
			this.setDisplay('Datos Ciudadanos Cartograf&iacute;a');
			for(itm in this.sel) {
				this.add(itm,this.sel[itm]);
			}
		},

		update : function(elm) {
			if (this.options.command == 'InsertText') {
				switch(elm){
					case 'Estado':content="[*__Estado__*]";break;
					case 'Municipio':content="[*__Municipio__*]";break;
					case 'Localidad':content="[*__Localidad__*]";break;
					case 'Distrito_Local':content="[*__Distrito_Local__*]";break;
					case 'Distrito_Federal':content="[*__Distrito_Federal__*]";break;
					case 'Seccion':content="[*__Seccion__*]";break;
				}
				addDataText(content,this.ne.selectedInstance.e.id);
			}else{
				this.ne.nicCommand(this.options.command,elm);
			}
			this.close();
		}
	});
	//////SELECT END
	//////Campos personalizados cambrano
	var nicEditorDatosCorreoFechaHoraSelect = nicEditorSelect.extend({
		sel : {
			'Fecha' : 'Fecha (2021-01-01)',
			'Fecha_WDDMMAAA' : 'Fecha (Lun 01 Feb 2021)',
			'Hora' : 'Hora (18:01:01)',
			'Hora_AMPM' : 'Hora AM/PM (01:12 PM)',
			'Hora_ampm' : 'Hora am/pm (01:12 pm)',
		},
		init : function() {
			this.setDisplay('Datos Fecha / Hora');
			for(itm in this.sel) {
				this.add(itm,this.sel[itm]);
			}
		},

		update : function(elm) {
			if (this.options.command == 'InsertText') {
				switch(elm){
					case 'Fecha':content="[*__Fecha__*]";break;
					case 'Fecha_WDDMMAAA':content="[*__Fecha_WDDMMAAA__*]";break;
					case 'Hora':content="[*__Hora__*]";break;
					case 'Hora_AMPM':content="[*__Hora_AMPM__*]";break;
					case 'Hora_ampm':content="[*__Hora_ampm__*]";break;
				}
				addDataText(content,this.ne.selectedInstance.e.id);
			}else{
				this.ne.nicCommand(this.options.command,elm);
			}
			this.close();
		}
	});
	//////SELECT END
	//////Campos personalizados cambrano
	var nicEditorDatosCorreoSelect = nicEditorSelect.extend({
		sel : {
			'URL' : 'Url Base',
			'Nombre_Sistema' : 'Nombre Sistema',
			'Slogan_Sistema' : 'Slogan Sistema', 
		},
		init : function() {
			this.setDisplay('Datos Plataforma');
			for(itm in this.sel) {
				this.add(itm,this.sel[itm]);
			}
		},

		update : function(elm) {
			if (this.options.command == 'InsertText') {
				switch(elm){
					case 'URL':content="[*__URL__*]";break;
					case 'Nombre_Sistema':content="[*__Nombre_Sistema__*]";break;
					case 'Slogan_Sistema':content="[*__Slogan_Sistema__*]";break; 
				}
				addDataText(content,this.ne.selectedInstance.e.id);
			}else{
				this.ne.nicCommand(this.options.command,elm);
			}
			this.close();
		}
	});
	//////SELECT END

	
	/* END CONFIG */
	nicEditors.registerPlugin(nicPlugin,tplTextOptions);