var w = window.innerWidth
h = window.innerHeight,
	mainColor = '#00edff20',
	add = false,
	multiple = false,
	page = (location.href.search('home') !== -1),
	nivel = (page) ? 0 : 1,
	section = $('section'),
	liftButton = $('.liftButton');
/*
*Primero se cargan en main todos los elementos y se
guardan en la estructura del main en home.html
(ahora comentada)
*/
Array.prototype.getIndex = function(str) {
	for (var i = 0; i < this.length; i++) {
		if (str == this[i]) return i;
	}
	return false
};




function SYSTEM() {
	this.selected = [];
	this.selectedName = [];
	this.pos = [];
	this.load = function() {
		/*
		*Con está función se pretende "pedir" al servidor todas
		las carpetas y archivos e imprimirlos en pantalla
		*/
		$.ajax({
			url: "server/php/proccess.php",
			data: { 'function': 'load' },
			method: 'post',
			encoding: "utf-8",
			success: function(data) { $('main').html(data) },
			error: function(e) { alert(e) }
		});
	}
	this.selectOne = function(el) {
		var id = el.attr('id');
		var index = this.selected.getIndex(id);
		if (index === false) {
			$('.element').css('background-color', 'inherit');
			el.css('background-color', mainColor);
			this.selected = [];
			this.selected.push(id)
		} else {
			el.css('background-color', 'inherit');
			this.selected[index] = [];
		}
	}
	this.select = function(el) {
		console.log(el)
		var id = el.attr('id');
		var index = this.selected.getIndex(id);
		if (index === false) {
			el.css('background-color', mainColor);
			this.selected.push(id)
		} else {
			el.css('background-color', 'inherit');
			this.selected[index] = undefined;
		}
	}

	this.unselect = function() {
		this.selected = [];
		this.selectedName = [];
		$('.element').removeAttr('style').find('.name').removeAttr('style');
		this.pos = [];
	}

	this.changeName = function() {
		$('body').removeAttr('onselectstart');
		this.selectedName.push($('#' + this.selected[0]).find('.name').html())
		$('#' + this.selected[0]).find('.name').attr('contenteditable', 'true').css({ 'border-bottom': '1px solid black' }).focus().select();
		console.log(this.selectedName[0] + 'linea 74-> changeName')

	}

	this.exitChangeName = function(newName) {
		$('body').removeAttr('onselectstart');
		newName.parent().parent().attr('id', newName.html());
		$('.name').css({ 'border': 'none' }).removeAttr('contenteditable');
		if (typeof this.selectedName[0] == 'undefined') this.selectedName[0] = newName.html().replace('<br>', '');
		$.post('server/php/proccess.php', { 'function': 'changeName', 'name': [this.selectedName[0], newName.html().replace('<br>', '')] }, function(d) { console.log('la respuesta es: ' + d) });
	}

	this.newFolder = function() {
		$('main').append("<div class=\"element\" id=\"nuevaCarpeta\"><div class=\"folderIcon\"><img src=\"server/img/folder.svg\"><div class=\"name\" contenteditable=\"true\">nuevaCarpeta</div></div></div>");
		$('body').removeAttr('onselectstart');
		this.selected = ['nuevaCarpeta'];
		this.selected = ['nuevaCarpeta'];
		$('#' + this.selected[0]).find('.name').attr('contenteditable', 'true').css({ 'border-bottom': '1px solid black' }).focus().select();
	}

	this.openFolder = function(name) {
		$.post('server/php/proccess.php', { 'function': 'openDirectory', 'name': name }, function(d) {
			$('main').html(d);
		});
	}

	this.exit = function() {
		$("[value='exit']").click();
	}
	this.download = function() {
		names = [];
		for (var i = 0; i < this.selected.length; i++) {
			names.push($('#' + this.selected[i]).find('.name').html());
		}
		$.post('server/php/proccess.php', { 'function': 'download', 'names': names }, function(d) { $('head').append(d) });
		//location.href = 'server/php/descarga.php?names='+names[0]
	}
	this.upLevel = function() {

		$.post('server/php/proccess.php', { 'function': 'upLevel' }, function(d) {
			$('main').html(d);
		});
	}

	this.upLoadFiles = function(files) {
		var data = new FormData();
		if (files) {
			$.each(files, function(i, file) {
				console.log("file" + i);
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
				console.log(xhr + "\n" + ajaxOptions + "\n" + thrownError);
			}
		})
	}

	this.changeTheme = function(theme) {
		$.post('server/php/proccess.php', { 'function': 'changeTheme', 'theme': theme }, function(d) { console.log(d) });
	}

	this.load();
}
/*
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
}*/
var sys = new SYSTEM();

