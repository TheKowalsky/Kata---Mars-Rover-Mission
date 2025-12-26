import { defineStore } from "pinia";
import { api } from "../api/client";

export const useObstaclesStore = defineStore("obstacles", {
  // Dades a obtenir dels obstacles
  state: () => ({
    items: [],                                                          // [{id, x, y}]
    loading: false,
    error: null,
  }),

  actions: {
    /*Depenent de les dades obtingudes haurem d'utilitar un format o un altre*/
    normalizeList(data) {
      // Accepta formats típics:
      // 1) [ ... ]
      // 2) { data: [ ... ] }
      // 3) { obstacles: [ ... ] }
      if (Array.isArray(data)) return data;
      if (Array.isArray(data?.data)) return data.data;
      if (Array.isArray(data?.obstacles)) return data.obstacles;
      return [];
    },
    /*Obtenir les dades dels obstacles*/
    async fetchObstacles() {
      this.loading = true;                                                      // Afegim el estat de carregant
      this.error = null;

      try {
        const { data } = await api.get("/obstacles");                           // Enviem un GET del obstacle al backend, aquest ens retornara tots els obstacles
        this.items = this.normalizeList(data);
      } catch (e) {
        this.error = e?.response?.data?.message || "Error al carregar els obstacles";   // En cas de tenir un error amb l'obtencio dels obstacles, es mostrara per pantalla
      } finally {
        this.loading = false;                                                   // Deixem d'estar amb el estat del loading
      }
    },
    /*Funció utilitzada per crear els obstacles*/
    async createObstacle(x, y) {
      this.loading = true;                                                      // Afegim el estat de carregar
      this.error = null;

      try {
        await api.post("/obstacles", { x, y });                                 // Enviem l'informació del obstacles nou amb un POST a la base de dades

        await this.fetchObstacles();                                            // Tornem a acualitzar els obstacles a mostrar al frontend ja que minim haurem d'afegir el nou obstacle creat

        return true;
      } catch (e) {
        const msg =
          e?.response?.data?.message ||
          (e?.response?.data?.errors
            ? JSON.stringify(e.response.data.errors)
            : null) ||
          "Error al crear el obstacle";

        this.error = msg;
        return false;
      } finally {
        this.loading = false;
      }
    },
    /**
     * Funció que ens serveix per eliminar un obstacle en concret
     * @param {*} id ID del obstacle a eliminar
     * @returns 
     */
    async deleteObstacle(id) {
      this.loading = true;                                      // Afegim el estat de carregar
      this.error = null;

      try {
        await api.delete(`/obstacles/${id}`);                   // Enviem la peticio d'eliminar un obstacle sobre el id del obstacle

        // ✅ clau: sempre recarreguem des del servidor
        await this.fetchObstacles();

        return true;
      } catch (e) {
        this.error = e?.response?.data?.message || "No s'ha pogut eliminar el obstacle";
        return false;
      } finally {
        this.loading = false;
      }
    },
  },
});
