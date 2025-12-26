<template>
  <div style="max-width: 1200px; margin: 24px auto; padding: 16px;">
    <!-- HEADER -->
    <div
      style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
      "
    >
      <h1 style="margin: 0;">Mars Rover Mission</h1>

      <button
        @click="onLogout"
        style="
          padding: 10px 14px;
          border: 1px solid #ccc;
          border-radius: 8px;
          background: #f7f7f7;
          cursor: pointer;
        "
      >
        Logout
      </button>
    </div>

    <!-- LAYOUT -->
    <div
      style="
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 18px;
        align-items: start;
      "
    >
      <!-- LEFT PANEL -->
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <!-- Card: Rover -->
        <div
          style="
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
          "
        >
          <h2 style="margin: 0 0 10px 0; font-size: 18px;">Rover</h2>

          <p v-if="rover.loading" style="margin: 0;">Carregant rover...</p>
          <p v-else-if="rover.error" style="margin: 0; color: #b00020;">
            {{ rover.error }}
          </p>

          <div v-else>
            <p style="margin: 6px 0;"><b>Posició:</b> ({{ rover.x }}, {{ rover.y }})</p>
            <p style="margin: 6px 0;"><b>Direcció:</b> {{ rover.direction }}</p>

            <div style="margin-top: 10px;">
              <p v-if="rover.status" style="margin: 6px 0;">
                <b>Status:</b>
                <span
                  :style="{
                    padding: '2px 8px',
                    borderRadius: '999px',
                    border: '1px solid #ddd',
                    background: rover.status === 'aborted' ? '#ffe7e7' : '#e9f7ef',
                    color: rover.status === 'aborted' ? '#b00020' : '#1e6b3a',
                  }"
                >
                  {{ rover.status }}
                </span>
              </p>

              <p v-if="rover.obstacle" style="margin: 6px 0;">
                <b>Obstacle detectat:</b> ({{ rover.obstacle.x }}, {{ rover.obstacle.y }})
              </p>

              <p v-if="rover.steps?.length" style="margin: 6px 0;">
                <b>Steps:</b> {{ rover.steps.length }}
              </p>
            </div>
          </div>
        </div>

        <!-- Card: Commands -->
        <div
          style="
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
          "
        >
          <h2 style="margin: 0 0 10px 0; font-size: 18px;">Comandes</h2>

          <form @submit.prevent="onSendCommands" style="display: flex; gap: 8px;">
            <input
              v-model="commands"
              :disabled="isAnimating"
              placeholder="Ex: FFRRFFL"
              autocomplete="off"
              style="
                flex: 1;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 10px;
                outline: none;
              "
            />

            <button
              type="submit"
              :disabled="rover.loading || isAnimating"
              style="
                padding: 10px 14px;
                border: 1px solid #ccc;
                border-radius: 10px;
                background: #111;
                color: #fff;
                cursor: pointer;
              "
            >
              {{ isAnimating ? "Movent..." : (rover.loading ? "Enviant..." : "Enviar") }}
            </button>
          </form>

          <p v-if="commandsError" style="margin: 10px 0 0; color: #b00020;">
            {{ commandsError }}
          </p>

          <p style="margin: 10px 0 0; color: #666; font-size: 13px;">
            Només F, L i R. (Sense espais)
          </p>
        </div>

        <!-- Card: Obstacles -->
        <div
          style="
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
          "
        >
          <h2 style="margin: 0 0 10px 0; font-size: 18px;">Obstacles</h2>

          <!-- Crear obstacle -->
          <form @submit.prevent="onCreateObstacle" style="display:flex; gap:8px; align-items:end;">
            <div style="flex:1;">
              <label style="font-size: 13px; color:#555;">X (0-199)</label>
              <input
                v-model.number="obsX"
                type="number"
                min="0"
                max="199"
                :disabled="obstacles.loading || isAnimating"
                style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px; outline:none;"
              />
            </div>

            <div style="flex:1;">
              <label style="font-size: 13px; color:#555;">Y (0-199)</label>
              <input
                v-model.number="obsY"
                type="number"
                min="0"
                max="199"
                :disabled="obstacles.loading || isAnimating"
                style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px; outline:none;"
              />
            </div>

            <button
              type="submit"
              :disabled="obstacles.loading || isAnimating"
              style="
                padding: 10px 14px;
                border: 1px solid #ccc;
                border-radius: 10px;
                background: #0b57d0;
                color: #fff;
                cursor: pointer;
              "
            >
              {{ obstacles.loading ? "..." : "Add" }}
            </button>
          </form>

          <p v-if="obstacles.error" style="margin: 10px 0 0; color: #b00020;">
            {{ obstacles.error }}
          </p>

          <!-- Llista obstacles -->
          <div style="margin-top: 12px;">
            <p v-if="obstacles.loading && !obstacles.items.length" style="margin: 0;">Carregant...</p>

            <p v-else-if="!obstacles.items.length" style="margin: 0; color:#666;">
              Encara no tens obstacles.
            </p>

            <ul v-else style="margin: 0; padding-left: 18px;">
              <li
                v-for="o in obstacles.items"
                :key="o.id"
                style="margin: 6px 0; display:flex; justify-content:space-between; gap:10px;"
              >
                <span>( {{ o.x }}, {{ o.y }} )</span>

                <button
                  @click="onDeleteObstacle(o.id)"
                  :disabled="obstacles.loading || isAnimating"
                  style="
                    border: 1px solid #ccc;
                    background: #fff;
                    border-radius: 8px;
                    padding: 4px 10px;
                    cursor: pointer;
                  "
                >
                  Delete
                </button>
              </li>
            </ul>
          </div>

          <p style="margin: 10px 0 0; color: #666; font-size: 13px;">
            Els obstacles es veuen en vermell al mapa.
          </p>
        </div>
      </div>

      <!-- RIGHT PANEL (MAP) -->
      <div
        style="
          border: 1px solid #ddd;
          border-radius: 12px;
          padding: 12px;
          background: #fff;
          display: flex;
          justify-content: center;
        "
      >
        <MarsMap
          :worldSize="200"
          :width="600"
          :height="600"
          :cellSize="3"
          :paddingPx="10"
          :rover="{ x: rover.x, y: rover.y, direction: rover.direction }"
          :obstacles="obstacles.items"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { useRoverStore } from "../stores/rover";
