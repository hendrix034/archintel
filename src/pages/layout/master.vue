<template>
    <div class="w-full h-full flex">
        <sidebar :dataOpenSideBar="openSideBar"/>
        <div class="w-full h-full">
            <headerTop :dataOpenSideBar="openSideBar" :clickHambuger="toggleSidebar"/>
            <div class="w-full h-[calc(100vh-50px)] pt-4">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import headerTop from './../../components/header.vue'
import sidebar from './../../components/sidebar.vue'
export default {
    components: { headerTop, sidebar },
    data() {
        return {
            openSideBar: true
        }
    },
    mounted(){
    },
    methods:{
        toggleSidebar() {
            this.openSideBar = !this.openSideBar
        }
    }
}
</script>