function md5cycle(x, k) {
    var a = x[0], b = x[1], c = x[2], d = x[3];

    a = ff(a, b, c, d, k[0], 7, -680876936);
    d = ff(d, a, b, c, k[1], 12, -389564586);
    c = ff(c, d, a, b, k[2], 17,  606105819);
    b = ff(b, c, d, a, k[3], 22, -1044525330);
    a = ff(a, b, c, d, k[4], 7, -176418897);
    d = ff(d, a, b, c, k[5], 12,  1200080426);
    c = ff(c, d, a, b, k[6], 17, -1473231341);
    b = ff(b, c, d, a, k[7], 22, -45705983);
    a = ff(a, b, c, d, k[8], 7,  1770035416);
    d = ff(d, a, b, c, k[9], 12, -1958414417);
    c = ff(c, d, a, b, k[10], 17, -42063);
    b = ff(b, c, d, a, k[11], 22, -1990404162);
    a = ff(a, b, c, d, k[12], 7,  1804603682);
    d = ff(d, a, b, c, k[13], 12, -40341101);
    c = ff(c, d, a, b, k[14], 17, -1502002290);
    b = ff(b, c, d, a, k[15], 22,  1236535329);

    a = gg(a, b, c, d, k[1], 5, -165796510);
    d = gg(d, a, b, c, k[6], 9, -1069501632);
    c = gg(c, d, a, b, k[11], 14,  643717713);
    b = gg(b, c, d, a, k[0], 20, -373897302);
    a = gg(a, b, c, d, k[5], 5, -701558691);
    d = gg(d, a, b, c, k[10], 9,  38016083);
    c = gg(c, d, a, b, k[15], 14, -660478335);
    b = gg(b, c, d, a, k[4], 20, -405537848);
    a = gg(a, b, c, d, k[9], 5,  568446438);
    d = gg(d, a, b, c, k[14], 9, -1019803690);
    c = gg(c, d, a, b, k[3], 14, -187363961);
    b = gg(b, c, d, a, k[8], 20,  1163531501);
    a = gg(a, b, c, d, k[13], 5, -1444681467);
    d = gg(d, a, b, c, k[2], 9, -51403784);
    c = gg(c, d, a, b, k[7], 14,  1735328473);
    b = gg(b, c, d, a, k[12], 20, -1926607734);

    a = hh(a, b, c, d, k[5], 4, -378558);
    d = hh(d, a, b, c, k[8], 11, -2022574463);
    c = hh(c, d, a, b, k[11], 16,  1839030562);
    b = hh(b, c, d, a, k[14], 23, -35309556);
    a = hh(a, b, c, d, k[1], 4, -1530992060);
    d = hh(d, a, b, c, k[4], 11,  1272893353);
    c = hh(c, d, a, b, k[7], 16, -155497632);
    b = hh(b, c, d, a, k[10], 23, -1094730640);
    a = hh(a, b, c, d, k[13], 4,  681279174);
    d = hh(d, a, b, c, k[0], 11, -358537222);
    c = hh(c, d, a, b, k[3], 16, -722521979);
    b = hh(b, c, d, a, k[6], 23,  76029189);
    a = hh(a, b, c, d, k[9], 4, -640364487);
    d = hh(d, a, b, c, k[12], 11, -421815835);
    c = hh(c, d, a, b, k[15], 16,  530742520);
    b = hh(b, c, d, a, k[2], 23, -995338651);

    a = ii(a, b, c, d, k[0], 6, -198630844);
    d = ii(d, a, b, c, k[7], 10,  1126891415);
    c = ii(c, d, a, b, k[14], 15, -1416354905);
    b = ii(b, c, d, a, k[5], 21, -57434055);
    a = ii(a, b, c, d, k[12], 6,  1700485571);
    d = ii(d, a, b, c, k[3], 10, -1894986606);
    c = ii(c, d, a, b, k[10], 15, -1051523);
    b = ii(b, c, d, a, k[1], 21, -2054922799);
    a = ii(a, b, c, d, k[8], 6,  1873313359);
    d = ii(d, a, b, c, k[15], 10, -30611744);
    c = ii(c, d, a, b, k[6], 15, -1560198380);
    b = ii(b, c, d, a, k[13], 21,  1309151649);
    a = ii(a, b, c, d, k[4], 6, -145523070);
    d = ii(d, a, b, c, k[11], 10, -1120210379);
    c = ii(c, d, a, b, k[2], 15,  718787259);
    b = ii(b, c, d, a, k[9], 21, -343485551);

    x[0] = add32(a, x[0]);
    x[1] = add32(b, x[1]);
    x[2] = add32(c, x[2]);
    x[3] = add32(d, x[3]);

}