import { useObstaclesStore } from "../stores/obstacles";
import MarsMap from "../components/MarsMap.vue";

const auth = useAuthStore();
const rover = useRoverStore();
const obstacles = useObstaclesStore();
const router = useRouter();

const commands = ref("");
const commandsError = ref("");

const isAnimating = ref(false);

const obsX = ref(0);
const obsY = ref(0);

function validateCommands(str) {
  const cleaned = (str || "").trim().toUpperCase();
  if (!cleaned) return { ok: false, value: "", error: "Introdueix comandes." };
  if (!/^[FLR]+$/.test(cleaned)) {
    return { ok: false, value: "", error: "Només es permeten F, L i R (sense espais)." };
  }
  return { ok: true, value: cleaned, error: "" };
}

function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function animateSteps(steps) {
  isAnimating.value = true;

  for (const step of steps) {
    rover.applyStep(step);
    await sleep(120);
  }

  rover.applyEnd();
  isAnimating.value = false;
}

async function onSendCommands() {
  commandsError.value = "";

  const v = validateCommands(commands.value);
  if (!v.ok) {
    commandsError.value = v.error;
    return;
  }

  if (isAnimating.value) return;

  const ok = await rover.sendCommands(v.value);
  if (!ok) return;

  if (rover.steps?.length) {
    await animateSteps(rover.steps);
  } else {
    rover.applyEnd();
  }
}

function clampToWorld(n) {
  const v = Number(n);
  if (Number.isNaN(v)) return 0;
  return Math.max(0, Math.min(199, v));
}

async function onCreateObstacle() {
  const x = clampToWorld(obsX.value);
  const y = clampToWorld(obsY.value);

  // opcional: impedir obstacle sobre el rover
  if (x === rover.x && y === rover.y) {
    obstacles.error = "No pots posar un obstacle a la posició del rover.";
    return;
  }

  const ok = await obstacles.createObstacle(x, y);
  if (ok) {
    obsX.value = x;
    obsY.value = y;
  }
}

async function onDeleteObstacle(id) {
  await obstacles.deleteObstacle(id);
}

onMounted(async () => {
  if (auth.isAuthenticated && !auth.user) {
    await auth.fetchMe();
  }

  await rover.fetchRover();
  await obstacles.fetchObstacles();
});

async function onLogout() {
  await auth.logout();
  router.push("/login");
}
</script>
