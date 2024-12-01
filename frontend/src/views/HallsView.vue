<script setup>
import { ref, onMounted } from "vue";
import axios from "../axios";
import Swal from "sweetalert2";
import { useToast } from "vue-toastification";

const toast = useToast();

const halls = ref([]);
const isModalOpen = ref(false);
const isEditMode = ref(false);
const hall = ref({
  name: "",
  description: "",
});
const selectedHallId = ref(null);

// Получение списка залов
const fetchHalls = async () => {
  try {
    const response = await axios.get("/halls");
    halls.value = response.data;
  } catch (error) {
    console.error("Error fetching halls:", error);
    toast.error("Failed to load halls.");
  }
};

const deleteHall = async (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "Cancel",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        await axios.delete(`/halls/${id}`);
        halls.value = halls.value.filter((hall) => hall.id !== id);
        toast.success("Hall deleted successfully.");
      } catch (error) {
        console.error("Error deleting hall:", error);
        toast.error("Failed to delete hall.");
      }
    }
  });
};

// Открытие модального окна для создания/редактирования
const openModal = (edit = false, hallData = null) => {
  isEditMode.value = edit;
  if (edit && hallData) {
    hall.value = { ...hallData };
    selectedHallId.value = hallData.id;
  } else {
    hall.value = { name: "", description: "" };
    selectedHallId.value = null;
  }
  isModalOpen.value = true;
};

// Закрытие модального окна
const closeModal = () => {
  isModalOpen.value = false;
};

// Сохранение или обновление зала
const saveHall = async () => {
  try {
    if (isEditMode.value) {
      await axios.put(`/halls/${selectedHallId.value}`, hall.value);
      toast.success("Hall updated successfully.");
    } else {
      await axios.post("/halls", hall.value);
      toast.success("Hall created successfully.");
    }
    closeModal();
    fetchHalls();
  } catch (error) {
    console.error("Error saving hall:", error);
    toast.error("Failed to save hall.");
  }
};

onMounted(() => {
  fetchHalls();
});
</script>

<template>
  <div class="hall-list">
    <h1>Halls</h1>
    <div class="hall-cards">
      <div v-for="hall in halls" :key="hall.id" class="hall-card">
        <h2>{{ hall.name }}</h2>
        <p>{{ hall.description }}</p>
        <button class="delete-btn" @click="() => deleteHall(hall.id)">
          Delete
        </button>
        <button class="edit-btn" @click="() => openModal(true, hall)">Edit</button>
      </div>
    </div>
    <button @click="() => openModal(false)" class="add-btn">
      Add New Hall
    </button>

    <!-- Модальное окно -->
    <div v-if="isModalOpen" class="modal-overlay">
      <div class="modal-content">
        <h2>{{ isEditMode ? 'Edit Hall' : 'Add New Hall' }}</h2>
        <form @submit.prevent="saveHall">
          <label for="name">Name:</label>
          <input
            type="text"
            id="name"
            v-model="hall.name"
            required
          />
          <label for="description">Description:</label>
          <textarea
            id="description"
            v-model="hall.description"
            required
          ></textarea>
          <button type="submit">{{ isEditMode ? 'Save Changes' : 'Create Hall' }}</button>
        </form>
        <button class="delete-btn" @click="closeModal">Close</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.hall-list {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 30px;
  padding: 45px;
}

.hall-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 40px;
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

.delete-btn {
  margin-top: 30px;
  background-color: #ff5733 !important;
}
.delete-btn:hover {
  background-color: #ff5733;
}

/* Стиль для модального окна */
.modal-overlay {
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
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  width: 400px;
  text-align: center;
}

.modal-content input,
.modal-content textarea {
  width: 100%;
  margin-bottom: 15px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.modal-content button {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  margin-top: 10px;
}

.modal-content button:hover {
  background-color: #218838;
}
</style>
