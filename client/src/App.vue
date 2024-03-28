<script setup>
import TaskForm from './components/TaskForm.vue'
import TasksList from './components/TasksList.vue'
import { useTaskStore } from './stores/TaskStore'

useTaskStore().$reset()
</script>

<template>
  <div class="container mt-5">
    <div class="row">
      <!-- Form -->
      <div class="col-md-3">
        <TaskForm />
      </div>
      <!-- Table -->
      <div class="col-md-9">
        <TasksList />
      </div>
    </div>
  </div>
</template>
