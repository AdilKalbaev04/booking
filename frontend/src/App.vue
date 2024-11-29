<script setup>
import { RouterLink, RouterView } from "vue-router";

function logout() {
  localStorage.removeItem("token");
  location.reload();
}

const isLoggedIn = !!localStorage.getItem("token");

// Пример проверки роли пользователя, предполагая, что токен содержит роль
const userRole = localStorage.getItem("role"); // Роль может быть сохранена в localStorage вместе с токеном
const isAdmin = userRole === "admin"; // Пример проверки роли "admin"
</script>

<template>
  <header>
    <div class="wrapper">
      <nav class="navbar">
        <div class="nav-links">
          <h1>there email user</h1>
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
        <button v-if="isLoggedIn" @click="logout" class="logout-btn">
          Logout
        </button>
      </nav>
    </div>
  </header>

  <RouterView />
</template>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #0044cc;
  padding: 15px 30px;
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
