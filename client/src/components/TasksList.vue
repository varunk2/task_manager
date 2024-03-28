<template>
    <div class="card table-container">
        <div class="card-header">
            <h5 class="card-title">Tasks</h5>
        </div>
        <div class="card-body">
            <Alert
                v-if="alert.flag"
                :variant="alert.variant"
                :message="alert.message"
            />

            <Loader v-if="isLoading" />

            <table class="table table-sm" v-else>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Task</th>
                        <th scope="col">Status</th>
                        <th scope="col">Attachment</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody v-if="tasks.length">
                    <tr
                        v-for="task, index in tasks"
                        :key="task.id"
                    >
                        <td scope="row">
                            {{ index + 1 }}
                        </td>
                        <td>{{ task.title}}</td>
                        <td>
                            <TaskStatusSwitch :id="task.id" :status="task.status" />
                        </td>
                        <td>
                            <a
                                :href="attachmentURL(task.id)"
                                v-if="task.filename"
                            >
                                {{ task.filename }}
                            </a>

                            <span v-else> N/A </span>
                            
                        </td>
                        <td>{{ task.created_at }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-outline-primary"
                                title="Edit"
                                @click="updateTask(task)"
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
import Loader from "./Loader.vue"
import TaskStatusSwitch from './TaskStatusSwitch.vue'
import { useTaskStore } from "../stores/TaskStore"
import { ref, onBeforeMount } from 'vue'
import { API_URL } from '../config'

const taskStore = useTaskStore()
const { tasks, isLoading } = storeToRefs(taskStore)
const alert = ref({
    flag: false,
    variant: "",
    message: ""
})

onBeforeMount(() => { taskStore.fetchTasks() })

const attachmentURL = (id) => API_URL + `tasks/file/${id}`

const updateTask = (task) => {
    taskStore.toggleOperation()
    taskStore.setTaskForUpdate(task)
}

const deleteTask = async (task) => {
    if (confirm("Are you sure you want to delete this task?")) {
        try {
            isLoading.value = true
            let url = API_URL + `tasks/${task.id}`
            const response = await fetch (
                url, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json"
                    }
                }
            )
            
            const result = await response.json()

            if (result.data.status) {
                taskStore.deleteTask(task)
                showAlert("success")
            }
            console.info("success", result)
        } catch (error) {
            showAlert("danger")
            console.error("Error:", error);
        } finally {
            isLoading.value = false
        }
    }
}

const showAlert = (variant)  => {
    alert.value.flag    = true
    alert.value.variant = variant
    alert.value.message = variant === "success" ? "Task created successfully!!!" : "Oops!! Looks like something went wrong."
    closeAlert()
}

const closeAlert = ()  => {
    setTimeout(() => {
        alert.value.flag = false
    }, 3000)
}
</script>