function cmn(q, a, b, x, s, t) {
    a = add32(add32(a, q), add32(x, t));
    return add32((a << s) | (a >>> (32 - s)), b);
}

function ff(a, b, c, d, x, s, t) {
    return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function gg(a, b, c, d, x, s, t) {
    return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function hh(a, b, c, d, x, s, t) {
    return cmn(b ^ c ^ d, a, b, x, s, t);
}

function ii(a, b, c, d, x, s, t) {
    return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

function md51(s) {
    txt = '';
    var n = s.length,
        state = [1732584193, -271733879, -1732584194, 271733878], i;
    for (i=64; i<=s.length; i+=64) {
        md5cycle(state, md5blk(s.substring(i-64, i)));
    }
    s = s.substring(i-64);
    var tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
    for (i=0; i<s.length; i++)
        tail[i>>2] |= s.charCodeAt(i) << ((i%4) << 3);
    tail[i>>2] |= 0x80 << ((i%4) << 3);
    if (i > 55) {
        md5cycle(state, tail);
        for (i=0; i<16; i++) tail[i] = 0;
    }
    tail[14] = n*8;
    md5cycle(state, tail);
    return state;
}

/* there needs to be support for Unicode here,
 * unless we pretend that we can redefine the MD-5
 * algorithm for multi-byte characters (perhaps
 * by adding every four 16-bit characters and
 * shortening the sum to 32 bits). Otherwise
 * I suggest performing MD-5 as if every character
 * was two bytes--e.g., 0040 0025 = @%--but then
 * how will an ordinary MD-5 sum be matched?
 * There is no way to standardize text to something
 * like UTF-8 before transformation; speed cost is
 * utterly prohibitive. The JavaScript standard
 * itself needs to look at this: it should start
 * providing access to strings as preformed UTF-8
 * 8-bit unsigned value arrays.
 */
function md5blk(s) { /* I figured global was faster.   */
    var md5blks = [], i; /* Andy King said do it this way. */
    for (i=0; i<64; i+=4) {
        md5blks[i>>2] = s.charCodeAt(i)
            + (s.charCodeAt(i+1) << 8)
            + (s.charCodeAt(i+2) << 16)
            + (s.charCodeAt(i+3) << 24);
    }
    return md5blks;
}

var hex_chr = '0123456789abcdef'.split('');

function rhex(n)
{
    var s='', j=0;
    for(; j<4; j++)
        s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
            + hex_chr[(n >> (j * 8)) & 0x0F];
    return s;
}

function hex(x) {
    for (var i=0; i<x.length; i++)
        x[i] = rhex(x[i]);
    return x.join('');
}

function md5(s) {
    return hex(md51(s));
}

/* this function is much faster,
so if possible we use it. Some IEs
are the only ones I know of that
need the idiotic second function,
generated by an if clause.  */

function add32(a, b) {
    return (a + b) & 0xFFFFFFFF;
}

if (md5('hello') != '5d41402abc4b2a76b9719d911017c592') {
    function add32(x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }
}


// confirmar operaciones

function confirmar(text){

    return confirm(text);

}

function fn_ValidateIBAN(IBAN) {

    //Se pasa a Mayusculas
    IBAN = IBAN.toUpperCase();
    //Se quita los blancos de principio y final.
    IBAN = trim(IBAN);
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

function rellenarBic(valor, objeto)
{

    //alert(valor);
    var subcode = valor.charAt(4) + valor.charAt(5) +valor.charAt(6) + valor.charAt(7);

    //alert(subcode);
    var tabla = new Object(); // or just {}
    tabla['0156']='ABNAESMMXXX';
    tabla['3524']='AHCFESMMXXX';
    tabla['0188']='ALCLESMMXXX';
    tabla['0136']='AREBESMMXXX';
    tabla['0078']='BAPUES22XXX';
    tabla['0065']='BARCESMMXXX';
    tabla['2095']='BASKES2BXXX';
    tabla['0190']='BBPIESMMXXX';
    tabla['0168']='BBRUESMXXXX';
    tabla['0182']='BBVAESMMXXX';
    tabla['3081']='BCOEESMM081';
    tabla['0198']='BCOEESMMXXX';
    tabla['0131']='BESMESMMXXX';
    tabla['0488']='BFASESMMXXX';
    tabla['0186']='BFIVESBBXXX';
    tabla['0128']='BKBKESMMXXX';
    tabla['0138']='BKOAES22XXX';
    tabla['0061']='BMARES2MXXX';
    tabla['0219']='BMCEESMMXXX';
    tabla['0149']='BNPAESMHXXX';
    tabla['0058']='BNPAESMZXXX';
    tabla['0160']='BOTKESMXXXX';
    tabla['0152']='BPLCESMMXXX';
    tabla['0155']='BRASESMMXXX';
    tabla['0081']='BSABESBBXXX';
    tabla['0049']='BSCHESMMXXX';
    tabla['0154']='BSUIESMMXXX';
    tabla['0094']='BVALESMMXXX';
    tabla['2080']='CAGLESMMVIG';
    tabla['2038']='CAHMESMMXXX';
    tabla['2100']='CAIXESBBXXX';
    tabla['3604']='CAPIESMMXXX';
    tabla['3183']='CASDESBBXXX';
    tabla['2085']='CAZRES2ZXXX';
    tabla['0234']='CCOCESMMXXX';
    tabla['3058']='CCRIES2AXXX';
    tabla['3025']='CDENESBBXXX';
    tabla['2045']='CECAESMM045';
    tabla['2048']='CECAESMM048';
    tabla['2056']='CECAESMM056';
    tabla['2086']='CECAESMM086';
    tabla['2000']='CECAESMMXXX';
    tabla['2013']='CESCESBBXXX';
    tabla['0130']='CGDIESMMXXX';
    tabla['0122']='CITIES2XXXX';
    tabla['1474']='CITIESMXSEC';
    tabla['3035']='CLPEES2MXXX';
    tabla['0159']='COBAESMXTMA';
    tabla['1460']='CRESESMMXXX';
    tabla['2108']='CSPAES2L108';
    tabla['3656']='CSSOES2SFI';
    tabla['0237']='CSURES2CXXX';
    tabla['0019']='DEUTESBBASS';
    tabla['0231']='DSBLESMMXXX';
    tabla['1467']='EHYPESMXXXX';
    tabla['9000']='ESPBESMMXXX';
    tabla['1497']='ESSIESMMXXX';
    tabla['0031']='ETCHES2GXXX';
    tabla['0046']='GALEES2GXXX';
    tabla['0487']='GBMNESMMXXX';
    tabla['0167']='GEBAESMMBIL';
    tabla['3682']='GVCBESBBETB';
    tabla['9096']='IBRCESMMXXX';
    tabla['1000']='ICROESMMXXX';
    tabla['1465']='INGDESMMXXX';
    tabla['3575']='INSGESMMXXX';
    tabla['0232']='INVLESMMXXX';
    tabla['9020']='IPAYESMMXXX';
    tabla['3669']='IVALESMMXXX';
    tabla['3641']='LISEESMMXXX';
    tabla['0236']='LOYIESMMXXX';
    tabla['0059']='MADRESMMXXX';
    tabla['9094']='MEFFESBBXXX';
    tabla['0162']='MIDLESMXXXX';
    tabla['3563']='MISVESMMXXX';
    tabla['3661']='MLCEESMMXXX';
    tabla['0169']='NACNESMMXXX';
    tabla['1479']='NATXESMMXXX';
    tabla['0144']='PARBESMHXXX';
    tabla['0216']='POHIESMMXXX';
    tabla['0233']='POPIESMMXXX';
    tabla['0229']='POPLESMMXXX';
    tabla['0075']='POPUESMMXXX';
    tabla['1459']='PRABESMMXXX';
    tabla['0211']='PROAESMMXXX';
    tabla['0083']='RENBESMMXXX';
    tabla['3501']='RENTESMMXXX';
    tabla['0108']='SOGEESMMAGM';
    tabla['1524']='UBIBESMMXXX';
    tabla['0226']='UBSWESMMNPB';
    tabla['2103']='UCJAES2MXXX';
    tabla['0196']='WELAESMMFU';
    tabla['9091']='XBCNESBBXXX';
    tabla['9092']='XRBVES2BXXX';
    tabla['9093']='XRVVESVVXXX';
    tabla['0239']='EVOBESMMXXX';
    tabla['0049']='BSCHESMMXXX';
    tabla['0030']='ESPCESMMXXX';


    if (tabla.hasOwnProperty(subcode)) {
        document.getElementById(objeto).value=tabla[subcode];
    }
    else{
        if(valor=="")
            document.getElementById(objeto).value="";
        else
            document.getElementById(objeto).value="";
    }
}