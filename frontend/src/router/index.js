import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/LoginView.vue";
import RegisterView from "../views/RegisterView.vue";
import DashboardView from "../views/DashboardView.vue";

/**
 * Rutes creades per generar aquest frontend
 */
const router = createRouter({
  history: createWebHistory(),
  routes: [                                                                 // Rutes establertes
    { path: "/login", component: LoginView },                               // Pagina de login
    { path: "/register", component: RegisterView },                         // Pagina per enregistrar-se
    { path: "/", component: DashboardView, meta: { requiresAuth: true } },  // La Dashboard, aquesta es utilitzada per veure i utilitzar el rover, es important que necessitis estar loguejat
  ],
});

router.beforeEach((to) => {
  const token = localStorage.getItem("token");                                // Obtenim el token
  if (to.meta.requiresAuth && !token) return "/login";                        // En cas de no tenir token la pagina es porta direactament a la pagina de login
  if ((to.path === "/login" || to.path === "/register") && token) return "/"; // En cas de que vulguessim accedir a login o register i tinguessim el token, directament accedira a la dashboard
});

export default router;
