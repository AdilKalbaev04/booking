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
        <button @click="() => openModal(true, hall)">Edit</button>
      </div>
    </div>
    <button @click="() => openModal(false)" class="add-btn">
      Add New Hall
    </button>

    <!-- Модальное окно -->
    <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <h1>{{ isEditMode ? "Edit Hall" : "Create Hall" }}</h1>
        <form @submit.prevent="saveHall" class="hall-form">
          <div>
            <label for="name">Name:</label>
            <input
              v-model="hall.name"
              id="name"
              type="text"
              required
              placeholder="Enter hall name"
            />
          </div>
          <div>
            <label for="description">Description:</label>
            <textarea
              v-model="hall.description"
              id="description"
              required
              placeholder="Describe the hall..."
            ></textarea>
          </div>
          <div class="modal-buttons">
            <button type="submit" class="submit-btn">
              {{ isEditMode ? "Update" : "Create" }}
            </button>
            <button type="button" class="cancel-btn" @click="closeModal">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Основной стиль для списка залов */
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
  color: white;
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
  background-color: #ff5733;
}
.delete-btn:hover {
  background-color: #ff5733;
}


/* Модальное окно */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.hall-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.hall-form input,
.hall-form textarea {
  padding: 12px;
  font-size: 16px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.modal-buttons {
  display: flex;
  justify-content: space-between;
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

.cancel-btn {
  background-color: #ccc;
  color: black;
  padding: 10px 20px;
  cursor: pointer;
  border: none;
  font-size: 16px;
}

.cancel-btn:hover {
  background-color: #aaa;
}
</style>
