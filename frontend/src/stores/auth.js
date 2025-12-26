import { defineStore } from "pinia";
import { api } from "../api/client";

export const useAuthStore = defineStore("auth", {
    // Estrucutura que utilitzarem
    state: () => ({
        user: null,
        token: localStorage.getItem("token"),
        loading: false,
        error: null,
    }),
    // Comprobem que el usuari tingui token, aixi podem observar si el usuari en qüestio esta loguejat
    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        // En primer lloc truquem al backend en /me per obtenir totes les dades del usuari en qüestio en cas de esta loguejat
        async fetchMe() {
        try {
            const { data } = await api.get("/me");
            this.user = data;
        } catch {
            this.logoutLocal();
        }
        },
        // En cas de que el login sigui correcte mostrem el loading per entrar a la pagina
        async login(email, password) {
        this.loading = true;
        this.error = null;
        // Enviem una peticio loggin
        try {
            const { data } = await api.post("/login", { email, password });
            this.token = data.token;                                            // Emmagatzemem el token
            localStorage.setItem("token", this.token);                          
            await this.fetchMe();
            return true;
        } catch (e) {
            this.error = e?.response?.data?.message || "Login error";           // En cas de intentar fer el login i surti qualsevol error, retornem missatge d'error
            return false;
        } finally {
            this.loading = false;
        }
        },
        // En cas de tenir que fer un enregistrament, en primer lloc si ha estat correcte tornarem a mostrar el loading
        async register(name, email, password, password_confirmation) {
        this.loading = true;
        this.error = null;
        try {
            const { data } = await api.post("/register", {                      // Enviem la peticio resgister amb les dades que ha passat el usuari
            name,
            email,
            password,
            password_confirmation,
            });
            this.token = data.token;                                            // Emmagatzemem el token
            localStorage.setItem("token", this.token);
            await this.fetchMe();
            return true;
        } catch (e) {
            this.error = e?.response?.data?.message || "Register error";        // Enc cas de tenir alguna mena d'error, ens mostrara le missatge del backend o "Register error"
            return false;
        } finally {
            this.loading = false;
        }
        },
        // En cas de voler fer un logout, directament aixo es fer un post al backend, i el backend fa tota la resta
        async logout() {
        try {
            await api.post("/logout");
        } catch {}
        this.logoutLocal();
        },
        // Ja per ultim en cas de fer el logout, hem de esborrar el token
        logoutLocal() {
        this.user = null;
        this.token = null;
        localStorage.removeItem("token");
        },
    },
});
