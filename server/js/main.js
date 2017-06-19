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
	this.selected = []
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
		$('.element').removeAttr('style').find('.name').removeAttr('style');
	}

	this.changeName = function(){
		$('body').removeAttr('onselectstart');
		for (var i = 0; i<this.selected.length; i++){
			$('#'+this.selected[i]).find('.name').attr('contenteditable', 'true').css({'border-bottom': '1px solid black'}).focus().select();
		}
	}
	
	this.exitChangeName = function(){
		$('body').removeAttr('onselectstart');
		$('.name').css({'border': 'none'}).removeAttr('contenteditable');
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




$('body').on('click', '.element', function(){
	return (!add ) ? sys.selectOne($(this)): sys.select($(this)) ;
});

$(document).on('keyup keydown', function(e){
	if (!e.shiftKey) return;
	sys.select($(this));
});

$('body').on('dbclick', '.element', function(){
		var id = $(this).attr('class');
		console.log(id);
		sys.loadMoreFolder(id);
});

$('body').on('keyup keydown', '.element .name', function(e){
	if (e.which !== 13) return;
	e.preventDefault();
	sys.exitChangeName();
	sys.unselect();
	
});

$('body, main').click(function(e){if (e.target !== this) return; sys.unselect();});

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

