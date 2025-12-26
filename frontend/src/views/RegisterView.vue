<!--El estil d'aquesta pagina es la mateixa que la pagina de loggin, ja comentat anteriorment, 
hi ha dues columnes on a la part de l'esquerra es on hi ha informacio falsa per aparentar una interficie
mes formal sobre una missio de mart i a la part de la dreta esta el formulari per crear el enregistrament-->
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
    <div
      style="
        width: 100%;
        max-width: 960px;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 18px;
      "
    >
      <!-- Columna esquerra -->
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
              Mission briefing
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
                  Objective
                </p>
                <p style="margin:4px 0 0; color:#b42318; font-weight:800; font-size: 18px;">
                  DEPLOY ROVER
                </p>
                <p style="margin:6px 0 0; color:#7a271a; font-size: 12px;">
                  Create your account and start exploring the Martian surface.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Columna dreta -->
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
        <!-- FORMULARI DE ENREGISTRAMENT -->
        <div style="width: 100%; max-width: 320px;">
          <h2 style="margin:0 0 6px; color:#101828; font-size: 20px;">
            Crear compte
          </h2>
          <p style="margin:0 0 16px; color:#667085; font-size: 13px;">
            Registra’t per iniciar la teva missió.
          </p>

          <form @submit.prevent="onSubmit" style="display:grid; gap: 10px;">
            <div style="display:grid; gap: 6px;">
              <label style="color:#344054; font-size: 13px;">Nom</label>
              <input
                v-model="name"
                type="text"
                required
                placeholder="Nom complet"
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
                autocomplete="new-password"
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

            <div style="display:grid; gap: 6px;">
              <label style="color:#344054; font-size: 13px;">
                Confirmar password
              </label>
              <input
                v-model="passwordConfirmation"
                type="password"
                required
                autocomplete="new-password"
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
            <!--Boto per enviar el fomrulari-->
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
              {{ auth.loading ? "Creant..." : "Registrar-me" }}
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
            <p style="margin: 0; color:#667085; font-size: 13px;">
              Ja tens compte?
            </p>

            <router-link
              to="/login"
              style="
                color: #101828;
                text-decoration: none;
                padding: 8px 10px;
                border-radius: 10px;
                border: 1px solid #d0d5dd;
                background: #ffffff;
              "
            >
              Login
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

const name = ref("");
const email = ref("");
const password = ref("");
const passwordConfirmation = ref("");
/**
 * En aquesta funció enviem el formulari al backend i ens retornara directament tant la informació del usuari
 * com el seu token i després ens portara directament a la pagina Dashboard
 */
async function onSubmit() {
  const ok = await auth.register(
    name.value,
    email.value,
    password.value,
    passwordConfirmation.value
  );

  if (ok) router.push("/");
}
</script>
