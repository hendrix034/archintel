<template>
 <div class="h-screen bg-gray-800" id="side-bar" :class="dataOpenSideBar == true ? 'side-bar-visible w-2/12' : 'side-bar-close'">
    <div class="bg-gray-700 h-[50px] flex justify-center items-center ">
      <div class="text-xl font-bold text-center flex items-center justify-center text-white h-full" v-show="dataOpenSideBar">ARCHINTEL</div>
    </div>
    <div class="flex flex-col justify-between h-[calc(100vh-3rem)] bg-gray-900">
      <div class="menu-man text-left px-2 whitespace-nowrap ">
        <div class="profile flex justify-center items-center text-center p-5 ">
          <div class="text-center  text-white" v-show="dataOpenSideBar">
            <h5 class="text-xl font-medium leading-tight mb-2"> {{ name }}</h5>
            <p class="text-gray-500">{{ type }}</p>
            
          </div>
        </div>
        <div class="py-3 rounded-sm cursor-pointer text-gray-300 hover:text-white">
          <router-link to="/" class="px-2 flex space-x-2"><span class="pi pi-microsoft" v-tooltip.right="'Dashboard'"></span> <span v-show="dataOpenSideBar">Dashboard</span></router-link>
        </div>
        <div v-if="type == 'editor'" class="py-3 rounded-md cursor-pointer  text-gray-300 hover:text-white">
          <router-link to="/user" class="px-2 flex space-x-2"><span class="pi pi-user " v-tooltip.right="'User'"></span> <span v-show="dataOpenSideBar">User</span></router-link>
        </div>
        <div v-if="type == 'editor'" class="py-3 rounded-md cursor-pointer  text-gray-300 hover:text-white">
          <router-link to="/company" class="px-2 flex space-x-2"><span class="pi pi-building " v-tooltip.right="'Company'"></span> <span v-show="dataOpenSideBar">Company</span></router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      type: ''
    }

  },
  mounted(){
    const user = JSON.parse(localStorage.getItem('user'));

    this.name = user.name
    this.type = user.type
  },
  props: {
      dataOpenSideBar: Boolean
  },
    
}
</script>