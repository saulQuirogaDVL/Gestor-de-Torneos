var cardBox = 1;
var card = 4;
var tupak = 4;
function crearCard(){
	if(card == 8){
		tupak = 8;
	}
	for (var i = 0; i < tupak ; i++) {
		if(card<16){
		card = card + 1;
		if(card%6 == 0){
			cardBox = cardBox+1;

			var cajaEspa = document.createElement('div');
			cajaEspa.setAttribute("class","w-100");
			document.getElementById("card-package").appendChild(cajaEspa);

			var cajaCart = document.createElement('div');
			cajaCart.setAttribute("id","card-box"+cardBox);
			cajaCart.setAttribute("class","row padding_cards");
			document.getElementById("card-package").appendChild(cajaCart);

			}
			var caja = document.createElement('div');
			caja.setAttribute("id","card"+card);
			caja.setAttribute("class","col-xl-2");
			var subcaja = document.createElement('div');
			/*+" padding_cards"*/
			subcaja.setAttribute("class","card cards_design_creacion");
			var bodycaja = document.createElement('div');
			bodycaja.setAttribute("class","card-body");
			var equipoNom = document.createElement('h5');
			var equipoText = document.createTextNode('Nombre del Equipo '+card+':');
			equipoNom.setAttribute("class","card-title");
			equipoNom.appendChild(equipoText);
			var equipoInput = document.createElement('input');
			equipoInput.setAttribute("class","redisenio-imput-card");
			equipoInput.setAttribute("tipe","text");
			equipoInput.setAttribute("name","nombreEquipo"+card);
			//equipoInput.setAttribute("value","{{ old('nombreEquipo"+card+"') }}");
			var integrantes = document.createElement('p');
			var integrantesText = document.createTextNode('Integrantes:');
			integrantes.setAttribute("class","card-text text-margin-redisenio");
			integrantes.appendChild(integrantesText);
			var input1 = document.createElement('input');
			input1.setAttribute("tipe","text");
			input1.setAttribute("name","name"+card+"_1");
			input1.setAttribute("class","padding_input");
			//input1.setAttribute("value","{{ old('name"+card+"_1"+"') }}");
			var input2 = document.createElement('input');
			input2.setAttribute("tipe","text");
			input2.setAttribute("name","name"+card+"_2");
			input2.setAttribute("class","padding_input");
			var input3 = document.createElement('input');
			input3.setAttribute("tipe","text");
			input3.setAttribute("name","name"+card+"_3");
			input3.setAttribute("class","padding_input");
			var input4 = document.createElement('input');
			input4.setAttribute("tipe","text");
			input4.setAttribute("name","name"+card+"_4");
			input4.setAttribute("class","padding_input");
			var input5 = document.createElement('input');
			input5.setAttribute("tipe","text");
			input5.setAttribute("name","name"+card+"_5");
			input5.setAttribute("class","padding_input");

			bodycaja.appendChild(equipoNom);
			bodycaja.appendChild(equipoInput);
			bodycaja.appendChild(integrantes);
			bodycaja.appendChild(input1);
			bodycaja.appendChild(input2);
			bodycaja.appendChild(input3);
			bodycaja.appendChild(input4);
			bodycaja.appendChild(input5);
			subcaja.appendChild(bodycaja);
			caja.appendChild(subcaja);

			var plus = document.getElementById("cardPlus");
			var clon = plus.cloneNode(true);
			if(card%6 == 0){
				document.getElementById("card-box"+(cardBox-1)).removeChild(plus);
				document.getElementById("card-box"+(cardBox-1)).appendChild(caja);
			}
			else{
				document.getElementById("card-box"+cardBox).removeChild(plus);
				document.getElementById("card-box"+cardBox).appendChild(caja);
			}
			document.getElementById("card-box"+cardBox).appendChild(clon);
		}
	}
}
var carnicero;
function eliminarCard(){
	if(card == 16){
		tupak = 8;
	}if(card == 9){
		tupak = 4;
	}
	for (var i = 0; i < tupak ; i++) {
		if(card>2){
			if(card%6 == 0){
				var elimiCaja = document.getElementById("card-box"+cardBox);
				var plus = document.getElementById("cardPlus");
				var clon = plus.cloneNode(true);
				document.getElementById("card-package").removeChild(elimiCaja);
				cardBox=cardBox-1;
				document.getElementById("card-box"+cardBox).appendChild(clon);
			}
			var elimiCard = document.getElementById("card"+card);
			document.getElementById("card-box"+cardBox).removeChild(elimiCard);
			card=card-1;
		}
	}
}

function Confirmar(){
	var res = confirm("Estas seguro de iniciar el torneo no podras volver a editar los equipos revisa bien antes de inicar!!!");
	if(res == true){
		return true;
	}
	else{
		return false;
	}
}
