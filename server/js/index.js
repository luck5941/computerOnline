var section = $('section'),
	links = $('.forms .links'),
	form = $('form'),
	sectionVP = location.hash.substring(1, location.hash.length),
	nivel = 1;

switch(sectionVP){
	case 'newUser':
		nivel=2;
		break;
	case 'forget':
		nivel=0;
		break;
	case 'login':
	default:
		nivel = 1;
		break;
}

//$('html, body').animate({'scrollLeft': $(section[nivel]).offset().left},1);
$('html, body').scrollLeft($(section[nivel]).offset().left);
$('.links[index='+nivel+']').css('text-decoration', 'underline');
$(links).click(function(e){
	let index = $(this).attr('index');
    let t = (MOBILE) ? 500 : 750;
	$('html, body').animate({'scrollLeft': $(section[index]).offset().left},t);
    if (MOBILE) $('html, body').scrollTop(0);
	$(links).css('text-decoration', 'none');
	$('.links[index='+index+']').css('text-decoration', 'underline');
    hash = (index == '0') ? 'forget' : (index == '1') ? 'login' : 'newUser';
    location.hash = hash;

});

