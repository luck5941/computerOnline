var section = $('section'),
	links = $('.forms .links'),
	form = $('form'),
	sectionVP = location.hash.substring(1, location.hash.length),
	n = 1;

switch(sectionVP){
	case 'newUser':
		n=2;
		break;
	case 'forget':
		n=0;
		break;
	case 'login':
	default:
		n = 1;
		break;
}

$('html, body').animate({'scrollLeft': $(section[n]).offset().left},1);
$('.links[index=1]').css('text-decoration', 'underline');
$(links).click(function(e){
	var index = $(this).attr('index');
	$('html, body').animate({'scrollLeft': $(section[index]).offset().left},1500, 'swing');
	$(links).css('text-decoration', 'none');
	$('.links[index='+index+']').css('text-decoration', 'underline');

});

