<template>
    <div class="w-full bg-gray-100">
        <div class="flex justify-between items-center h-[50px]">
            <div class="p-4 cursor-pointer hover:bg-gray-50" @click="clickHambuger">
                <i class="pi pi-bars"></i>
            </div>
            <div class="flex space-x-3 items-center justify-center px-3">
                <Avatar icon="pi pi-user" class="mr-2" style="background-color:#9c27b0; color: #ffffff" shape="circle" @click="toggle" aria-haspopup="true" aria-controls="overlay_menu" />
                <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
                <Toast />
                
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        dataOpenSideBar: Boolean,
        clickHambuger: Function
    },
    data() {
        return {
            items: [
                {
                    label: 'Profile',
                    items: [
                        {
                            label: 'Logout',
                            icon: 'pi pi-refresh',
                            command: () => {
                                localStorage.removeItem('user');
                                
                                this.$toast.add({ severity: 'success', summary: 'Logout', detail: '', life: 3000 });
                                this.$router.push('/login');
                            }
                        },
                    ]
                }
            ]
        };
    },
    methods: {
        toggle(event) {
            this.$refs.menu.toggle(event);
        },
        save() {
            this.$toast.add({ severity: 'success', summary: 'Success', detail: 'Data Saved', life: 3000 });
        }
    },
};
</script>