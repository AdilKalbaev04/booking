<script setup>
import { ref, onMounted } from "vue";
import axios from "../axios";

const halls = ref([]);

const fetchHalls = async () => {
  try {
    const response = await axios.get("/halls");
    halls.value = response.data;
    console.log(response.data);
  } catch (error) {
    console.error("Error fetching halls:", error);
  }
};

onMounted(() => {
  fetchHalls();
});

const deleteHall = async (id) => {
  if (confirm("Are you sure you want to delete this hall?")) {
    try {
      await axios.delete(`/halls/${id}`);
      halls.value = halls.value.filter((hall) => hall.id !== id);
    } catch (error) {
      console.error("Error deleting hall:", error);
    }
  }
};
</script>

<template>
  <div class="hall-list">
    <h1>Halls</h1>
    <div class="hall-cards">
      <div v-for="hall in halls" :key="hall.id" class="hall-card">
        <h2>{{ hall.name }}</h2>
        <p>{{ hall.description }}</p>
        <button @click="() => deleteHall(hall.id)">Delete</button>
        <button @click="$router.push(`/halls/edit/${hall.id}`)">Edit</button>
      </div>
    </div>
    <button @click="$router.push('/halls/create')" class="add-btn">
      Add New Hall
    </button>
  </div>
</template>

<style scoped>
.hall-list {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 30px;
}

.hall-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  width: 100%;
}

.hall-card {
  background: linear-gradient(135deg, #6e7bff, #4e66f2);
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  color: white;
}

.hall-card:hover {
  transform: translateY(-5px);
}
.hall-card h2 {
  margin-bottom: 10px;
  font-size: 1.5em;
}

.hall-card p {
  margin-bottom: 15px;
  color: #ffffff;
}

button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  margin-right: 10px;
}

button:hover {
  background-color: #0056b3;
}

.add-btn {
  margin-top: 30px;
  background-color: #28a745;
}

.add-btn:hover {
  background-color: #218838;
}
</style>
