<script setup>
import { ref, onMounted } from "vue";
import axios from "../axios";
import { useRoute, useRouter } from "vue-router";

const router = useRouter();
const route = useRoute();
const isEditMode = !!route.params.id;

const hall = ref({
  name: "",
  description: "",
});

const fetchHall = async (id) => {
  try {
    const response = await axios.get(`/halls/${id}`);
    hall.value = response.data;
  } catch (error) {
    console.error("Error fetching hall:", error);
  }
};

onMounted(() => {
  if (isEditMode) {
    fetchHall(route.params.id);
  }
});

const saveHall = async () => {
  try {
    if (isEditMode) {
      await axios.put(`/halls/${route.params.id}`, hall.value);
      alert("Hall updated successfully.");
    } else {
      await axios.post("/halls", hall.value);
      alert("Hall created successfully.");
    }
    router.push("/halls");
  } catch (error) {
    console.error("Error saving hall:", error);
  }
};
</script>

<template>
  <div class="hall-form-container">
    <h1>{{ isEditMode ? "Edit Hall" : "Create Hall" }}</h1>
    <form @submit.prevent="saveHall" class="hall-form">
      <div>
        <label for="name">Name:</label>
        <input v-model="hall.name" id="name" type="text" required placeholder="Enter hall name" />
      </div>
      <div>
        <label for="description">Description:</label>
        <textarea v-model="hall.description" id="description" required placeholder="Describe the hall..."></textarea>
      </div>
      <button type="submit" class="submit-btn">{{ isEditMode ? "Update" : "Create" }}</button>
    </form>
  </div>
</template>

<style scoped>
.hall-form-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 600px;
  margin: auto;
}

.hall-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
  width: 100%;
}

.hall-form input, .hall-form textarea {
  padding: 12px;
  font-size: 16px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.hall-form textarea {
  resize: vertical;
  min-height: 150px;
}

.submit-btn {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  cursor: pointer;
  border: none;
  font-size: 16px;
}

.submit-btn:hover {
  background-color: #0056b3;
}
</style>