function loadElemts() {
	var id = '',
		$elements = $('.element')
	for (var i = 0; i < $elements.length; i++) {
		id = $($elements[i]).attr('id');
		eval('window.' + id + '= new ELEMENT(' + id + ')');
	}
}

//loadElemts();

function ascendente(direccion) {
	nivel = (direccion) ? nivel - 1 : nivel + 1;
	if (nivel == -1) nivel = 0;
	if (nivel == 4) nivel = 3;
	if (nivel == 0) {
		$('body, html').animate({ 'scrollTop': 0 }, 750);
		$('#ancla').animate({ 'opacity': 0 }, 750);
	} else {
		$('body, html').animate({ 'scrollTop': $(section[nivel - 1]).offset().top }, 750);
		$('#lift, #ancla').animate({ 'opacity': 1 }, 750);
	}
	console.log(nivel)
	liftButton.removeAttr('style');
	$(liftButton[nivel]).css({ 'color': '#ffffff', 'border-color': '#ffffff', 'background-color': 'var(--secondColor)' });
}

function horiziontal(direccion) {
	nivel = (direccion) ? nivel - 1 : nivel + 1;
	if (nivel == -1) nivel = 0;
	if (nivel == 3) nivel = 2;
	$('body, html').animate({ 'scrollLeft': $(section[nivel]).offset().left }, 750);
}




$('body').on('click', '.folderIcon', function() {
	/*(!add ) ? 
		sys.selectOne($(this).parent()) : 
		sys.select($(this).parent()) ;*/

	if (add)
		sys.select($(this).parent())
	else {
		if (multiple) {
			var element = $('.element');
			sys.select($(this).parent());
			sys.pos.push($(this).parent().index('.element'));
			console.log(sys.pos)
			if (sys.pos.length > 1) {
				for (var i = sys.pos[0] + 1; i < sys.pos[sys.pos.length - 1]; i++) {
					console.log(i)
					sys.select($(element[i]));
				}
			}
		} else
			sys.selectOne($(this).parent());
	}
});

$(document).on('keyup keydown', function(e) {
	if (e.shiftKey) return;
	multiple = true;
});

$('body').on('dblclick', '.folderIcon', function() {
	if ($(this).parent().attr('id') == 'upLevel') return;
	var id = $(this).find('.name').html();
	console.log(id);
	sys.openFolder(id);
});

$('body').on('keyup keydown', '.element .name', function(e) {
	if (e.which !== 13) return;
	e.preventDefault();
	sys.exitChangeName($(this));
	sys.unselect();

});

$('body, main, header').click(function(e) {
	if (e.target !== this) return;
	sys.unselect();
	$('#settingMenu').css('display', 'none')
});

$('#añadir').click(function() {
	sys.newFolder();
});

$('#exit').click(function(e) { sys.exit(); })


$('#descargar').click(function(e) { sys.download(); });

$('#setting').click(function(e) {
	if ($('#settingMenu').css('display') == 'none')
		$('#settingMenu').css('display', 'block');
	else
		$('#settingMenu').css('display', 'none');
});




$('body').on('dblclick', '#upLevel .folderIcon', function() {
	sys.upLevel();
});

