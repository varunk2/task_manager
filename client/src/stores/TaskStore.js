import { defineStore } from "pinia"
import { taskDate } from "../utilities/Date"

export const useTaskStore = defineStore("task", {
    persist: true,
    state: () => ({
        tasks: []
    }),
    actions: {
        createTask(task) {
            this.tasks.push({
                id: Math.floor(1000 + Math.random() * 9000),
                title: task,
                status: 0,
                created_at: taskDate()
            })
        },
        toggleTaskStatus(task_id, status) {
            let task = this.tasks.find(t => t.id === task_id)
            task.status = status
        },
        deleteTask(task) {
            this.tasks = this.tasks.filter((t) => t.id !== task.id)
        }
    }
})