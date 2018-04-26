function geraEImprime(){
	function ordenaNumeros(a,b) {
	    return a - b;
	}
	
	var numeros = [];
	for (var i = 1; i <= 50; i++) {
		numeros.push(i);
	}
	numeros = shuffle(numeros);

	var numeros5 = [];
	for (var i = 0; i < 5; i++) {
		numeros5.push(numeros[i]);
	}
	numeros5.sort(ordenaNumeros);
	$.each( numeros5, function( index, value ){
	    $('#numeros-container .row div:nth-of-type('+( index + 1)+')').html("<span>" + value + "</span>");
	});


	var estrelas = [];
	for (var i = 1; i <= 12; i++) {
		estrelas.push(i);
	}
	estrelas = shuffle(estrelas);

	var estrelas2 = [];
	for (var i = 0; i < 2; i++) {
		estrelas2.push(estrelas[i]);
	}
	estrelas2.sort(ordenaNumeros);
	$.each( estrelas2, function( index, value ){
	    $('#estrelas-container .row div:nth-of-type('+( index + 1)+')').html("<span>" + value + "</span>");
	});
}

function shuffle(o) {
	for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
	return o;
};

geraEImprime();

$('.btn-gerador').click(function(){
	geraEImprime();
});

$('#select-data').change(function(){
	var dataSorteioSelect = $(this).val();
	var href = '?data=' + dataSorteioSelect;
	$('#btnConsultar').attr('href', href);
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
var search = getUrlParameter('data');

if (search != null){
    $("#select-data option[value='"+search+"']").attr("selected", "selected");
}

console.log('<?php $ocurNumeros; ?>');