<template>
  <div class="min-h-screen flex justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full">
      <div class="flex justify-between">
        <h2 class="text-2xl font-semibold">Articles</h2>
        <button  v-if="!isEditor" @click="showCreateArticleModal" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
          Create Article
        </button>
      </div>
      <div v-if="articles.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
        <div v-for="article in articles" :key="article.id" class="bg-white border p-4 rounded-lg shadow-md">
          <div class="mb-4">
            <img :src="getLogoWithPrefix(article.image)" alt="Article Logo" class="w-full rounded">
          </div>
          <div>
            <h2 class="text-xl font-semibold text-blue-800 mb-2">{{ article.title }}</h2>
            <p class="text-gray-500 mb-2">{{ article.date }}</p>
            <div class="text-gray-700 mb-2 content">
              {{ article.content }}
            </div>
          </div>
          <div class="text-gray-700 flex justify-between">
            <div>
              <p><span class="text-blue-600 font-semibold">Author:</span> {{ article.writer }}</p>
              <p><span class="text-red-600 font-semibold">Editor:</span> {{ article.editor }}</p>
              <p><span class="text-indigo-600 font-semibold">Company:</span> {{ article.company }}</p>
              <p><span class="text-green-600 font-semibold">Status:</span> {{ article.status }}</p>
            </div>
            <div>
              <button @click="editArticle(article)" class="text-blue-600 hover:underline mr-2">Edit</button>
              <button @click="confirmDelete(article.id)" class="text-red-600 hover:underline">Delete</button>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="bg-white border p-4 rounded-lg shadow-md text-center">
        <p>No articles found.</p>
      </div>
    </div>
    <div v-if="isCreateArticleModalVisible" class="fixed inset-0 flex items-center justify-center">
      <div class="fixed inset-0 bg-gray-700 opacity-50"></div>
      <div class="bg-white p-8 rounded shadow-md w-96 z-10">
        <h2 class="text-2xl font-semibold mb-6">Create Article</h2>
        <form @submit.prevent="createArticle">
          <div class="mb-4">
            <label for="newTitle" class="block text-gray-600 text-sm font-medium mb-2">Title</label>
            <input
              v-model="newArticle.title"
              type="text"
              id="newTitle"
              name="newTitle"
              class="w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <div class="mb-4">
            <label for="newLink" class="block text-gray-600 text-sm font-medium mb-2">Link</label>
            <input
              v-model="newArticle.link"
              type="text"
              id="newLink"
              name="newLink"
              class="w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <div class="mb-4">
            <label for="newDate" class="block text-gray-600 text-sm font-medium mb-2">Date</label>
            <input
              v-model="newArticle.date"
              type="date"
              id="newDate"
              name="newDate"
              class="w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <div class="mb-4">
            <label for="newContent" class="block text-gray-600 text-sm font-medium mb-2">Content</label>
            <textarea
              v-model="newArticle.content"
              id="newContent"
              name="newContent"
              class="w-full p-2 border border-gray-300 rounded"
              rows="4"
              required
            ></textarea>
          </div>
          <div v-if="isEditor" class="mb-4">
            <label for="newStatus" class="block text-gray-600 text-sm font-medium mb-2">Status</label>
            <select v-model="newArticle.status" id="newStatus" name="newStatus" class="w-full p-2 border border-gray-300 rounded" required>
              <option value="For edit">For edit</option>
              <option value="Published">Published</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="newCompany" class="block text-gray-600 text-sm font-medium mb-2">Company</label>
            <select v-model="newArticle.company" id="newCompany" name="newCompany" class="w-full p-2 border border-gray-300 rounded" required>
              <option value="" disabled selected>Select a company</option>
              <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
            </select>
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
        <button @click="closeCreateArticleModal" class="mt-4 text-red-500 hover:underline">Cancel</button>
      </div>
    </div>
    <div v-if="showEdit" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

      <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <div class="modal-content py-4 text-left px-6">
          <h2 class="text-xl font-semibold text-blue-800 mb-2">Edit Article</h2>

          <!-- Edit article form -->
          <form @submit.prevent="saveChanges">
            <div class="mb-4">
              <label for="image" class="text-gray-600 font-medium">Image</label>
              <input type="file" @change="handleImageUpload" id="image" name="image" class="w-full p-2 border border-gray-300 rounded" accept="image/*">
            </div>

            <div class="mb-4">
              <label for="title" class="text-gray-600 font-medium">Title</label>
              <input type="text" v-model="newArticle.title" id="title" name="title" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
              <label for="date" class="text-gray-600 font-medium">Date</label>
              <input type="date" v-model="newArticle.date" id="date" name="date" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
              <label for="content" class="text-gray-600 font-medium">Content</label>
              <textarea v-model="newArticle.content" id="content" name="content" class="w-full p-2 border border-gray-300 rounded" rows="6" required></textarea>
            </div>

            <div class="mb-4">
              <label for="company" class="text-gray-600 font-medium">Company</label>
              <select v-model="newArticle.company" id="newCompany" name="newCompany" class="w-full p-2 border border-gray-300 rounded" required>
              <option value="" disabled selected>Select a company</option>
              <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div v-if="isEditor" class="mb-4">
              <label for="newStatus" class="block text-gray-600 text-sm font-medium mb-2">Status</label>
              <select v-model="newArticle.status" id="newStatus" name="newStatus" class="w-full p-2 border border-gray-300 rounded" required>
                <option value="For edit">For edit</option>
                <option value="Published">Published</option>
              </select>
            </div>


            <div class="mt-4">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
              <button @click="closeEditModal" class="text-red-600 ml-4 hover:underline cursor-pointer">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <confirmation-modal
      :visible="showConfirmationModal"
      :message="confirmationMessage"
      @confirmed="deleteArticle"
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
      showEdit: false,
      articleToEdit: null, 
      currentUser: null,
      isEditor: false,
      showConfirmationModal: false,
      confirmationMessage: 'Are you sure you want to delete this Article?',
      articleIdToDelete: null,
      articles: [],
      companies:[],
      isCreateArticleModalVisible: false,
      newArticle: {
        id: null,
        title: '',
        link: '',
        date: '',
        content: '',
        status: 'For edit',
        writer: '',
        editor: '',
        company: '',
        image: '',
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
    const user = JSON.parse(localStorage.getItem('user'));
    
    this.currentUser = user;
    this.isEditor = user.type == 'editor' ? true : false;
    
    this.fetchArticleData();
    this.fetchCompanyData();
  },
  methods: {
    closeEditModal() {
      this.showEdit = false;
      this.imageFile = null;
    },
    fetchArticleData() {
      if(this.isEditor){
      this.$axios
        .get('articles/fetch.php')
        .then((response) => {
          this.articles = response.data;
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      }
      else
      {
        this.$axios
        .get('articles/fetch.php', {
          params: {
            user_id: this.currentUser.id, // Replace with the actual user_id value
          }
        })
        .then((response) => {
          // Handle the response data
          this.articles = response.data;
        })
        .catch((error) => {
          // Handle errors
          console.error('Error:', error);
        });
      }
    },
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
    showCreateArticleModal() {
      this.isCreateArticleModalVisible = true;
    },
    closeCreateArticleModal() {
      this.isCreateArticleModalVisible = false;
    },
    handleImageUpload(event) {
      this.newArticle.image = event.target.files[0];
      console.log(this.newArticle.image);
    },
    async saveChanges() {
      if(this.isEditor){
        this.newArticle.editor = this.currentUser.id
      }else{
        this.newArticle.writer = this.currentUser.id
      }
      try {
        const formData = new FormData();
        formData.append('id', this.newArticle.id);
        formData.append('title', this.newArticle.title);
        formData.append('link', this.newArticle.link);
        formData.append('date', this.newArticle.date);
        formData.append('content', this.newArticle.content);
        formData.append('status', this.newArticle.status);
        formData.append('writer', this.newArticle.writer);
        formData.append('editor', this.newArticle.editor);
        formData.append('company', this.newArticle.company);

        // Check if a new image file is provided
        if (this.newArticle.image) {
          formData.append('image', this.newArticle.image);
        }

        const config = {
          headers: {
            'content-type': 'multipart/form-data',
          },
        };

        const response = await this.$axios.post('articles/update.php', formData, config);

        if (response.data.status) {
          this.$toast.add({
            severity: 'success',
            summary: 'Success',
            detail: response.data.message,
            life: 3000,
          });
          this.fetchArticleData();
          this.newArticle.title = '';
          this.newArticle.link = '';
          this.newArticle.date = '';
          this.newArticle.content = '';
          this.newArticle.writer = '';
          this.newArticle.editor = '';
          this.newArticle.company = '';
          this.showEdit = false;
        } else {
          this.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: response.data.message,
            life: 3000,
          });
        }
      } catch (error) {
        console.error('Error:', error);
      }

    },
    async createArticle() {
      this.newArticle.writer = this.currentUser.id;

      try {
        const formData = new FormData();
        formData.append('title', this.newArticle.title);
        formData.append('link', this.newArticle.link);
        formData.append('date', this.newArticle.date);
        formData.append('content', this.newArticle.content);
        formData.append('status', this.newArticle.status);
        formData.append('editor', this.newArticle.editor);
        formData.append('writer', this.newArticle.writer);
        formData.append('company', this.newArticle.company);
        formData.append('image', this.newArticle.image);

        const config = {
          headers: {
            'content-type': 'multipart/form-data',
          },
        };

        const response = await this.$axios.post('articles/add.php', formData, config);
        if (response.data.status) {
          this.$toast.add({
            severity: 'success',
            summary: 'Success',
            detail: response.data.message,
            life: 3000,
          });
          this.fetchArticleData();
          this.newArticle.title = '';
          this.newArticle.link = '';
          this.newArticle.date = '';
          this.newArticle.content = '';
          this.newArticle.writer = '';
          this.newArticle.editor = '';
          this.newArticle.company = '';
          this.isCreateArticleModalVisible = false;
        } else {
          this.$toast.add({
            severity: 'error',
            summary: 'Error',
            detail: response.data.message,
            life: 3000,
          });
        }
      } catch (error) {
        console.error('Error:', error);
      }
    },
    editArticle(article) {
      this.showEdit = true;
      this.newArticle = { ...article };
    },
    async deleteArticle() {
      const response = await this.$axios.post('articles/delete.php', { id: this.articleIdToDelete });
      this.$toast.add({
        severity: 'success',
        summary: 'Success',
        detail: response.data.message,
        life: 3000,
      });
      this.fetchArticleData();
      this.showConfirmationModal = false;
    },
    cancelDelete() {
      this.showConfirmationModal = false;
    },
    confirmDelete(articleId) {
      this.articleIdToDelete = articleId;
      this.showConfirmationModal = true;
    },
  },
};
</script>
<style>
.content {
  max-height: 100px; /* Set the maximum height for the content area */
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>






