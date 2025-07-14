<template>
  <div class="resumo-card" :style="{ background: bg }">
    <div class="icon-box" :style="{ background: iconBg }">
      <i :class="icon"></i>
    </div>
    <div class="resumo-info">
      <div class="resumo-value">{{ value }}</div>
      <div class="resumo-label">{{ label }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  icon: string
  value: string | number
  label: string
  bg?: string
  iconBg?: string
}>()
</script>

<style scoped>
.resumo-card {
  display: flex;
  align-items: center;
  gap: 18px;
  border-radius: 18px;
  box-shadow: 0 2px 8px #0001;
  padding: 1.5rem 2rem;
  min-width: 220px;
  min-height: 100px;
  background: #fff;
}
.icon-box {
  width: 54px;
  height: 54px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: #fff;
  background: #3b82f6;
}
.resumo-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.resumo-value {
  font-size: 2.1rem;
  font-weight: 700;
  color: #222;
}
.resumo-label {
  font-size: 1rem;
  color: #6b7280;
  font-weight: 500;
}
</style> 