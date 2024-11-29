<template>
  <div class="booking-list">
    <h1>Список бронирований</h1>
    <div class="booking-cards">
      <div v-for="booking in bookings" :key="booking.id" class="booking-card">
        <div class="booking-info">
          <h3>Зал: {{ booking.hall_id }}</h3>
          <p>Время начала: {{ formatDate(booking.start_time) }}</p>
          <p>Время окончания: {{ formatDate(booking.end_time) }}</p>
          <p>Причина: {{ booking.reason }}</p>
        </div>
        <div class="actions">
          <button @click="deleteBooking(booking.id)" class="delete-btn">Удалить</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      bookings: [],
    };
  },
  mounted() {
    this.fetchBookings();
  },
  methods: {
    async fetchBookings() {
      try {
        const response = await axios.get("/bookings");
        this.bookings = response.data;
      } catch (error) {
        console.error("Ошибка при загрузке бронирований:", error);
      }
    },
    async deleteBooking(id) {
      if (confirm("Вы уверены, что хотите удалить это бронирование?")) {
        try {
          await axios.delete(`/bookings/${id}`);
          this.fetchBookings();
        } catch (error) {
          console.error("Ошибка при удалении бронирования:", error);
        }
      }
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
  padding: 20px;
}

h1 {
  font-size: 2rem;
  margin-bottom: 20px;
}

.booking-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  width: 100%;
}

.booking-card {
  background-color: #f4f4f4;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.booking-info {
  margin-bottom: 20px;
}

.booking-info h3 {
  font-size: 1.25rem;
  margin-bottom: 10px;
}

.booking-info p {
  margin: 5px 0;
}

.actions {
  display: flex;
  justify-content: flex-end;
}

.delete-btn {
  background-color: #e74c3c;
  color: white;
  padding: 8px 15px;
  cursor: pointer;
  border: none;
  border-radius: 5px;
}

.delete-btn:hover {
  background-color: #c0392b;
}
</style>
