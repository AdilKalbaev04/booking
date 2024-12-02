<template>
  <div class="booking-list">
    <h1>Список залов</h1>

    <div class="hall-cards">
      <div
        v-for="hall in halls"
        :key="hall.id"
        class="hall-card"
        @click="openModal(hall)"
      >
        <h3>{{ hall.name }}</h3>
        <p>{{ hall.description }}</p>
        <div class="chairs-container">
          <div v-for="n in 8" :key="n" class="chair"></div>
        </div>
        <span
          v-if="bookings.some((booking) => booking.hall_id === hall.id)"
          class="occupied"
        >
          Забронирован
        </span>
      </div>
    </div>

    <!-- Модальное окно для создания/редактирования бронирования -->
    <div v-if="isModalOpen" class="modal" @click.self="closeModal">
      <div class="modal-content">
        <h2>
          {{
            isEditMode
              ? `Изменить бронирование для ${selectedHall.name}`
              : `Создать бронирование для ${selectedHall.name}`
          }}
        </h2>
        <form @submit.prevent="saveBooking" class="form-container">
          <div class="form-group">
            <label for="start_time">Время начала:</label>
            <input
              type="datetime-local"
              v-model="newBooking.start_time"
              required
              class="form-control"
            />
          </div>
          <div class="form-group">
            <label for="end_time">Время окончания:</label>
            <input
              type="datetime-local"
              v-model="newBooking.end_time"
              required
              class="form-control"
            />
          </div>
          <div class="form-group">
            <label for="reason">Описание:</label>
            <input
              type="text"
              v-model="newBooking.reason"
              required
              class="form-control"
              placeholder="Введите Описание"
            />
          </div>
          <div class="form-actions">
            <button type="submit" class="submit-btn">
              {{
                isEditMode ? "Обновить бронирование" : "Создать бронирование"
              }}
            </button>
            <button type="button" @click="closeModal" class="cancel-btn">
              Отмена
            </button>
          </div>
        </form>
      </div>
    </div>

    <h1>Список забронированных залов</h1>
    <div class="booking-cards">
      <div v-for="booking in bookings" :key="booking.id" class="booking-card">
        <div class="booking-info">
          <h3>Зал: {{ getHallNameById(booking.hall_id) }}</h3>
          <p>Время начала: {{ formatDate(booking.start_time) }}</p>
          <p>Время окончания: {{ formatDate(booking.end_time) }}</p>
          <p>Описание: {{ booking.reason }}</p>
        </div>
        <div class="actions">
          <button @click="openEditModal(booking)">Изменить</button>
          <button @click="deleteBooking(booking.id)" class="delete-btn">
            Удалить
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios";
import Swal from "sweetalert2";
import { useToast } from "vue-toastification";

