<template>
    <div class="card form-container">
        <div class="card-header">
            <h5 class="card-title">{{ isAdd ? "Create" : "Edit" }} Task</h5>
        </div>
        <div class="card-body">

            <Alert
                v-if="alert.flag"
                :variant="alert.variant"
                :message="alert.message"
            />
            
            <div class="mb-3">
                <input
                    id="name"
                    type="text"
                    v-model="taskTitle"
                    class="form-control"
                    placeholder="Enter your task"
                >
                <p
                    v-if="taskTitleError"
                    class="text-danger"
                >
                    <small>{{ taskTitleError }}</small>
                </p>
            </div>

            <span>
                <small>Attachment</small>
            </span>

            <form
                class="form-control dropzone"
                id="my-great-dropzone"
                enctype="multipart/form-data"
            >
                <div class="dz-message" data-dz-message>
                    <span>Drop PDF file here or click to upload.</span>
                </div>
            </form>

            <button
                type="submit"
                class="form-control btn btn-primary mt-3"
                @click="addTask"
                :disabled="submitting"
                v-if="isAdd"
            >
                Submit
            </button>

            <button
                type="submit"
                class="form-control btn btn-primary mt-3"
                @click="updateTask"
                :disabled="submitting"
                v-if="isEdit"
            >
                Update
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import Dropzone from 'dropzone'
import 'dropzone/dist/dropzone.css'
import { useTaskStore } from "../stores/TaskStore"
import Alert from "./Alert.vue"    
import { storeToRefs } from "pinia"
import { API_URL } from "../config"

const taskStore = useTaskStore()
const { taskId, taskTitle, isAdd, isEdit } = storeToRefs(taskStore)
const taskTitleError = ref('')
const dropzoneInstance = ref(null)
const fileData = ref(null)
const submitting = ref(false)
const alert = ref({
    flag: false,
    variant: "",
    message: ""
})

onMounted(() => {
    Dropzone.autoDiscover = false
    dropzoneInstance.value = new Dropzone("#my-great-dropzone", {
        maxFilesize: 2, // MB
        acceptedFiles: '.pdf',
        url: '#',
        init: function () {
            this.on("error", (file, errorMessage) => {
                if (file.accepted && !file.size) {
                    alert("File is too large. Max size: 2MB.")
                    this.removeFile(file)
                }
                else if (!file.accepted) {
                    alert("Invalid file format. Please upload a PDF file.")
                    this.removeFile(file)
                }
            })
            
            this.on("success", (file, response) => {
                alert("File uploaded successfully!")
            })
        },
        sending: function (file, xhr, formData) {
            formData.append('title', taskTitle.value)
            formData.append('filename', file.name)
            formData.append('file', file)
            fileData.value = formData
        }
    })
})

const addTask = async () => {

    if (taskTitle.value === "") {
        taskTitleError.value = "This field is required."
        clearErrors()
        return
    }

    if (fileData.value === null) {
        let formData = new FormData()
        formData.append('title', taskTitle.value)
        fileData.value = formData
    }

    try {
        submitting.value = true
        const response = await fetch(
            "http://localhost:8000/api/tasks",
            {
                method: 'POST',
                body: fileData.value
            }
        )
        
        const result = await response.json()
        if (result.data.status) {
            taskStore.fetchTasks()
            showAlert("success", "Task created successfully!!!")
            clearFields()
        }
        console.info("success", result)
    } catch (error) {
        showAlert("danger", "Oops!! Looks like something went wrong.")
        console.error("Error:", error);
    } finally {
        submitting.value = false
    }
}

const updateTask = async () => {
    if (taskTitle.value === "") {
        taskTitleError.value = "This field is required."
        clearErrors()
        return
    }

    
    if (fileData.value === null) {        
        let formData = new FormData()
        formData.append('title', taskTitle.value)
        fileData.value = formData
    }

    try {
        submitting.value = true
        const response = await fetch(
            API_URL + `tasks/${taskId.value}`,
            {
                method: 'PATCH',
                body: fileData.value
            }
        )
        
        const result = await response.json()
        if (result.data.status) {
            taskStore.fetchTasks()
            showAlert("success", "Task updated successfully!!!")
            clearFields()
        }
        console.info("success", result)
    } catch (error) {
        showAlert("danger", "Oops!! Looks like something went wrong.")
        console.error("Error:", error);
    } finally {
        submitting.value = false
    }
}

const clearFields = () => {
    taskTitle.value = ""
    dropzoneInstance.value.removeAllFiles()
}

const showAlert = (variant, message)  => {
    alert.value.flag    = true
    alert.value.variant = variant
    alert.value.message = message
    closeAlert()
}

const closeAlert = ()  => {
    setTimeout(() => {
        alert.value.flag = false
    }, 3000)
}

const clearErrors = () => {
    setTimeout(() => {
        taskTitleError.value = ""
    }, 4000)
}

</script>