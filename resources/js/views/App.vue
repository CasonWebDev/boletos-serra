<template>
    <div class="app">
        <Header />
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    import Header from "../components/Header";
    export default {
        components: {Header}
    }
</script>
<style lang="scss" scoped>
    .app{
        width: 100vw;
        height: 100vh;
        display: flex;
        flex-direction: column;
        .container{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex: auto;
        }
    }
</style>
