import { defineStore } from "pinia"
import { taskDate } from "../utilities/Date"
import { API_URL } from "../config"

export const useTaskStore = defineStore("task", {
    persist: true,
    state: () => ({
        isAdd: true,
        isEdit: false,
        taskId: '',
        taskTitle: '',
        tasks: [],
        isLoading: false
    }),
    actions: {
        async fetchTasks() {
            try {
                this.isLoading = true
                const response = await fetch(API_URL + 'tasks')
                const result = await response.json()
                this.tasks = result.data
            } catch (error) {
                console.error(error)
            } finally {
                this.isLoading = false
            }
        },
        createTask(task) {
            this.tasks.push({
                id: Math.floor(1000 + Math.random() * 9000),
                title: task,
                status: 0,
                created_at: taskDate()
            })
        },
        toggleOperation () {
            this.isAdd = !this.isAdd
            this.isEdit = !this.isEdit
        },
        setTaskForUpdate(task) {
            this.taskId = task.id
            this.taskTitle = task.title
        },
        async toggleTaskStatus(task_id, status) {
            let task = this.tasks.find(t => t.id === task_id)
            task.status = status
            fetch(
                API_URL + 'tasks/update/status',
                {
                    method: "post",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: task_id,
                        status: status
                    })
                }
            )
        },
        deleteTask(task) {
            this.tasks = this.tasks.filter((t) => t.id !== task.id)
        }
    }
})