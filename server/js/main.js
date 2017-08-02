var w = window.innerWidth
h = window.innerHeight,
	mainColor = '#00edff20',
	add = false,
	multiple = false,
	enviar = true,
	page = (location.href.search('home') !== -1),
	searchClick = true,
	section = $('section'),
	liftButton = $('.liftButton');
if (page) var nivel = 0;


function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

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
			console.log(d)
			$('main').html(d);
		});
	}

	this.exit = function() {
		$("[value='exit']").click();
	}
	this.download = function(name = true) {
		console.log('poep')
		if (name === true) {
			var names = [];
			for (var i = 0; i < this.selected.length; i++) {
				names.push($('#' + this.selected[i]).find('.name').html());
			}
		}
		else
			var names = [name];
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

		upload.uploadFile(data);
		/*
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
		});
		*/
	}

	this.changeTheme = function(theme) {
		$.post('server/php/proccess.php', { 'function': 'changeTheme', 'theme': theme }, function(d) { console.log(d) });
	}

	this.load();
}

function UPLOAD() {
	this.uploadFile = function(d) {
		$('#progress').css('display', 'block');
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress", this.uploadProgress, false);
		xhr.addEventListener("load", this.uploadComplete, false);
		xhr.addEventListener("error", this.uploadFailed, false);
		xhr.addEventListener("abort", this.uploadCanceled, false);
		xhr.open("POST", "server/php/proccess.php");
		xhr.send(d);
	}

	this.uploadProgress = function(e) {
		if (e.lengthComputable) {
			console.log(e);
			var percentComplete = Math.round(e.loaded * 100 / e.total),
				percent = (parseInt($('#progressBarCont').attr('width')) - 2) * percentComplete / 100;
			$('#progressBar').attr({'width': (parseInt($('#progressBarCont').attr('width'))-2-percent), 'x': (8+percent)});


		} else {
			console.log('unable to compute');
		}
	}
	this.uploadComplete = function(evt) {
		/* This event is raised when the server send back a response*/
		$('main').append('evt.target.responseText' + evt.target.responseText);
		$('#progress').css('display', 'none');
	}

	this.uploadFailed = function(evt) {
		console.log("There was an error attempting to upload the file.");
	}

	this.uploadCanceled = function(evt) {
		console.log("The upload has been canceled by the user or the browser dropped the connection.");
	}

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
var upload = new UPLOAD();

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
	switch (nivel) {
		case 0:
			var hash = 'forget';
			break;
		case 1:
			var hash = 'login';
			break;
		case 2:
			var hash = 'newUser';
			break;

	}
	location.hash = hash;
}




$('body').on('click', '.folderIcon', function() {
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

$('body').on('dblclick', '', function() {
	sys.selectOne($(this));
});





$(document).on('keyup keydown', function(e) {
	if (e.shiftKey) return;
	multiple = true;
});

$('body').on('dblclick', '.folderIcon, .list', function(e) {
	if ($(this).parent().attr('id') == 'upLevel') return;
	var her = $(this);
	console.log(e.currentTarget.className)
	var id = (e.currentTarget.className == 'folderIcon') ? $(this).find('.name').html() : $(this).find('.path').html();
	console.log(id);
	return ($(this).find('img').attr('src').indexOf('folder') !== -1 /*|| (e.currentTarget.className !== 'folderIcon')*/) ? sys.openFolder(id) : (function(){
		let a = her.find('.path').html(),
			b = her.find('.name').html();
			console.log(a+'/'+b)
			sys.download(a+'/'+b);
	}());
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

$('#exit').click(function(e) { sys.exit(); });

$('#search').click(async function(e) {
	if (e.target.nodeName.toLowerCase() !== 'circle') return;
	searchClick = false;
	$('#section').animate({ 'height': '80vh', 'margin-top': '15vh' }, 1000);
	console.log('entra')
	$('#search *').children().animate({ 'opacity': 0 }, 500);
	await sleep(500)
	$('#search svg').css('display', 'none');
	$('#search').css({ 'opacity': 0, 'height': '4vw' });
	$('#search').animate({ 'opacity': 1 }, 400);
	await sleep(400)
	$('#search').animate({ 'width': '20%' }, 500);
	$('#search').append('<input id="searching" placeholder="search" autofocus=true></input>')
		// $('#searching').attr('contenteditable', 'true').html('Search').removeAttr('style').css('font-size', '25pt');
	$('#searching').removeAttr('style').css('font-size', '25pt').focus();

});


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
	console.log(e.target.isContentEditable) 
	if (e.target.nodeName.toLowerCase() == 'input' || e.target.isContentEditable)
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

	for (var i = 0; i < input.length - 1; i++) {
		eval('obj.' + $(input[i]).attr('name') + ' = "' + $(input[i]).val() + '"')
	}
	console.log($this.find('form').attr('action'));
	$.post($this.find('form').attr('action'), obj, function(d) {
		console.log(d)
		$this.append('<div>' + d + '</div>');

	})

});

$('#home').on('input', '#searching', function(e) {
	var val = this.value;
	if ($('#lavel').html().search('contSearch') == -1)
		$('#lavel').append('<div id="contSearch"></div></div>')
	if (enviar)
		$.post('server/php/proccess.php', { 'function': 'search', 'val': val })
		.done(function(d) {
			$('#contSearch').html(d);
			enviar = true;
		})
		.fail(function(xhr, status, error) {
			enviar = true;
			console.log(xhr)
				/*console.log(status)
				console.log(error)*/
		});
	enviar = false;
}).on('keydown', '#searching', async function(e) {
	if (e.which !== 27) return;
	$(this).css('display', 'none');
	$(this).parent().removeAttr('style').children('svg').css('display', 'block').animate({ 'opacity': 1 }, 200).children().animate({ 'opacity': 1 }, 750);
	$(this).remove();
	$('#section').animate({ 'height': '100vh', 'margin-top': '0' }, 750);
	await sleep(750)
	$('#section').removeAttr();
});





if (page) {
	$('#settingMenu').css({ 'top': $('header').offset().top + w * 0.06, 'left': $('#setting').offset().left - w * 0.15, 'display': 'none' });
	$('html, body').animate({ 'scrollTop': 0 }, 1);
}
