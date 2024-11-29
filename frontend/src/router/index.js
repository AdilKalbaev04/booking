import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import BookingList from "@/views/BookingList.vue";
import BookingCreate from "@/views/BookingCreate.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import HallsView from "../views/HallsView.vue";
import HallCreateEdit from "../views/HallCreateEdit.vue";

const routes = [
  { path: "/", component: LoginView, name: "Login" },
  { path: "/register", component: RegisterView, name: "Register" },
  // { path: "/Home", component: HomeView, name: "Home" },
  { path: "/create", component: BookingCreate, name: "BookingCreate" },
  { path: "/bookings", component: BookingList, name: "BookingList" },
  { path: "/halls", name: "Halls", component: HallsView },
  { path: "/halls/create", name: "HallCreate", component: HallCreateEdit },
  { path: "/halls/edit/:id", name: "HallEdit", component: HallCreateEdit },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
