<template>
  <div class="register">
    <h1>Регистрация</h1>
    <form @submit.prevent="handleRegister">
      <div>
        <label for="email">Email:</label>
        <input v-model="email" type="email" required />
      </div>
      <div>
        <label for="password">Пароль:</label>
        <input v-model="password" type="password" required />
      </div>
      <button type="submit">Зарегистрироваться</button>
    </form>
  </div>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async handleRegister() {
      try {
        const response = await axios.post("/register", {
          email: this.email,
          password: this.password,
        });

        alert("Регистрация успешна! Войдите в систему.");
        this.$router.push("/login"); // Переход на страницу входа
      } catch (error) {
        console.error("Ошибка регистрации:", error);
        alert("Ошибка при регистрации.");
      }
    },
  },
};
</script>
