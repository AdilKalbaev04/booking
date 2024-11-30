<script setup>
import { onMounted } from "vue";
import { RouterLink, RouterView, useRouter } from "vue-router";

function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("email");
  localStorage.removeItem("role");
  location.reload();
}
const router = useRouter();

const isLoggedIn = !!localStorage.getItem("token");
const email = localStorage.getItem("email");
const userRole = localStorage.getItem("role");
const isAdmin = userRole === "admin";

onMounted(() => {
  if (isLoggedIn) {
    router.push("/bookings");
  }
});
</script>

<template>
  <header>
    <div class="wrapper">
      <nav class="navbar">
        <div class="nav-links">
          <RouterLink v-if="isAdmin" to="/users" class="nav-item"
            >Users</RouterLink
          >

          <RouterLink to="/bookings" v-if="isLoggedIn" class="nav-item"
            >Bookings</RouterLink
          >
          <RouterLink to="/create" v-if="isLoggedIn" class="nav-item"
            >Create</RouterLink
          >
          <RouterLink to="/halls" v-if="isLoggedIn" class="nav-item"
            >Halls</RouterLink
          >
          <template v-else>
            <RouterLink to="/" class="nav-item">Login</RouterLink>
            <RouterLink to="/register" class="nav-item">Register</RouterLink>
          </template>
        </div>
        <div class="xz">
          <span>{{ email }}</span>
          <button v-if="isLoggedIn" @click="logout" class="logout-btn">
            Logout
          </button>
        </div>
      </nav>
    </div>
  </header>
  <RouterView />
</template>

<style>
* {
  box-sizing: border-box;
}

body {
  margin: 0;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #0044cc;
  padding: 15px 30px;
}

.xz {
  display: flex;
  align-items: center;
  gap: 10px;
}
.nav-links {
  display: flex;
}

.nav-item {
  margin-right: 20px;
  color: white;
  text-decoration: none;
  font-size: 18px;
}

.nav-item:hover {
  text-decoration: underline;
}

.logout-btn {
  background-color: #ff5733;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
}

.logout-btn:hover {
  background-color: #e74c3c;
}

button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
