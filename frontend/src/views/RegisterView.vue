<template>
  <div class="register">
    <div class="register-container">
      <h1>Регистрация</h1>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="email">Email:</label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            placeholder="Введите ваш email"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label for="password">Пароль:</label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
            placeholder="Введите ваш пароль"
            class="form-control"
          />
        </div>
        <button type="submit" class="submit-btn">Зарегистрироваться</button>
      </form>
      <div style="color: white">
        Если есть аккаунт
        <RouterLink to="/" class="nav-item">Вход</RouterLink>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios";
import { useToast } from "vue-toastification"; // Импортируем vue-toastification

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

        this.showToast("success", "Регистрация успешна! Войдите в систему.");
        this.$router.push("/"); // Переход на страницу входа
      } catch (error) {
        console.error("Ошибка регистрации:", error);
        this.showToast("error", "Ошибка при регистрации.");
      }
    },

    // Метод для отображения уведомлений
    showToast(type, message) {
      const toast = useToast();
      toast[type](message);
    },
  },
};
</script>

<style scoped>
/* Общий фон и центрирование */
.register {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

/* Карточка регистрации */
.register-container {
  background: linear-gradient(135deg, #6e7bff, #4e66f2);

  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

/* Заголовок */
h1 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #ffffff;
}

/* Форматирование полей ввода */
.form-group {
  margin-bottom: 20px;
  text-align: left;
}

label {
  display: block;
  font-size: 1rem;
  margin-bottom: 5px;
  color: #ffffff;
}

.form-control {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s ease;
}

.form-control:focus {
  border-color: #00cc99;
  outline: none;
}

/* Кнопка отправки */
.submit-btn {
  width: 100%;
  padding: 12px;
  font-size: 1rem;
  background: #4560fd;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.submit-btn:hover {
  background: #0055aa;
}

/* Адаптивность */
@media (max-width: 480px) {
  h1 {
    font-size: 1.5rem;
  }

  .form-control {
    font-size: 0.9rem;
    padding: 8px;
  }

  .submit-btn {
    font-size: 0.9rem;
    padding: 10px;
  }
}
</style>
