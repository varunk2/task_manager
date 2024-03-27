<template>
    <span class="text-danger">Pending</span>
    <label class="switch ms-2 me-2">
        <input
            type="checkbox"
            v-model="status"
            true-value="1"
            false-value="0"
            @change="updateTaskStatus"
        >
        <span class="slider round"></span>
    </label>
    <span class="text-success">Completed</span>
</template>

<script setup>
import { ref } from 'vue'
import { useTaskStore } from '../stores/TaskStore'

const taskStore = useTaskStore()

const props = defineProps({
    id: String,
    status: Number
})

const status = ref(props.status)

const updateTaskStatus = () => {
    taskStore.toggleTaskStatus(props.id, parseInt(status.value))
}
</script>