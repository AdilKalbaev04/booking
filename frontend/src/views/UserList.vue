<template>
  <div class="user-list">
    <h1>Список пользователей</h1>
    <div class="actions">
      <button @click="openModal()" class="create-btn">
        Создать пользователя
      </button>
    </div>
    <table class="user-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Имя</th>
          <th>Email</th>
          <th>Роль</th>
          <th colspan="2">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.role }}</td>
          <td>
            <button class="edit-btn" @click="openModal(user)">
              Редактировать
            </button>
          </td>
          <td>
            <button class="cancel-btn" @click="deleteUser(user.id)">
              Удалить
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="isModalOpen" class="modal-overlay">
      <div class="modal">
        <div class="modal-content">
          <h2>
            {{
              isEditMode ? "Редактировать пользователя" : "Создать пользователя"
            }}
          </h2>
          <form @submit.prevent="saveUser">
            <div class="form-group">
              <label for="name">Имя:</label>
              <input
                id="name"
                v-model="user.name"
                type="text"
                required
                class="form-control"
                placeholder="Введите имя"
              />
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input
                id="email"
                v-model="user.email"
                type="email"
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
            <div class="form-group" v-if="!isEditMode">
              <label for="password">Пароль:</label>
              <input
                id="password"
                v-model="user.password"
                type="password"
                required
                class="form-control"
                placeholder="Введите пароль"
              />
            </div>
            <div class="modal-actions">
              <button type="submit" class="submit-btn">
                {{ isEditMode ? "Обновить" : "Создать" }}
              </button>
              <button type="button" @click="closeModal" class="cancel-btn">
                Отмена
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      users: [],
      isModalOpen: false,
      isEditMode: false,
      user: {
        name: "",
        email: "",
        role: "user",
        password: "",
      },
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
    openModal(user = null) {
      this.isModalOpen = true;
      if (user) {
        this.isEditMode = true;
        this.user = { ...user };
        this.user.password = "";
      } else {
        this.isEditMode = false;
        this.user = { name: "", email: "", role: "user", password: "" };
      }
    },
    closeModal() {
      this.isModalOpen = false;
      this.user = { name: "", email: "", role: "user", password: "" };
    },
    async saveUser() {
      try {
        if (this.isEditMode) {
          await axios.put(`/users/${this.user.id}`, this.user);
          Swal.fire("Успех!", "Пользователь обновлен!", "success");
        } else {
          await axios.post("/users", this.user);
          Swal.fire("Успех!", "Пользователь создан!", "success");
        }
        this.fetchUsers();
        this.closeModal();
      } catch (error) {
        console.error("Ошибка при сохранении пользователя:", error);
        Swal.fire("Ошибка!", "Не удалось сохранить пользователя", "error");
      }
    },
    async deleteUser(id) {
      const result = await Swal.fire({
        title: "Вы уверены?",
        text: "Вы не сможете отменить это действие!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Да, удалить!",
        cancelButtonText: "Отмена",
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/users/${id}`);
          Swal.fire("Удалено!", "Пользователь был удален.", "success");
          this.fetchUsers();
        } catch (error) {
          console.error("Ошибка при удалении пользователя:", error);
          Swal.fire("Ошибка!", "Не удалось удалить пользователя", "error");
        }
      }
    },
  },
};
</script>

<style scoped>
/* Стили страницы */
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
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.create-btn:hover {
  background-color: #218838;
}

.user-table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

/* Стили для модального окна */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.3s ease-out;
}

.modal {
  background: #fff;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transform: scale(0.8);
  animation: modalOpen 0.3s ease-out forwards;
}

.modal-content h2 {
  font-size: 1.5rem;
  margin-bottom: 20px;
  text-align: center;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-size: 1rem;
  display: block;
  margin-bottom: 5px;
}

.form-control {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-top: 5px;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.submit-btn,
.cancel-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 5px;
  width: 48%;
  transition: background-color 0.3s;
}

.submit-btn:hover,
.cancel-btn:hover {
  background-color: #0056b3;
}

.cancel-btn {
  background-color: #e74c3c;
}
.edit-btn {
  background-color: #0056b3;
  color: white;
}
.edit-btn:hover {
  background-color: #2588f3;
}

.submit-btn {
  background-color: #28a745;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes modalOpen {
  from {
    transform: scale(0.8);
  }
  to {
    transform: scale(1);
  }
}
</style>
