var w = window.innerWidth
	h = window.innerHeight,
	mainColor = '#00edff20',
	add = false;
/*
*Primero se cargan en main todos los elementos y se
guardan en la estructura del main en home.html
(ahora comentada)
*/
Array.prototype.getIndex = function(str) {
	for (var i = 0; i< this.length; i++){
		if (str == this[i]) return i;
	}
	return false
};




function SYSTEM(){
	this.selected = [];
	this.selectedName = [];
	this.load = function() {
		/*
		*Con está función se pretende "pedir" al servidor todas
		las carpetas y archivos e imprimirlos en pantalla
		*/
		$.ajax({
			url: "server/php/proccess.php",
			data: {'function': 'load'},
			method: 'post',
			encoding:"utf-8",
			success: function(data) {$('main').html(data)},
			error: function(e){alert(e)}
		});
	}
	this.selectOne = function(el) {
		var id = el.attr('id');
		var index = this.selected.getIndex(id);
		if (index === false){
			$('.element').css('background-color', 'inherit');
			el.css('background-color', mainColor);
			this.selected = [];
			this.selected.push(id)
		}
		else {
			el.css('background-color', 'inherit');
			this.selected[index] = [];
		}
	}
	this.select = function(el){
		var id = el.attr('id');
		var index = this.selected.getIndex(id);
		if (index === false){
			el.css('background-color', mainColor);
			this.selected.push(id)
		}
		else {
			el.css('background-color', 'inherit');
			this.selected[index] = undefined;
		}	
	}

	this.unselect = function(){
		this.selected = [];
		this.selectedName = [];
		$('.element').removeAttr('style').find('.name').removeAttr('style');
	}

	this.changeName = function(){
		$('body').removeAttr('onselectstart');
		this.selectedName.push($('#'+this.selected[0]).find('.name').html())
		$('#'+this.selected[0]).find('.name').attr('contenteditable', 'true').css({'border-bottom': '1px solid black'}).focus().select();
		console.log(this.selectedName[0] + 'linea 74-> changeName')
		
	}
	
	this.exitChangeName = function(newName){
		alert('numero de veces que entra')
		$('body').removeAttr('onselectstart');
		newName.parent().parent().attr('id', newName.html());
		$('.name').css({'border': 'none'}).removeAttr('contenteditable');
		if (typeof this.selectedName[0] == 'undefined') this.selectedName[0] = newName.html().replace('<br>', '');
		$.post('server/php/proccess.php', {'function':'changeName', 'name': [this.selectedName[0], newName.html().replace('<br>', '')]}, function(d){console.log('la respuesta es: '+d)});
	}

	this.newFolder = function() {
		$('main').append("<div class=\"element\" id=\"nuevaCarpeta\"><div class=\"folderIcon\"><img src=\"server/img/folder.svg\"><div class=\"name\" contenteditable=\"true\">nuevaCarpeta</div></div></div>");
		$('body').removeAttr('onselectstart');
		this.selected = ['nuevaCarpeta'];
		this.selected = ['nuevaCarpeta'];
		$('#'+this.selected[0]).find('.name').attr('contenteditable', 'true').css({'border-bottom': '1px solid black'}).focus().select();
	}

	this.openFolder = function(name) {
		$.post('server/php/proccess.php', {'function': 'openDirectory', 'name': name}, function(d){
			$('main').html(d);
		});
	}

	this.exit = function() {
		$("[value='exit']").click();
	}
	this.download = function() {
		names = [];
		for (var i = 0; i<this.selected.length; i++){
			names.push($('#' + this.selected[i]).find('.name').html());
		}
		$.post('server/php/proccess.php', {'function': 'download', 'names': names}, function(d){$('body').html(d)});
		//location.href = 'server/php/descarga.php?names='+names[0]
	}
	this.upLevel = function() {

		$.post('server/php/proccess.php', {'function': 'upLevel'}, function(d){
			$('main').html(d);
		});
	}

	this.upLoadFiles = function(files) {
		var data = new FormData();
		if(files){
			$.each(files, function(i, file){
				console.log("file"+ i);
				console.log(file);
				data.append("files[]", file);
			});
		}

		$.ajax({
			url: "server/php/proccess.php",
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			success: function(d) {
				$('main').append(d);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(xhr+"\n"+ ajaxOptions+"\n"+ thrownError);
			}
		})
	}

	this.load();
}

function ELEMENT(id) {
	this.x = 0;
	this.y = 0;
	this.jqr = '';
	this.id = id; 
	this.loadElem = function() {
		this.jqr = $('#'+this.id);
		var offset = this.jqr.offset()
		this.x = offset.left;
		this.y = offset.top;
	}
	this.loadElem()
}
var sys = new SYSTEM();
function loadElemts() {
	var id = '',
		$elements = $('.element')
	for (var i = 0; i< $elements.length; i++){
		id = $($elements[i]).attr('id');
		eval('window.'+id+ '= new ELEMENT('+id+')');
	}
}

//loadElemts();




$('body').on('click', '.folderIcon', function(){
	(!add ) ? 
		sys.selectOne($(this).parent()) : 
		sys.select($(this).parent()) ;
});

$(document).on('keyup keydown', function(e){
	if (!e.shiftKey) return;
	sys.select($(this));
});

$('body').on('dblclick', '.folderIcon', function(){
	if ($(this).parent().attr('id') == 'upLevel') return;
	var id = $(this).find('.name').html();
	console.log(id);
	sys.openFolder(id);
});

$('body').on('keyup keydown', '.element .name', function(e){
	if (e.which !== 13) return;
	e.preventDefault();
	sys.exitChangeName($(this));
	sys.unselect();
	
});

$('body, main').click(function(e){if (e.target !== this) return; sys.unselect();});

$('#añadir').click(function(){
	sys.newFolder();
});

$('#exit').click(function(e){sys.exit();})

$('#descargar').click(function(e){sys.download();});

$('body').on('dblclick', '#upLevel .folderIcon', function(){
	sys.upLevel();
});

$('#lavel').on('dragover', function(e){
	e.preventDefault();
	e.stopPropagation();
}).on('dragenter', function(e){
	e.preventDefault();
	e.stopPropagation();
}).on('drop', function(e){
	if(e.originalEvent.dataTransfer){
		if(e.originalEvent.dataTransfer.files.length){
			e.preventDefault();
			e.stopPropagation();
			//alert(e.originalEvent.dataTransfer.files.length);
			var files = e.originalEvent.dataTransfer.files;
			sys.upLoadFiles(files)
			//$('#files').attr('value', files);


		}
	}
})


$(document).keydown(function(e){
	var key = e.which;
	switch (key) {
		case 16: // sifth
			break;
		case 17: // ctrl
			add = true;
			break;
		case 113: //f2
			sys.changeName();
			break;
		case 123: //f12
			//e.preventDefault();
			break;
		default:
			console.log(e.which );
			break;		
	}
}).keyup(function(e){add = false;});

$('main').css({'height': h*0.8});
$('header').css({'height': w*0.06});



$('form#fileupload').submit(function(e){
	e.preventDefault();
	console.log($(this).find('#files').val());
});

