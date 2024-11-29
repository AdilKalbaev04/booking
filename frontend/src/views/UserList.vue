<template>
    <div class="user-list">
      <h1>Список пользователей</h1>
      <div class="actions">
        <router-link to="/users/create" class="create-btn">Создать пользователя</router-link>
      </div>
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>
              <button @click="editUser(user.id)" class="edit-btn">Редактировать</button>
              <button @click="deleteUser(user.id)" class="delete-btn">Удалить</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from "@/axios";
  
  export default {
    data() {
      return {
        users: [],
      };
    },
    mounted() {
      this.fetchUsers();
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await axios.get("/users");
          this.users = response.data;
        } catch (error) {
          console.error("Ошибка при загрузке пользователей:", error);
        }
      },
      editUser(id) {
        this.$router.push(`/users/edit/${id}`);
      },
      async deleteUser(id) {
        if (confirm("Вы уверены, что хотите удалить этого пользователя?")) {
          try {
            await axios.delete(`/users/${id}`);
            this.fetchUsers(); // Обновление списка
          } catch (error) {
            console.error("Ошибка при удалении пользователя:", error);
          }
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .user-list {
    padding: 20px;
  }
  
  h1 {
    font-size: 2rem;
    margin-bottom: 20px;
  }
  
  .actions {
    margin-bottom: 20px;
  }
  
  .create-btn {
    background-color: #28a745;
    color: white;
    padding: 10px;
    text-decoration: none;
    border-radius: 5px;
  }
  
  .create-btn:hover {
    background-color: #218838;
  }
  
  .user-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  th,
  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }
  
  .edit-btn,
  .delete-btn {
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .edit-btn {
    background-color: #007bff;
    color: white;
    margin-right: 5px;
  }
  
  .edit-btn:hover {
    background-color: #0056b3;
  }
  
  .delete-btn {
    background-color: #e74c3c;
    color: white;
  }
  
  .delete-btn:hover {
    background-color: #c0392b;
  }
  </style>
   