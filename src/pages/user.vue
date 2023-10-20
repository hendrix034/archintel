<template>
    <div class="min-h-screen flex justify-center bg-gray-100">
      <div class="bg-white p-8 rounded shadow-md w-full">
        <div class="flex justify-between">
          <h2 class="text-2xl font-semibold">User Management</h2>
          <button @click="showCreateUserModal" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Create User
          </button>
        </div>
        <table class="w-full table-auto mt-4">
          <thead>
            <tr class="bg-gray-200">
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">type</th>
              <th class="px-4 py-2">status</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td class="border px-4 py-2">{{ user.name }}</td>
              <td class="border px-4 py-2">{{ user.email }}</td>
              <td class="border px-4 py-2">{{ user.type }}</td>
              <td class="border px-4 py-2">{{ user.status }}</td>
              <td class="border px-4 py-2">
                <button @click="editUser(user)" class="text-blue-500 hover:underline mr-2">Edit</button>
                <button @click="confirmDelete(user.id)" class="text-red-500 hover:underline">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Create User Modal -->
      <div v-if="isCreateUserModalVisible || isEditUserModalVisible" class="fixed inset-0 flex items-center justify-center">
        <div class="fixed inset-0 bg-gray-700 opacity-50"></div>
        <div class="bg-white p-8 rounded shadow-md w-96 z-10">
          <h2 class="text-2xl font-semibold mb-6">Create User</h2>
          <!-- Form to create a new user -->
          <form @submit.prevent="isEditUserModalVisible ? saveEditedUserValidation() : createUser()">
            <div class="mb-4">
              <label for="newName" class="block text-gray-600 text-sm font-medium mb-2">Name</label>
              <input
                v-model="newUser.name"
                type="text"
                id="newName"
                name="newName"
                class="w-full p-2 border border-gray-300 rounded"
                required
              />
            </div>
            <div class="mb-4">
              <label for="newEmail" class="block text-gray-600 text-sm font-medium mb-2">Email</label>
              <input
                v-model="newUser.email"
                type="email"
                id="newEmail"
                name="newEmail"
                class="w-full p-2 border border-gray-300 rounded"
                required
              />
            </div>
            
            <div class="mb-4">
              <label for="newPassword" class="block text-gray-600 text-sm font-medium mb-2">Password</label>
              <input
                v-model="newUser.password"
                type="password"
                id="newPassword"
                name="newPassword"
                class="w-full p-2 border border-gray-300 rounded"
                required
              />
            </div>
            <div class="mb-4">
              <label for="newPassword" class="block text-gray-600 text-sm font-medium mb-2">Confirm Password</label>
              <input
                v-model="newUser.confirmPassword"
                type="password"
                id="newConfirmPassword"
                name="newConfirmPassword"
                class="w-full p-2 border border-gray-300 rounded"
              />
            </div>
            <div class="mb-4">
              <label for="newStatus" class="block text-gray-600 text-sm font-medium mb-2">Status</label>
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  value="active"
                  v-model="newUser.status"
                  class="form-radio h-5 w-5 text-blue-600"
                />
                <span class="ml-2">Active</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input
                  type="radio"
                  value="inactive"
                  v-model="newUser.status"
                  class="form-radio h-5 w-5 text-red-600"
                />
                <span class="ml-2">Inactive</span>
              </label>
            </div>
            <div class="mb-4">
              <label for="newStatus" class="block text-gray-600 text-sm font-medium mb-2">Type</label>
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  value="writer"
                  v-model="newUser.type"
                  class="form-radio h-5 w-5 text-blue-600"
                />
                <span class="ml-2">Writer</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input
                  type="radio"
                  value="editor"
                  v-model="newUser.type"
                  class="form-radio h-5 w-5 text-red-600"
                />
                <span class="ml-2">Editor</span>
              </label>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
              {{ isEditUserModalVisible ? 'Save' : 'Create' }}
            </button>
          </form>
          <button @click="closeCreateUserModal" class="mt-4 text-red-500 hover:underline">Cancel</button>
        </div>
      </div>
      <confirmation-modal
      :visible="showConfirmationModal"
      :message="confirmationMessage"
      @confirmed="deleteUser"
      @canceled="cancelDelete"
      ></confirmation-modal>
    </div>
  </template>
  
  <script>
  import ConfirmationModal from './../components/modal/confirmationModal';
  export default {
    components: {
      ConfirmationModal,
    },
    data() {
      return {
        isEditUserModalVisible: false,
        showConfirmationModal: false,
        confirmationMessage: 'Are you sure you want to delete this user?',
        userIdToDelete: null,
        users: [],
        isCreateUserModalVisible: false,
        newUser: {
          name: '',
          password: '',
          confirmPassword: '',
          email: '',
          status: 'active',
          type: 'writer'
        },
      };
    },
    mounted() {
      this.fetchUserData()
    },
    methods: {
      fetchUserData() {
        this.$axios
        .get('users/fetch.php')
        .then((response) => {
          // Handle the response data
          this.users = response.data;
        })
        .catch((error) => {
          // Handle errors
          console.error('Error:', error);
        });
      },
      showCreateUserModal() {
        this.isCreateUserModalVisible = true;
      },
      closeCreateUserModal() {
        this.isCreateUserModalVisible = false;
        this.isEditUserModalVisible = false;
      },
      async createUser() {
        if(this.newUser.confirmPassword !== this.newUser.password){

          this.$toast.add({ severity: 'warn', summary: 'Warning', detail: 'Password not match', life: 3000 });

        }else{
          
        
          try {

            const response = await this.$axios.post('users/add.php', this.newUser);
            if(response.data.status){
              
              this.$toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
              this.fetchUserData();
              this.newUser.name = '';
              this.newUser.email = '';
              this.newUser.confirmPassword = '';
              this.newUser.password = '';
              this.isCreateUserModalVisible = false;

            }else{

              this.$toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 3000 });

            }


            

          } catch (error) {

            console.error('Error:', error);

          }

          
        }
      },
      editUser(user) {
        this.newUser = { ...user }; // Make a copy of the user to edit
        this.isEditUserModalVisible = true;
      },
      saveEditedUserValidation() {

        if(this.newUser.confirmPassword == null){

          delete this.newUser.password;
          this.saveEditedUser()

        } else if (this.newUser.confirmPassword === this.newUser.password) {

          this.$toast.add({ severity: 'warn', summary: 'Warning', detail: 'Password not match', life: 3000 });
          
        } else {
          
          this.saveEditedUser()
        }
        
      },
      async saveEditedUser(){

        try {
            const response = await this.$axios.post('users/update.php', this.newUser);
            if (response.data.status) {
              this.$toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
              this.fetchUserData();
              this.isEditUserModalVisible = false;
            } else {
              this.$toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 3000 });
            }
          } catch (error) {
            console.error('Error:', error);
          }

      },

      async deleteUser() {

        const response = await this.$axios.post('users/delete.php', {id: this.userIdToDelete});
        this.$toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
        this.fetchUserData();
        this.showConfirmationModal = false;

      },
      cancelDelete() {

        this.showConfirmationModal = false;

      },
      confirmDelete(userId) {

        this.userIdToDelete = userId;
        this.showConfirmationModal = true;

      },
      // async deleteUser(userId) {
      //   const response = await this.$axios.post('users/delete.php', {id: userId});
      //   this.$toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
      //   this.fetchUserData();
      // },
    },
  };
  </script>

  