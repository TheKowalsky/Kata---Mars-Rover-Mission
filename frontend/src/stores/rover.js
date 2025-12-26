import { defineStore } from "pinia";
import { api } from "../api/client";

export const useRoverStore = defineStore("rover", {
  // Estat del rover
  state: () => ({
    x: 0,
    y: 0,
    direction: "N",                                         // Direcció del rover
    loading: false,
    error: null,
    lastCommands: "",                                       // Ultima comandan del rover
    status: null,                                           // Estats de la comanda "completed" | "aborted" | null
    obstacle: null,                                         // Obstacles sobre aquell rover { x, y } | null
    steps: [],                                              // Passos / steps per animar el rover
    end: null,
  }),

  actions: {
    /**
     * Funció utilitzada per carregar el rover
     */
    async fetchRover() {
      this.loading = true;                                  // Passem al estat de carregar
      this.error = null;

      try {
        const { data } = await api.get("/rover");           // Enviem la peticio GET al backend per retornar tota la informacio del rover
        this.x = data.x;                                  
        this.y = data.y;
        this.direction = data.direction;
      } catch (e) {
        this.error = e?.response?.data?.message || "No s'ha pogut carregar el rover";
      } finally {
        this.loading = false;
      }
    },

    /**
     * Funcio utilitzada per moure el rover a una posicio especifica
     * @param {*} commands Comanda a animar
     * @returns 
     */
    async sendCommands(commands) {
      this.loading = true;
      this.error = null;

      this.lastCommands = commands;
      this.status = null;
      this.obstacle = null;
      this.steps = [];
      this.end = null;

      try {
        const { data } = await api.post("/rover/commands", { commands });   // Obtenim la comanda del rover en qüestio
        /**
         * El rover ens retorna tant un vector dels pasos, com el estat, com els obstafles i el inici i final del rover
         */
        this.steps = data.steps || [];
        this.status = data.status || null;
        this.obstacle = data.obstacle || null;
        this.end = data.end || null;

        return true;
      } catch (e) {
        const msg =
          e?.response?.data?.message ||
          (e?.response?.data?.errors
            ? JSON.stringify(e.response.data.errors)
            : null) ||
          "Error al enviar la comanda";

        this.error = msg;
        return false;
      } finally {
        this.loading = false;
      }
    },

    // Apliquem els steps en variables locals
    applyStep(step) {
      if (!step) return;
      this.x = step.x;
      this.y = step.y;
      this.direction = step.direction;
    },

    // Ens assegurem del final
    applyEnd() {
      if (!this.end) return;
      this.x = this.end.x;
      this.y = this.end.y;
      this.direction = this.end.direction;
    },
  },
});
