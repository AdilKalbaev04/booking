import { createRouter, createWebHistory } from "vue-router";
import BookingList from "@/views/BookingList.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import HallsView from "../views/HallsView.vue";
import UserList from "@/views/UserList.vue";

const routes = [
  { path: "/", component: LoginView, name: "Login" },
  { path: "/register", component: RegisterView, name: "Register" },
  { path: "/bookings", component: BookingList, name: "BookingList" },
  { path: "/halls", name: "Halls", component: HallsView },
  { path: "/users", name: "UserList", component: UserList },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
