<template>
  <div class="user-profile">
    <!-- Email Search -->
    <div class="email-search">
      <h2>Search User by Email</h2>
      <div class="search-form">
        <input 
          v-model="searchEmail" 
          type="email" 
          placeholder="Enter email address"
          @keyup.enter="searchByEmail"
        >
        <button @click="searchByEmail">Search</button>
      </div>
      <div v-if="loadingEmail">Searching...</div>
      <div v-if="errorEmail" class="error">{{ errorEmail }}</div>
      <div v-if="emailUser" class="user-card">
        <h3>{{ emailUser.name }}</h3>
        <p>{{ emailUser.email }}</p>
      </div>
    </div>

    <!-- Single User Display -->
    <div class="single-user">
      <h2>Single User</h2>
      <div v-if="loading">Loading...</div>
      <div v-else-if="error">{{ error }}</div>
      <div v-else class="user-card">
        <h3>{{ user.name }}</h3>
        <p>{{ user.email }}</p>
      </div>
    </div>

    <!-- All Users Display -->
    <div class="all-users">
      <h2>All Users</h2>
      <div v-if="loadingAll">Loading all users...</div>
      <div v-else-if="errorAll">{{ errorAll }}</div>
      <div v-else class="users-grid">
        <div v-for="user in allUsers" :key="user.id" class="user-card">
          <h3>{{ user.name }}</h3>
          <p>{{ user.email }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      allUsers: [],
      emailUser: null,
      searchEmail: '',
      loading: true,
      loadingAll: true,
      loadingEmail: false,
      error: null,
      errorAll: null,
      errorEmail: null
    }
  },
  created() {
    this.fetchUser()
    this.fetchAllUsers()
  },
  methods: {
    async fetchUser() {
      this.loading = true;
      this.error = null;
      try {
        const response = await this.axios.get('/api/user/1');
        this.user = response.data.data;
      } catch (error) {
        console.error('Error:', error);
        this.error = 'Error loading user data';
      } finally {
        this.loading = false;
      }
    },
    async fetchAllUsers() {
      this.loadingAll = true;
      this.errorAll = null;
      try {
        const response = await this.axios.get('/api/users');
        this.allUsers = response.data.data;
      } catch (error) {
        console.error('Error:', error);
        this.errorAll = 'Error loading all users';
      } finally {
        this.loadingAll = false;
      }
    },
    async searchByEmail() {
      if (!this.searchEmail) {
        this.errorEmail = 'Please enter an email address';
        return;
      }

      this.loadingEmail = true;
      this.errorEmail = null;
      this.emailUser = null;

      try {
        const response = await this.axios.get(`/api/user/email?email=${encodeURIComponent(this.searchEmail)}`);
        this.emailUser = response.data.data;
      } catch (error) {
        console.error('Error:', error);
        this.errorEmail = error.response && error.response.data && error.response.data.message 
          ? error.response.data.message 
          : 'Error searching user';
      } finally {
        this.loadingEmail = false;
      }
    }
  }
}
</script>

<style scoped>
.user-profile {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.email-search, .single-user, .all-users {
  margin-bottom: 30px;
  padding: 20px;
  background: #f5f5f5;
  border-radius: 8px;
}

.search-form {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

input {
  flex: 1;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  padding: 8px 16px;
  background: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background: #45a049;
}

.user-card {
  border: 1px solid #ddd;
  padding: 15px;
  margin: 10px 0;
  border-radius: 4px;
  background-color: white;
}

.users-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 15px;
}

h2 {
  color: #333;
  margin-top: 0;
  margin-bottom: 20px;
}

h3 {
  margin: 0 0 10px 0;
  color: #666;
}

p {
  margin: 0;
  color: #888;
}

.error {
  color: #f44336;
  margin: 10px 0;
}
</style>
