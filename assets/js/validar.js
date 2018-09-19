// Acepta NIEs (Extranjeros con X, Y o Z al principio)
function validadDNI(dni) {
    var numero, let, letra;
    var expresion_regular_dni = /^[XYZ]?\d{5,8}[A-Z]$/;

    dni = dni.toUpperCase();

    if(expresion_regular_dni.test(dni) === true){
        numero = dni.substr(0,dni.length-1);
        numero = numero.replace('X', 0);
        numero = numero.replace('Y', 1);
        numero = numero.replace('Z', 2);
        let = dni.substr(dni.length-1, 1);
        numero = numero % 23;
        letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
        letra = letra.substring(numero, numero+1);
        if (letra != let) {
            //alert('Dni erroneo, la letra del NIF no se corresponde');
            return false;
        }else{
            //alert('Dni correcto');
            return true;
        }
    }else{
        //alert('Dni erroneo, formato no válido');
        return false;
    }
}

// validar cif

function validarCIF(cif) {
    if (!cif || cif.length !== 9) {
        return false;
    }

    var letters = ['J', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
    var digits = cif.substr(1, cif.length - 2);
    var letter = cif.substr(0, 1);
    var control = cif.substr(cif.length - 1);
    var sum = 0;
    var i;
    var digit;

    if (!letter.match(/[A-Z]/)) {
        return false;
    }

    for (i = 0; i < digits.length; ++i) {
        digit = parseInt(digits[i]);

        if (isNaN(digit)) {
            return false;
        }

        if (i % 2 === 0) {
            digit *= 2;
            if (digit > 9) {
                digit = parseInt(digit / 10) + (digit % 10);
            }

            sum += digit;
        } else {
            sum += digit;
        }
    }

    sum %= 10;
    if (sum !== 0) {
        digit = 10 - sum;
    } else {
        digit = sum;
    }

    if (letter.match(/[ABEH]/)) {
        return String(digit) === control;
    }
    if (letter.match(/[NPQRSW]/)) {
        return letters[digit] === control;
    }

    return String(digit) === control || letters[digit] === control;
}

function checkCif() {
    var cifs = document.getElementById('cif').value.split(',');
    var message = document.getElementsByClassName('message')[0];

    for (var i = 0; i < cifs.length; ++i) {
        if (isValidCif(cifs[i].trim())) {
            message.innerHTML += '<br />CIF ' + cifs[i] + ' válido';
        } else {
            message.innerHTML += '<br />CIF ' + cifs[i] + ' no válido';
        }
    }
}


//--------------------------------------------------------
// validar iban

function validarIBAN(IBAN) {

    //Se pasa a Mayusculas
    IBAN = IBAN.toUpperCase();
    //Se quita los blancos de principio y final.
    IBAN = IBAN.trim();
    IBAN = IBAN.replace(/\s/g, ""); //Y se quita los espacios en blanco dentro de la cadena

    var letra1,letra2,num1,num2;
    var isbanaux;
    var numeroSustitucion;
    //La longitud debe ser siempre de 24 caracteres
    if (IBAN.length != 24) {
        return false;
    }

    // Se coge las primeras dos letras y se pasan a números
    letra1 = IBAN.substring(0, 1);
    letra2 = IBAN.substring(1, 2);
    num1 = getnumIBAN(letra1);
    num2 = getnumIBAN(letra2);
    //Se sustituye las letras por números.
    isbanaux = String(num1) + String(num2) + IBAN.substring(2);
    // Se mueve los 6 primeros caracteres al final de la cadena.
    isbanaux = isbanaux.substring(6) + isbanaux.substring(0,6);

    //Se calcula el resto, llamando a la función modulo97, definida más abajo
    resto = modulo97(isbanaux);
    if (resto == 1){
        return true;
    }else{
        return false;
    }
}

function modulo97(iban) {
    var parts = Math.ceil(iban.length/7);
    var remainer = "";

    for (var i = 1; i <= parts; i++) {
        remainer = String(parseFloat(remainer+iban.substr((i-1)*7, 7))%97);
    }

    return remainer;
}

function getnumIBAN(letra) {
    ls_letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return ls_letras.search(letra) + 10;
}


//---------------------------------------------- calcular dc cuenta bancaria

function calculaDC(entidad, oficina,cuenta) {
    var pesos = new Array();
    pesos[0] = 6;
    pesos[1] = 3;
    pesos[2] = 7;
    pesos[3] = 9;
    pesos[4] = 10;
    pesos[5] = 5;
    pesos[6] = 8;
    pesos[7] = 4;
    pesos[8] = 2;
    pesos[9] = 1;

    entidadInv = inv(entidad);
    oficinaInv = inv(oficina);
    cuentaInv = inv(cuenta);

    ofient = "" + oficinaInv + entidadInv
    suma =0;
    for(a=0;a<8;a++)
        suma += ofient.substr(a,1)*pesos[a];

    sumaCCC =0;
    for(a=0;a<10;a++)
        sumaCCC += cuentaInv.substr(a,1)*pesos[a];

    dc = 11 - (suma % 11)
    dcCcc = 11 - (sumaCCC % 11)

    if (dc == 10) dc = 1;
    if (dc == 11) dc = 0;
    if (dcCcc == 10) dcCcc = 1;
    if (dcCcc == 11) dcCcc = 0;

    return ""+dc + dc_CCC;
}

function inv(item) {
    var ret = "";

    for (a=0;a<=item.length;a++)
        ret = ret + item.substr(item.length-a,1);

    return ret
}

//-------------------------------------------------------