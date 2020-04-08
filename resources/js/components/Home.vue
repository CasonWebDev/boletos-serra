<template>
    <div class="home">
        <div v-if="initial">
            <p>Olá Associado,<br>
                Para facilitar o pagamento de sua mensalidade, disponibilizamos a segunda via do boleto online.</p>
            <p>Digite o CPF do titular para ter acesso ao boleto:</p>
            <the-mask :mask="'###.###.###-##'" placeholder="000.000.000-00" v-model="cpf" />
            <button @click="obterBoletos()">Emitir segunda via</button>
        </div>
        <div v-if="loading" class="loading">
            <img :src="loadingSpinner">
        </div>
        <div v-if="boletos" class="boletos">
            <p>Olá, {{cliente}}</p>
            <p>Clique em VISUALIZAR BOLETO para emitir a segunda via.</p>
            <ul>
                <li v-for="{ arquivo, referencia } in boletos">
                    {{referencia}}
                    <a :href="arquivo" target="_blank" class="link">Visualizar Boleto</a>
                </li>
            </ul>
        </div>
        <div v-if="error" class="boletos">
            <p>Boleto não encontrado! Talvez você esteja cadastrado com outra forma de pagamento,
                entre em contato através do nosso whatsapp 19 99240 6881
                ou pelo e-mail <a href="mailto:">cobranca@gruposerra.com.br</a></p>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import {TheMask} from 'vue-the-mask'
    import loadingSpinner from '../../img/loading.svg'
    export default {
        data() {
            return {
                initial: true,
                loading: false,
                boletos: null,
                cliente: null,
                error: false,
                cpf: null,
                loadingSpinner
            };
        },
        components: {
            TheMask
        },
        methods: {
            obterBoletos() {
                this.initial = false;
                this.loading = true;
                axios
                    .post('/api/obter-boletos', {
                        'api_token': process.env.MIX_API_TOKEN,
                        'cpf': this.cpf
                    })
                    .then(response => {
                        console.log(response);
                        if(response.data.length === 0){
                            this.error = true;
                        } else {
                            this.boletos = response.data;
                            this.cliente = response.data[0].nome;
                        }
                        this.loading = false;
                    })
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import "resources/sass/breakpoints";
    .home{
        @include for-size(phone-only) {
            width: 90%;
        }
        @include for-size(tablet-landscape-up) {
            width: 90%;
        }
        @include for-size(desktop-up) {
            width: 1000px;
        }
        p{
            font-size: 1.4em;
            margin-bottom: 1em;
        }
        input{
            @include for-size(phone-only) {
                width: 100%;
            }
            padding: .5em;
            width: 40%;
            border: solid 1px;
            font-size: 1.4em;
        }
        button, .link{
            @include for-size(phone-only) {
                margin-left: 0;
                margin-top: .5em;
                width: 100%;
            }
            background-color: #2669a0;
            color: #ffffff;
            border: solid 1px #2669a0;
            text-transform: uppercase;
            padding: .5em 1em;
            font-size: 1.4em;
            margin-left: .5em;
            cursor: pointer;
            text-decoration: none;
        }
        .loading{
            display: flex;
            justify-content: center;
            img {
                width: 50px;
            }
        }
        .boletos{
            text-align: center;
            ul{
                list-style: none;
                font-size: 1.5em;
                font-weight: bold;
                margin-top: 2em;
                li{
                    @include for-size(phone-only) {
                        flex-direction: column;
                    }
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: 1em 0;
                    border-bottom: solid 1px rgba(0,0,0,.2);
                    text-transform: uppercase;
                    &:last-child{
                        border-bottom: none;
                    }
                }
                .link{
                    @include for-size(phone-only) {
                        margin-left: 0;
                    }
                    font-size: .8em;
                    margin-left: 2em;
                }
            }
        }
    }
</style>
