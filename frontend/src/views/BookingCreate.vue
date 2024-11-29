<template>
  <div class="booking-create">
    <h1>Создать бронирование</h1>
    <form @submit.prevent="createBooking" class="form-container">
      <div class="form-group">
        <label for="hall_id">Выберите зал:</label>
        <select v-model="booking.hall_id" id="hall_id" required class="form-control">
          <option value="" disabled>Выберите зал</option>
          <option v-for="hall in halls" :key="hall.id" :value="hall.id">{{ hall.name }}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="start_time">Время начала:</label>
        <input type="datetime-local" v-model="booking.start_time" required class="form-control" />
      </div>
      <div class="form-group">
        <label for="end_time">Время окончания:</label>
        <input type="datetime-local" v-model="booking.end_time" required class="form-control" />
      </div>
      <div class="form-group">
        <label for="reason">Причина:</label>
        <input type="text" v-model="booking.reason" required class="form-control" placeholder="Введите причину" />
      </div>
      <button type="submit" class="submit-btn">Создать бронирование</button>
    </form>
  </div>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      booking: {
        hall_id: "",
        start_time: "",
        end_time: "",
        reason: "",
      },
      halls: [],
    };
  },
  mounted() {
    this.fetchHalls();
  },
  methods: {
    async fetchHalls() {
      try {
        const response = await axios.get("/halls");
        this.halls = response.data;
      } catch (error) {
        console.error("Ошибка при загрузке залов:", error);
      }
    },
    async createBooking() {
      try {
        await axios.post("/bookings", this.booking);
        alert("Бронирование создано!");
        this.$router.push("/bookings");
      } catch (error) {
        console.error("Ошибка при создании бронирования:", error);
      }
    },
  },
};
</script>

<style scoped>
.booking-create {
  display: flex;
  flex-direction: column;
  align-items: center;
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
  max-width: 600px;
  width: 100%;
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
