<template>
    <div class="min-h-screen flex justify-center bg-gray-100">
      <div class="bg-white p-8 rounded shadow-md w-full">
        <div class="flex justify-between">
          <h2 class="text-2xl font-semibold">Company Management</h2>
          <button @click="showCreateCompanyModal" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Create Company
          </button>
        </div>
        <table class="w-full table-auto mt-4">
          <thead>
            <tr class="bg-gray-200">
              <th class="px-4 py-2">Logo</th>
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody v-if="companies.length">
            <tr v-for="company in companies" :key="company.id">
                <td class="border px-4 py-2">
                    <img :src="getLogoWithPrefix(company.logo)" alt="Company Logo" style="max-width: 100px; max-height: 100px;" />
                </td>
                <td class="border px-4 py-2">{{ company.name }}</td>
                <td class="border px-4 py-2">
                    <button @click="editCompany(company)" class="text-blue-500 hover:underline mr-2">Edit</button>
                    <button @click="confirmDelete(company.id)" class="text-red-500 hover:underline">Delete</button>
                </td>
            </tr>
          </tbody>
          <div v-else class="bg-white border p-4 rounded-lg shadow-md text-center">
            <p>No Company found.</p>
          </div>
        </table>
      </div>
      <div v-if="isCreateCompanyModalVisible" class="fixed inset-0 flex items-center justify-center">
        <div class="fixed inset-0 bg-gray-700 opacity-50"></div>
        <div class="bg-white p-8 rounded shadow-md w-96 z-10">
          <h2 class="text-2xl font-semibold mb-6">Create company</h2>
          <form @submit.prevent="createCompany">
            <div class="mb-4">
              <label for="newName" class="block text-gray-600 text-sm font-medium mb-2">Name</label>
              <input
                v-model="newCompany.name"
                type="text"
                id="newName"
                name="newName"
                class="w-full p-2 border border-gray-300 rounded"
                required
              />
            </div>
            <div class="mb-4">
              <label for="newStatus" class="block text-gray-600 text-sm font-medium mb-2">Status</label>
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  value="active"
                  v-model="newCompany.status"
                  class="form-radio h-5 w-5 text-blue-600"
                />
                <span class="ml-2">Active</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input
                  type="radio"
                  value="inactive"
                  v-model="newCompany.status"
                  class="form-radio h-5 w-5 text-red-600"
                />
                <span class="ml-2">Inactive</span>
              </label>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-600 text-sm font-medium mb-2">Image</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    @change="handleImageUpload"
                    class="w-full p-2 border border-gray-300 rounded"
                    accept="image/*"
                />
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
              Create
            </button>
          </form>
          <button @click="closeCreateCompanyModal" class="mt-4 text-red-500 hover:underline">Cancel</button>
        </div>
      </div>
      <confirmation-modal
      :visible="showConfirmationModal"
      :message="confirmationMessage"
      @confirmed="deleteCompany"
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
        showConfirmationModal: false,
        confirmationMessage: 'Are you sure you want to delete this Company?',
        companyIdToDelete: null,
        companies: [],
        isCreateCompanyModalVisible: false,
        newCompany: {
          name: '',
          image: '',
          status: 'active',
        },
      };
    },
    computed: {
        getLogoWithPrefix() {
        return (logoURL) => {
            return `http://localhost/archintel-backend${logoURL}`;
        };
        },
    },
    mounted() {
      this.fetchCompanyData()
    },
    methods: {
      fetchCompanyData() {
        this.$axios
        .get('companies/fetch.php')
        .then((response) => {
          // Handle the response data
          this.companies = response.data;
        })
        .catch((error) => {
          // Handle errors
          console.error('Error:', error);
        });
      },
      showCreateCompanyModal() {
        this.isCreateCompanyModalVisible = true;
      },
      closeCreateCompanyModal() {
        this.isCreateCompanyModalVisible = false;
      },
      handleImageUpload(event) {
        this.newCompany.image = event.target.files[0];
        console.log(this.newCompany.image); // Check the selected file
      },
      async createCompany() {
        
          
        
          try {
            const formData = new FormData();
            formData.append('name', this.newCompany.name);
            formData.append('status', this.newCompany.status);
            formData.append('image', this.newCompany.image); // 'image' should match the field name in your PHP script

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            };

            const response = await this.$axios.post('companies/add.php', formData, config);
            if(response.data.status){
              
              this.$toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
              this.fetchCompanyData();
              this.newCompany.name = '';
              
              this.isCreateCompanyModalVisible = false;

            }else{

              this.$toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 3000 });

            }


            

          } catch (error) {

            console.error('Error:', error);

          }

          
        
      },
      editCompany() {
      },

      async deleteCompany() {
        const response = await this.$axios.post('companies/delete.php', {id: this.companyIdToDelete});
        this.$toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
        this.fetchCompanyData();
        this.showConfirmationModal = false;
      },
      cancelDelete() {
        this.showConfirmationModal = false;
      },
      confirmDelete(companyId) {
        this.companyIdToDelete = companyId;
        this.showConfirmationModal = true;
      },
    },
  };
  </script>

  