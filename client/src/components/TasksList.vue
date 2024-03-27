<template>
    <div class="card table-container">
        <div class="card-header">
            <h5 class="card-title">Tasks</h5>
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Task</th>
                        <th scope="col">Status</th>
                        <th scope="col">Attachment</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody v-if="tasks.length">
                    <tr
                        v-for="task in tasks"
                        :key="task.id"
                    >
                        <td scope="row">
                            {{ task.id }}
                        </td>
                        <td>{{ task.title}}</td>
                        <td>
                            <TaskStatusSwitch :id="task.id" :status="task.status" />
                        </td>
                        <td>Attachment</td>
                        <td>{{ task.created_at }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-outline-primary"
                                title="Edit"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button
                                class="btn btn-sm btn-outline-danger ms-2"
                                title="Delete"
                                @click="deleteTask(task)"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="6">
                            <p class="text-center">No records!</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import TaskStatusSwitch from './TaskStatusSwitch.vue'
import { useTaskStore } from "../stores/TaskStore"

const taskStore = useTaskStore()
const { tasks } = storeToRefs(taskStore)

const deleteTask = (task) => {
    if (confirm("Are you sure you want to delete this task?")){
        taskStore.deleteTask(task)
    }
}
</script>