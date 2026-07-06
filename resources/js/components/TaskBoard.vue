<template>
  <div style="padding:20px;font-family:Arial;">

    <h1>Task Board 🚀</h1>

    <!-- Notifications -->
    <div style="background:#f8f9fa;padding:10px;border-radius:8px;border:1px solid #ddd;margin-bottom:20px;">
      <h3>🔔 Notifications</h3>

      <div v-if="notifications.length===0">
        No notifications yet.
      </div>

      <ul v-else>
        <li v-for="(note,index) in notifications" :key="index">
          {{ note }}
        </li>
      </ul>
    </div>

    <!-- ADD TASK -->
    <div style="margin-bottom:20px;">

      <input
        v-model="title"
        placeholder="Enter task title"
      />

      <select v-model="assigned_to">
        <option value="">Assign User</option>

        <option
          v-for="u in users"
          :key="u.id"
          :value="u.id"
        >
          {{ u.name }}
        </option>
      </select>

      <input
        type="date"
        v-model="due_date"
      />

      <button @click="addTask">
        Add Task
      </button>

    </div>

    <div style="display:flex;gap:20px;">

      <!-- TO DO -->
      <div style="flex:1;border:1px solid #ccc;padding:10px;">

        <h2>To Do</h2>

        <ul>

          <li
            v-for="t in todo"
            :key="t.id"
          >

            <strong>{{ t.title }}</strong>

            <div v-if="t.assignedUser">
              👤 {{ t.assignedUser.name }}
            </div>

            <div v-if="t.due_date">
              📅 {{ t.due_date }}
            </div>

            <button
              @click="moveTask(t.id,'in_progress')"
            >
              ➡
            </button>

            <button
              @click="deleteTask(t.id)"
            >
              ❌
            </button>

          </li>

        </ul>

      </div>

      <!-- IN PROGRESS -->

      <div style="flex:1;border:1px solid #ccc;padding:10px;">

        <h2>In Progress</h2>

        <ul>

          <li
            v-for="t in inProgress"
            :key="t.id"
          >

            <strong>{{ t.title }}</strong>

            <div v-if="t.assignedUser">
              👤 {{ t.assignedUser.name }}
            </div>

            <div v-if="t.due_date">
              📅 {{ t.due_date }}
            </div>

            <button
              @click="moveTask(t.id,'todo')"
            >
              ⬅
            </button>

            <button
              @click="moveTask(t.id,'done')"
            >
              ✔
            </button>

            <button
              @click="deleteTask(t.id)"
            >
              ❌
            </button>

          </li>

        </ul>

      </div>

      <!-- DONE -->

      <div style="flex:1;border:1px solid #ccc;padding:10px;">

        <h2>Done</h2>

        <ul>

          <li
            v-for="t in done"
            :key="t.id"
          >

            <strong>{{ t.title }}</strong>

            <div v-if="t.assignedUser">
              👤 {{ t.assignedUser.name }}
            </div>

            <div v-if="t.due_date">
              📅 {{ t.due_date }}
            </div>

            <button
              @click="moveTask(t.id,'in_progress')"
            >
              ↩
            </button>

            <button
              @click="deleteTask(t.id)"
            >
              ❌
            </button>

          </li>

        </ul>

      </div>

    </div>

    <h2 style="margin-top:40px;">
      📅 Calendar View
    </h2>

    <FullCalendar :options="calendarOptions"/>

  </div>
</template>
<script>
import axios from "axios";
import Swal from "sweetalert2";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";

export default {
  components: {
    FullCalendar
  },

  data() {
    return {
      title: "",
      assigned_to: "",
      due_date: "",

      notifications: [],

      users: [],
      tasks: [],
      todo: [],
      inProgress: [],
      done: [],

      calendarOptions: {
        plugins: [dayGridPlugin],
        initialView: "dayGridMonth",
        events: []
      }
    };
  },

  mounted() {
    this.loadTasks();
    this.loadUsers();
  },

  methods: {

    async loadUsers() {
      const res = await axios.get("http://127.0.0.1:8000/api/users");
      this.users = res.data;
    },

    async loadTasks() {
      const res = await axios.get("http://127.0.0.1:8000/api/tasks");

      this.tasks = res.data;

      this.todo = this.tasks.filter(t => t.status === "todo");
      this.inProgress = this.tasks.filter(t => t.status === "in_progress");
      this.done = this.tasks.filter(t => t.status === "done");

      this.calendarOptions.events = this.tasks
        .filter(t => t.due_date)
        .map(t => ({
          title: t.title,
          start: t.due_date
        }));
    },

    async addTask() {

      if (!this.title) return;

      await axios.post("http://127.0.0.1:8000/api/tasks", {
        title: this.title,
        status: "todo",
        assigned_to: this.assigned_to,
        due_date: this.due_date
      });

      this.notifications.unshift(
        "✅ Task '" + this.title + "' added successfully."
      );

      Swal.fire({
        icon: "success",
        title: "Task Added!",
        text: "Task created successfully.",
        timer: 1500,
        showConfirmButton: false
      });

      this.title = "";
      this.assigned_to = "";
      this.due_date = "";

      this.loadTasks();
    },

    async deleteTask(id) {

      const task = this.tasks.find(t => t.id === id);

      await axios.delete(
        `http://127.0.0.1:8000/api/tasks/${id}`
      );

      if (task) {
        this.notifications.unshift(
          "🗑 Task '" + task.title + "' deleted."
        );
      }

      this.loadTasks();
    },

    async moveTask(id, status) {

      const task = this.tasks.find(t => t.id === id);

      await axios.put(
        `http://127.0.0.1:8000/api/tasks/${id}`,
        {
          status: status
        }
      );

      if (task) {

        if (status === "todo") {
          this.notifications.unshift(
            "📌 '" + task.title + "' moved to To Do."
          );
        }

        if (status === "in_progress") {
          this.notifications.unshift(
            "🚀 '" + task.title + "' moved to In Progress."
          );
        }

        if (status === "done") {
          this.notifications.unshift(
            "🎉 '" + task.title + "' completed."
          );
        }

      }

      this.loadTasks();
    }

  }
};
</script>