export default {
  data() {
    return {
      bookings: [],
      halls: [],
      isModalOpen: false,
      selectedHall: null,
      isEditMode: false,
      newBooking: {
        hall_id: "",
        start_time: "",
        end_time: "",
        reason: "",
        id: null,
      },
    };
  },
  mounted() {
    this.fetchBookings();
    this.fetchHalls();
  },
  methods: {
    async fetchBookings() {
      try {
        const response = await axios.get("/bookings");
        this.bookings = response.data;

        this.bookings.forEach((booking) => {
          if (new Date(booking.end_time) < new Date()) {
            this.deleteBookingV2(booking.id);
          }
        });
      } catch (error) {
        this.handleError(error, "Ошибка при загрузке бронирований");
      }
    },
    async fetchHalls() {
      try {
        const response = await axios.get("/halls");
        this.halls = response.data;
      } catch (error) {
        this.handleError(error, "Ошибка при загрузке залов");
      }
    },
    getHallNameById(hallId) {
      const hall = this.halls.find((h) => h.id === hallId);
      return hall ? hall.name : "Неизвестный зал";
    },
    openModal(hall) {
      const isOccupied = this.bookings.some(
        (booking) => booking.hall_id === hall.id
      );

      if (isOccupied) {
        this.showToast("error", "Этот зал уже забронирован!");
        return;
      }

      this.selectedHall = hall;
      this.isModalOpen = true;
      this.isEditMode = false;
      this.newBooking = {
        hall_id: hall.id,
        start_time: "",
        end_time: "",
        reason: "",
        id: null,
      };
    },

    openEditModal(booking) {
      this.selectedHall = this.halls.find(
        (hall) => hall.id === booking.hall_id
      );
      this.isModalOpen = true;
      this.isEditMode = true;
      this.newBooking = { ...booking };
    },
    closeModal() {
      this.isModalOpen = false;
      this.selectedHall = null;
      this.newBooking = {
        hall_id: "",
        start_time: "",
        end_time: "",
        reason: "",
        id: null,
      };
    },
    async saveBooking() {
      try {
        if (this.isEditMode) {
          await axios.put(`/bookings/${this.newBooking.id}`, this.newBooking);
        } else {
          await axios.post("/bookings", this.newBooking);
        }
        this.fetchBookings();
        this.closeModal();
        this.showToast(
          "success",
          this.isEditMode ? "Бронирование обновлено!" : "Бронирование создано!"
        );
      } catch (error) {
        this.handleError(error, "Ошибка при сохранении бронирования");
      }
    },
    async deleteBooking(id) {
      const result = await Swal.fire({
        title: "Вы уверены?",
        text: "Вы хотите удалить это бронирование?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Да, удалить",
        cancelButtonText: "Отмена",
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/bookings/${id}`);
          this.fetchBookings();
          this.showToast("success", "Успешно удалена!");
        } catch (error) {
          this.handleError(error, "Ошибка при удалении бронирования");
        }
      }
    },
    async deleteBookingV2(id) {
      const result = await Swal.fire({
        title: "Время истекло этого зала",
        text: "Вы хотите удалить это бронирование?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Да, удалить",
        cancelButtonText: "Отмена",
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/bookings/${id}`);
          this.fetchBookings();
        } catch (error) {
          this.handleError(error, "Ошибка при удалении бронирования");
        }
      }
    },
    handleError(error, message) {
      console.error(message, error);
      this.showToast("error", message);
    },
    showToast(type, message) {
      const toast = useToast();
      toast[type](message);
    },
    formatDate(dateTime) {
      const date = new Date(dateTime);
      return date.toLocaleString();
    },
  },
};
</script>

<style scoped>
.booking-list {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 45px;
}

h1 {
  font-size: 2.5rem;
  margin-bottom: 20px;
  margin-top: 60px;
  color: #333;
}

.hall-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 80px;
  width: 100%;
}

.hall-card {
  max-width: 300px;
  min-height: 250px;
  background-image: url("../assets/chair.jpg");
  background-size: contain;
  padding: 20px;
  text-align: center;
  border-radius: 50%;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  color: rgb(0, 0, 0);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hall-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.booking-cards {
  margin-top: 30px;
  display: flex;
  gap: 20px;
  width: 100%;
}

.booking-card {
  background: linear-gradient(135deg, #6e7bff, #4e66f2);
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  color: white;
}

.booking-card:hover {
  transform: translateY(-5px);
}

.booking-info {
  margin-bottom: 20px;
}

.booking-info h3 {
  font-size: 1.25rem;
}

.booking-info p {
  color: rgba(255, 255, 255, 0.8);
}

.actions {
  display: flex;
  justify-content: flex-end;
  gap: 5px;
}

.delete-btn {
  background-color: #e74c3c;
  color: white;
  padding: 8px 15px;
  cursor: pointer;
  border: none;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.delete-btn:hover {
  background-color: #c0392b;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 500px;
  width: 100%;
}

.form-container {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-size: 1rem;
  margin-bottom: 5px;
}

.form-control {
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.form-actions {
  display: flex;
  justify-content: space-between;
}

.submit-btn {
  background-color: #2ecc71;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.submit-btn:hover {
  background-color: #27ae60;
}

.cancel-btn {
  background-color: #e74c3c;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.cancel-btn:hover {
  background-color: #c0392b;
}

.occupied {
  color: red;
  font-size: large;
  font-weight: 600;
}

.add-hall-btn {
  margin: 20px 0;
  background-color: #28a745;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.add-hall-btn:hover {
  background-color: #218838;
}
</style>