$('#lavel').on('dragover', function(e) {
	e.preventDefault();
	e.stopPropagation();
}).on('dragenter', function(e) {
	e.preventDefault();
	e.stopPropagation();
}).on('drop', function(e) {
	if (e.originalEvent.dataTransfer) {
		if (e.originalEvent.dataTransfer.files.length) {
			e.preventDefault();
			e.stopPropagation();
			var files = e.originalEvent.dataTransfer.files;
			sys.upLoadFiles(files)
		}
	}
});

$(':file').change(function() {
	var files = $(':file')[0].files
	sys.upLoadFiles(files)
})


$(document).keydown(function(e) {
	if (e.target.nodeName.toLowerCase() == 'input')
		return;
	var key = e.which;
	switch (key) {
		case 16: // sifth
			multiple = true;
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
		case 40: // abajo
		case 39: //drcha
			e.preventDefault();
			if (page)
				ascendente(false);
			else
				horiziontal(false);
			break;
		case 38: //arriba
		case 37: // izq
			e.preventDefault();
			if (page)
				ascendente(true);
			else
				horiziontal(true);
			break;
		default:
			console.log(e.which);
			break;
	}
}).keyup(function(e) {
	add = false;
	multiple = false;
});


/*
---------------------------------Personalización------------------------------------
*/
$('article').click(function() {
	var themes = $(this).attr('class'),
		firstColor, secondColor, thirdColor, circle;
	circle = $('.' + themes).find('circle');
	firstColor = $(circle[1]).css('fill');
	secondColor = $(circle[0]).css('fill');
	thirdColor = $(circle[2]).css('fill');
	document.documentElement.style.setProperty('--firstColor', firstColor);
	document.documentElement.style.setProperty('--secondColor', secondColor);
	document.documentElement.style.setProperty('--thirdColor', thirdColor);
	sys.changeTheme(themes);
});

$('#ancla').click(function(e) {
	$('html, body').animate({ 'scrollTop': 0 }, 2000);
	$('#ancla, #lift').animate({ 'opacity': 0 }, 2000);
});

$('#settingMenu li').click(function(e) {
	var index = $(this).index('#settingMenu li');
	$('html, body').animate({ 'scrollTop': $(section[index]).offset().top }, (index + 1) * 750);
	$('#settingMenu').css('display', 'none');
	$('#ancla, #lift').animate({ 'opacity': 1 }, (index + 1) * 750);
	liftButton.removeAttr('style')
	$(liftButton[index + 1]).css({ 'color': '#ffffff', 'border-color': '#ffffff', 'background-color': 'var(--secondColor)' }, (index + 1) * 750)
});

$('.liftButton').click(function() {
	var liftButton = $('.liftButton'),
		index = $(this).index('.liftButton');
	liftButton.removeAttr('style');
	$(liftButton[index]).css({ 'color': '#ffffff', 'border-color': '#ffffff', 'background-color': 'var(--secondColor)' }, (index + 1) * 750)
	if (index == 0)
		$('#ancla').click();
	else
		$('html, body').animate({ 'scrollTop': $(section[index - 1]).offset().top }, 750);
});

$('#home .forms').submit(function(e) {
	e.preventDefault();
	var obj = {},
		$this = $(this),
		input = $this.find('input');

	for (var i = 0; i < input.length-1; i++) {
		eval('obj.'+$(input[i]).attr('name') + ' = "'+$(input[i]).val() +'"')
	}
	console.log($this.find('form').attr('action'));
	$.post($this.find('form').attr('action'), obj, function(d){
		console.log(d)
		$this.append('<div>'+d+'</div>');

	})
	console.log(obj);

});




if (page) {
	$('#settingMenu').css({ 'top': $('header').offset().top + w * 0.06, 'left': $('#setting').offset().left - w * 0.15, 'display': 'none' });
	$('html, body').animate({ 'scrollTop': 0 }, 1);
}
