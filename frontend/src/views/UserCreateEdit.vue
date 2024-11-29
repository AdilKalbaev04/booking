<template>
  <div class="user-create-edit">
    <h1>
      {{ isEditMode ? "Редактировать пользователя" : "Создать пользователя" }}
    </h1>
    <form @submit.prevent="saveUser" class="form-container">
      <div class="form-group">
        <label for="name">Имя:</label>
        <input
          type="text"
          v-model="user.name"
          required
          class="form-control"
          placeholder="Введите имя"
        />
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input
          type="email"
          v-model="user.email"
          required
          class="form-control"
          placeholder="Введите email"
        />
      </div>
      <div class="form-group">
        <label for="role">Роль:</label>
        <select v-model="user.role" required class="form-control">
          <option value="admin">Администратор</option>
          <option value="user">Пользователь</option>
        </select>
      </div>
      <button type="submit" class="submit-btn">
        {{ isEditMode ? "Обновить" : "Создать" }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from "@/axios";
import { useRoute, useRouter } from "vue-router";

export default {
  data() {
    return {
      user: {
        name: "",
        email: "",
        role: "user",
      },
      isEditMode: false,
    };
  },
  mounted() {
    const route = useRoute();
    if (route.params.id) {
      this.isEditMode = true;
      this.fetchUser(route.params.id);
    }
  },
  methods: {
    async fetchUser(id) {
      try {
        const response = await axios.get(`/users/${id}`);
        this.user = response.data;
      } catch (error) {
        console.error("Ошибка при загрузке пользователя:", error);
      }
    },
    async saveUser() {
      try {
        if (this.isEditMode) {
          await axios.put(`/users/${this.$route.params.id}`, this.user);
          alert("Пользователь обновлен!");
        } else {
          await axios.post("/users", this.user);
          alert("Пользователь создан!");
        }
        this.$router.push("/users");
      } catch (error) {
        console.error("Ошибка при сохранении пользователя:", error);
      }
    },
  },
};
</script>

<style scoped>
.user-create-edit {
  padding: 20px;
}

h1 {
  font-size: 2rem;
  margin-bottom: 20px;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 15px;
  max-width: 500px;
  margin: 0 auto;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-control {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.submit-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px;
  cursor: pointer;
  font-size: 16px;
  border-radius: 5px;
}

.submit-btn:hover {
  background-color: #0056b3;
}
</style>
