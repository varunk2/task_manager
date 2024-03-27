<template>
    <div class="card form-container">
        <div class="card-header">
            <h5 class="card-title">Task</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input
                    id="name"
                    type="text"
                    v-model="taskTitle"
                    class="form-control"
                    placeholder="Enter your task"
                >
            </div>

            <!-- <span><small>Attachment</small></span> -->
            <!-- <form action="/target" class="form-control dropzone" id="my-great-dropzone"></form> -->
            <button
                type="submit"
                class="form-control btn btn-primary mt-3"
                @click="addTask"
            >
                Submit
            </button>
        </div>
    </div>
</template>

<script setup>
    // Dropzone.options.myGreatDropzone = { // camelized version of the `id`
    //     paramName: "file", // The name that will be used to transfer the file
    //     maxFilesize: 2, // MB
    //     accept: function(file, done) {
    //         if (file.name == "justinbieber.jpg") {
    //             done("Naha, you don't.");
    //         }
    //         else { done(); }
    //     }
    // };
    import { ref } from "vue"
    import { useTaskStore } from "../stores/TaskStore"

    const taskStore = useTaskStore()
    const taskTitle = ref('')

    const addTask = () => {
        taskStore.createTask(taskTitle.value)
    }

</script>