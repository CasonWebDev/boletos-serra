<template>
    <div class="header">
        <div class="content">
            <img :src="logo" alt="">
            <div class="line"></div>
            <img :src="whatsapp" alt="">
        </div>
    </div>
</template>
<script>
    import logo from "../../img/logoserra.png"
    import whatsapp from "../../img/whatsapp.png";
    export default {
        data() {
            return {
                logo,
                whatsapp
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import "resources/sass/breakpoints";
    .header{
        background-color: #ffffff;
        padding: 3em 0;
        border-bottom: solid 10px #2669a0;
        display: flex;
        align-items: center;
        justify-content: center;
        .content{
            display: flex;
            align-items: center;
            justify-content: space-between;
            .line{
                width: 2px;
                height: 100px;
                background-color: #000000;
            }
            @include for-size(phone-only) {
                width: 90%;
                flex-direction: column;
                img{
                    max-width: 100%
                }
                .line{
                    display: none;
                }
            }
            @include for-size(tablet-landscape-up) {
                width: 90%;
                img{
                    max-width: 50%
                }
            }
            @include for-size(desktop-up) {
                width: 1000px;
            }
        }
    }
</style>
