var xmlhttp = new XMLHttpRequest();
var url = "json/json.php";
var myArr;

xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

        myArr = JSON.parse(this.responseText);
        mostrarPreguntas();

    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

//Funcion para el select de paises
function comunidades() {
    var paises = document.getElementById("paises");
    var ccaa = document.getElementById("preguntaccaa");
    if (paises.value == "73") {
        ccaa.setAttribute("class", "visible");
    } else {
        ccaa.setAttribute("class", "oculto");
   
    }
}



function mostrarPreguntas() {
    console.log(" TAMAÑO ARRAY PREGUNTAS: " + myArr.length);

    var demoP = document.getElementById("pruebas");

    var contador = 0;
    for (i = 0; i < myArr.length; i++) {

        contador++;


        var fila = myArr[i];
        console.log(" FILA " + contador);
        if (fila.disponible == "1") {




            var preguntas = document.getElementById("preguntas");
            var formulario = document.getElementById("formulario");
            var div = document.createElement("div");
            var newLabel = document.createElement("Label");
            var textoP = document.createTextNode(fila.pregunta);
            var dependencia = fila.idRespuestaDepende;
            var sub1 = document.getElementById("sub1");
           
            preguntas.appendChild(formulario);
            formulario.insertBefore(div,sub1);
           // formulario.appendChild(div);
            div.appendChild(newLabel);
            newLabel.appendChild(textoP);
            div.setAttribute("id", "pregunta" + fila.idPregunta);

            if (fila.tipo == "range") {

                var imagen = document.createElement("img");
                            
                div.appendChild(imagen);
                    
                imagen.setAttribute("src","img/"+myArr[i].imagen+".png");
            }

            var arrayRespuestas = fila.respuesta;
            if (dependencia.length != 0 || fila.idPregunta == "ccaa") {
                //borra la linea 74 entera

                div.classList.add("oculto");
                // BUCLE FOR PARA AÑADIR UNA CLASE DEPENDIENTEX

                for (k = 0; k < dependencia.length; k++) {

                    div.classList.add("dependiente" + dependencia[k]);
                }
            } else {
                div.classList.add("visible");
            }


            console.log(" TAMAÑO ARRAY RESPUESTAS " + fila.idPregunta + ":" + arrayRespuestas.length);
            console.log(" TIPO " + fila.idPregunta + ":" + myArr[i].tipo);
            var existe = false;
            for (j = 0; j < arrayRespuestas.length; j++) {

                if (fila.tipo == "checkbox" || fila.tipo == "radio" ||
                    fila.tipo == "range") {
                    var input = document.createElement('input');
                    if (fila.tipo == "checkbox") {
                        input.type = "checkbox";
                        input.name = fila.idPregunta;
                        input.setAttribute("value",
                            arrayRespuestas[j].idRespuesta);
                        input.setAttribute("id", "respuesta" +
                            arrayRespuestas[j].idRespuesta);
                    } else if (fila.tipo == "radio") {
                        input.type = "radio";
                        input.name = fila.idPregunta;
                        input.onclick = function() {
                            mostrarOcultos(this)
                        };
                        input.setAttribute("value",
                            arrayRespuestas[j].idRespuesta);
                        input.setAttribute("id", "respuesta" +
                            arrayRespuestas[j].idRespuesta);
                    } else if (fila.tipo == "range") {
                        input.type = "radio";
                        input.name = fila.idPregunta;
						div.classList.add("range");
                       
                        input.setAttribute("value",
                            arrayRespuestas[j].idRespuesta);
                        input.setAttribute("id", "respuesta" +
                            arrayRespuestas[j].idRespuesta);

                            
                    }

                   
                    var newLabel2 = document.createElement("Label");
                    var textoR = document
                        .createTextNode(arrayRespuestas[j].respuesta);
                 
                    div.appendChild(input);
                    div.appendChild(newLabel2);
                    newLabel2.setAttribute("for","respuesta"+arrayRespuestas[j].idRespuesta);
                    newLabel2.appendChild(textoR);


                 
                } else { // tipo select
                    if (!existe) {

                        if (fila.idPregunta == "paises") {
                            var select = document.createElement('select');
                            select.setAttribute("id", "paises");
                            select.setAttribute("name", "select" + fila.idPregunta);
                            select.onchange = function() {
                                comunidades()
                            };
                            select.options
                                .add(new Option("Seleccione",null));
                            div.appendChild(select);
                        } else {
                            var select = document.createElement('select');
                            select.setAttribute("name", "select" + fila.idPregunta);
                            select.options
                                .add(new Option("Seleccione",null));
                            div.appendChild(select);
                        }



                    }
                    select.setAttribute("name", "select" + fila.idPregunta);
                    select.options.add(new Option(arrayRespuestas[j].respuesta, arrayRespuestas[j].idRespuesta));

                    div.appendChild(select);
                    existe = true;
                }

            }

        }
    }
}

function mostrarOcultos(elem) {
    var valor = elem.getAttribute("value");

    // Oculto y quito los seleccionados de los hermanos
    var padre = elem.parentNode;
    ocultarOtros(padre, valor);

    // Muestro los ocultos cambiando la clase de los dependientes
    var dependiente = "dependiente" + valor;
    var arrDependeClase = document.getElementsByClassName(dependiente);

    for (a = 0; a < arrDependeClase.length; a++) {

        arrDependeClase[a].classList.add("visible");
        arrDependeClase[a].classList.remove("oculto");
    }

}

function ocultarOtros(elemento, valor) {

    var lista = elemento.getElementsByTagName("input");

    // alert(lista.length);

    for (i = 0; i < lista.length; i++) {
        idActual = lista[i].value;
        if (valor != idActual) {
            deseleccionarOcultar(idActual);
        }
    }

}




function deseleccionarOcultar(identificador) {
    var dependiente = "dependiente" + identificador;
    var arrDependeClase = document.getElementsByClassName(dependiente);

    for (a = 0; a < arrDependeClase.length; a++) {
        var clases = arrDependeClase[a].classList;
        var borrar = true;
        if (clases.length > 2) { // Si tiene más de una clase es que tiene más de una dependencia
            for (j = 0; j < clases.length; j++) {
                if ((clases[j] != "visible") && (clases[j] != "oculto") && (clases[j] != "dependiente" + identificador)) {
                    var idRespuestaComprobar = clases[j].substring(11, 15);
                    var idComprobar = "respuesta" + idRespuestaComprobar;
                    var comprobar = document.getElementById(idComprobar);
                    if (comprobar.checked == true) { // Hay otra pregunta de la que es dependiente y tenemos que parar el borrado
                        borrar = false;
                    }

                }
            }
        }
        if (borrar) {
            // Quitamos las opciones si se hubieran seleccionado
            var listaOpciones = arrDependeClase[a].getElementsByTagName("input");

            for (t = 0; t < listaOpciones.length; t++) {
                listaOpciones[t].checked = false;
            }

            // Ocultamos la capa de la pregunta
            arrDependeClase[a].classList.remove("visible");
            arrDependeClase[a].classList.add("oculto");
        }

    }

}