const {Form} = require('./Form')

export function install(Vue, options) {
    Vue.mixin({
        data: function () {
            let params = {}
            if (!('form' in this)) params.form = new Form
            if (!('loading' in this)) params.loading = false
            if (!('money_config' in this)) params.money_config = {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                precision: 2,
            }
            if (!('dollar_config' in this)) params.dollar_config = {
                decimal: ',',
                thousands: '.',
                prefix: 'US$ ',
                precision: 2,
            }
            if (!('money_config_high_precision' in this)) params.money_config_high_precision = {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                precision: 6,
            }
            if (!('percent_config' in this)) params.percent_config = {
                decimal: ',',
                thousands: '.',
                prefix: '',
                suffix: ' %',
                precision: 2,
            }
            return params
        }
    })

    Vue.prototype.getUrlSegment = (numberSegment) => {
        let path = window.location.pathname;
        let segments = path.split("/");
        return segments[numberSegment];
    }

    Vue.prototype.getUrlParameterByName = (name, url) => {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    Vue.prototype.findInArrayObject = (prop, value, array) => {
        for (var i = 0; i < array.length; i++) {
            if (array[i][prop] === value) {
                return array[i];
            }
        }
    }

    Vue.prototype.removeFormatting = (str) => {
        //remove tudo o que não é numero
        str = str.replace(/[^\d]/g, "");
        return str;
    }

    Vue.prototype.formatMoney = (number, decPlaces, decSep, thouSep) => {
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
            decSep = typeof decSep === "undefined" ? "," : decSep;
        thouSep = typeof thouSep === "undefined" ? "." : thouSep;
        let sign = number < 0 ? "-" : "";
        let i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
        let j = (j = i.length) > 3 ? j % 3 : 0;

        return sign +
            (j ? i.substr(0, j) + thouSep : "") +
            i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
            (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
    }

    Vue.prototype.goBack = () => {
        window.location.href = window.previousUrl;
    }

    /*FUNÇÃO VALIDA CPF
        EXEMPLO DE CAHAMADA|UTILIZAÇÃO: if(!valida_cpf(document.getElementById("CPFCNPJ").value)){}*/
    Vue.prototype.isCPF = (cpf) => {
        var newcpf = '';
        for (i = 0; i <= cpf.length; i++)
            if (!isNaN(cpf.substr(i, 1)))
                newcpf += cpf.substr(i, 1);
        cpf = newcpf;

        var numeros, digitos, soma, i, resultado, digitos_iguais;
        digitos_iguais = 1;
        if (cpf.length < 11)
            return false;
        for (i = 0; i < cpf.length - 1; i++)
            if (cpf.charAt(i) != cpf.charAt(i + 1)) {
                digitos_iguais = 0;
                break;
            }
        if (!digitos_iguais) {
            numeros = cpf.substring(0, 9);
            digitos = cpf.substring(9);
            soma = 0;
            for (i = 10; i > 1; i--)
                soma += numeros.charAt(10 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;
            numeros = cpf.substring(0, 10);
            soma = 0;
            for (i = 11; i > 1; i--)
                soma += numeros.charAt(11 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;
            return true;
        }
        else
            return false;
    }

    /*
    =============================================================================================
    FUNÇÃO VALIDA CNPJ
    EXEMPLO DE CAHAMADA|UTILIZAÇÃO: if(!valida_cnpj(document.getElementById("CPFCNPJ").value)){}*/
    Vue.prototype.isCNPJ = (cnpj) => {
        var newcnpj = '';
        for (i = 0; i <= cnpj.length; i++)
            if (!isNaN(cnpj.substr(i, 1)))
                newcnpj += cnpj.substr(i, 1);
        cnpj = newcnpj;

        var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
        digitos_iguais = 1;
        if (cnpj.length < 14 && cnpj.length < 15)
            return false;
        for (i = 0; i < cnpj.length - 1; i++)
            if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
                digitos_iguais = 0;
                break;
            }
        if (!digitos_iguais) {
            tamanho = cnpj.length - 2
            numeros = cnpj.substring(0, tamanho);
            digitos = cnpj.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;
            tamanho = tamanho + 1;
            numeros = cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;
            return true;
        }
        else
            return false;
    }

    Vue.prototype.mesExtenso = () => {
        return [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro',
        ];
    }
}
