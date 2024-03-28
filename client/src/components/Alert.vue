<template>
    <div
        :class="`alert alert-${variant}`"
        role="alert"
    >
        {{ message }}
    </div>
</template>

<script setup>
const props = defineProps({
    variant: String,
    message: String
})
</script>

<style scoped>
.alert{
    height: 40px;
    padding: 0.3rem 0.3rem 0.3rem 0.5rem;
}
</style>