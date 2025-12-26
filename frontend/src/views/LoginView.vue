<template>
  <div
    style="
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      background: #f6f7fb;
    "
  >
    <!--Layout princial de dues columnes-->
    <div
      style="
        width: 100%;
        max-width: 960px;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 18px;
      "
    >
      <!-- COLUMNA DE LA ESQUERRA
        l'obteciu es aparentar una pagina que esigui relacionada a una missio de mart,
        aixi que s'ha generat un panell on es poden veure informacio falsa sobre misions de mart-->
      <div
        style="
          border: 1px solid #e6e8f0;
          border-radius: 18px;
          padding: 22px;
          background: #ffffff;
          box-shadow: 0 10px 26px rgba(16, 24, 40, 0.06);
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          min-height: 420px;
        "
      >
        <div>
          <div style="display:flex; align-items:center; gap:10px; margin-bottom: 10px;">
            <div
              style="
                width: 44px;
                height: 44px;
                border-radius: 14px;
                background: #ffe7e0;
                border: 1px solid #ffd3c7;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                color: #b42318;
              "
            >
              MR
            </div>

            <div>
              <h1 style="margin:0; font-size: 22px; color:#101828;">
                Mars Rover Mission
              </h1>
            </div>
          </div>

          <div
            style="
              margin-top: 14px;
              border: 1px solid #f0f2f7;
              border-radius: 16px;
              padding: 14px;
              background: linear-gradient(180deg, #fff6f3, #ffffff);
            "
          >
            <p style="margin:0 0 8px; color:#101828; font-weight:700;">
              Today’s terrain: Mars
            </p>

            <div
              style="
                border-radius: 14px;
                border: 1px solid #ffd6cc;
                background: #fff0eb;
                padding: 12px;
                position: relative;
                overflow: hidden;
              "
            >
              <div
                style="
                  position: absolute;
                  inset: 0;
                  opacity: 0.55;
                  background-image: radial-gradient(
                    rgba(180, 35, 24, 0.12) 1px,
                    transparent 1px
                  );
                  background-size: 14px 14px;
                "
              ></div>

              <div style="position: relative;">
                <p style="margin:0; color:#7a271a; font-weight:700; font-size: 13px;">
                  Mission status
                </p>
                <p style="margin:4px 0 0; color:#b42318; font-weight:800; font-size: 18px;">
                  READY
                </p>
                <p style="margin:6px 0 0; color:#7a271a; font-size: 12px;">
                  Connect to your rover and start sending commands (F, L, R).
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- COLUMNA DE LA DRETA -->
      <div
        style="
          border: 1px solid #e6e8f0;
          border-radius: 18px;
          padding: 22px;
          background: #ffffff;
          box-shadow: 0 10px 26px rgba(16, 24, 40, 0.06);
          min-height: 420px;
          display: flex;
          align-items: center;
          justify-content: center;
        "
      >
        <!-- DIV que ens serveix com a fomrulari per passar tant el usuari com la contrasenya per 
         inicia sessio -->
        <div style="width: 100%; max-width: 320px;">
          <h2 style="margin:0 0 6px; color:#101828; font-size: 20px;">
            Inicia sessió
          </h2>
          <p style="margin:0 0 16px; color:#667085; font-size: 13px;">
            Entra amb el teu compte per accedir al dashboard.
          </p>

          <form @submit.prevent="onSubmit" style="display:grid; gap: 10px;">
            <div style="display:grid; gap: 6px;">
              <label style="color:#344054; font-size: 13px;">Email</label>
              <input
                v-model="email"
                type="email"
                required
                autocomplete="email"
                placeholder="you@example.com"
                style="
                  width: 100%;
                  padding: 10px 12px;
                  border-radius: 12px;
                  border: 1px solid #d0d5dd;
                  background: #ffffff;
                  color: #101828;
                  outline: none;
                "
              />
            </div>

            <div style="display:grid; gap: 6px;">
              <label style="color:#344054; font-size: 13px;">Password</label>
              <input
                v-model="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                style="
                  width: 100%;
                  padding: 10px 12px;
                  border-radius: 12px;
                  border: 1px solid #d0d5dd;
                  background: #ffffff;
                  color: #101828;
                  outline: none;
                "
              />
            </div>

            <button
              type="submit"
              :disabled="auth.loading"
              style="
                margin-top: 6px;
                width: 100%;
                padding: 10px 12px;
                border-radius: 12px;
                border: 1px solid #b42318;
                background: #b42318;
                color: #ffffff;
                cursor: pointer;
                font-weight: 700;
              "
            >
              {{ auth.loading ? "Entrant..." : "Login" }}
            </button>

            <p
              v-if="auth.error"
              style="margin: 6px 0 0; color:#b42318; font-size: 13px;"
            >
              {{ auth.error }}
            </p>
          </form>

          <div
            style="
              margin-top: 14px;
              padding-top: 14px;
              border-top: 1px solid #eef2f6;
              display: flex;
              justify-content: space-between;
              align-items: center;
            "
          >
            <!--Al final del inici de sessio, tenim l'opiò en cas de no tenir compte creat
            Aquest boto ens envia a la pagina de enregistrament directament-->
            <p style="margin: 0; color:#667085; font-size: 13px;">
              No tens compte?
            </p>

            <router-link
              to="/register"
              style="
                color: #101828;
                text-decoration: none;
                padding: 8px 10px;
                border-radius: 10px;
                border: 1px solid #d0d5dd;
                background: #ffffff;
              "
            >
              Crear compte
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();

const email = ref("");
const password = ref("");
/**
 * Sol tenim una funcio el qual es enviar el formulari del inici de sessio
 * al backend per a que ho comprovi i envií tant el token com un error en cas de no existir
 */
async function onSubmit() {
  const ok = await auth.login(email.value, password.value);
  if (ok) router.push("/");
}
</script>
