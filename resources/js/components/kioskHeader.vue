<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const logo = new URL('../../../public/images/sss/sss-logo.png', import.meta.url).href

const currentTime = ref('')
const currentDate = ref('')

const temperature = ref('--')
const locationName = ref('Detecting location...')
const weatherIcon = ref('sun')

let interval = null

const updateDateTime = () => {
  const now = new Date()

  currentTime.value = now.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  })

  currentDate.value = now.toLocaleDateString('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  })
}

const getWeatherIcon = (code) => {
  if ([0, 1].includes(code)) {
    return 'sun'
  }

  if ([2, 3].includes(code)) {
    return 'cloud'
  }

  if ([45, 48].includes(code)) {
    return 'fog'
  }

  if ([51, 53, 55, 56, 57, 61, 63, 65, 66, 67, 80, 81, 82].includes(code)) {
    return 'rain'
  }

  if ([71, 73, 75, 77, 85, 86].includes(code)) {
    return 'snow'
  }

  if ([95, 96, 99].includes(code)) {
    return 'storm'
  }

  return 'sun'
}

const getLocationName = async (latitude, longitude) => {
  try {
    const response = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`
    )

    if (!response.ok) {
      throw new Error('Unable to get location')
    }

    const data = await response.json()

    const address = data.address || {}

    locationName.value =
      address.city ||
      address.town ||
      address.municipality ||
      address.county ||
      address.state ||
      'Unknown Location'
  } catch (error) {
    console.error('Location lookup failed:', error)
    locationName.value = 'Unknown Location'
  }
}

const getWeather = async (latitude, longitude) => {
  try {
    const response = await fetch(
      `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,weather_code&temperature_unit=celsius`
    )

    if (!response.ok) {
      throw new Error('Unable to get weather')
    }

    const data = await response.json()

    temperature.value = Math.round(data.current.temperature_2m)
    weatherIcon.value = getWeatherIcon(data.current.weather_code)
  } catch (error) {
    console.error('Weather lookup failed:', error)
    temperature.value = '--'
  }
}

const getCurrentLocation = () => {
  if (!navigator.geolocation) {
    locationName.value = 'Location unavailable'
    return
  }

  navigator.geolocation.getCurrentPosition(
    async (position) => {
      const { latitude, longitude } = position.coords

      await Promise.all([
        getWeather(latitude, longitude),
        getLocationName(latitude, longitude),
      ])
    },
    (error) => {
      console.error('Geolocation error:', error)

      locationName.value = 'Location unavailable'
    },
    {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 300000,
    }
  )
}

onMounted(() => {
  updateDateTime()

  interval = setInterval(updateDateTime, 1000)

  getCurrentLocation()
})

onUnmounted(() => {
  clearInterval(interval)
})
</script>

<template>
  <header class="flex items-center justify-between p-6  font-[Inter]">

    <!-- Left: Logo + System Name -->
    <div class="flex items-center gap-3">

      <img
        :src="logo"
        alt="Social Security System Logo"
        class="mb-1 h-[36px] w-[48px]"
      />

      <div class="leading-[18px]">
        <h1 class="text-sm font-semibold">
          Social Security System
        </h1>

        <p class="text-xs text-paragraph  ">
          Queue Management System - Branch kiosk
        </p>
      </div>

    </div>

    <!-- Right: Weather + Date/Time -->
    <div class="flex items-start gap-7">

      <!-- Weather -->
      <div class="flex items-center gap-2">

        <!-- Sunny -->
        <svg
          v-if="weatherIcon === 'sun'"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="h-[24px] w-[24px] text-[#FABC00]"
        >
          <circle cx="12" cy="12" r="4" />
          <path d="M12 2v2" />
          <path d="M12 20v2" />
          <path d="m4.93 4.93 1.41 1.41" />
          <path d="m17.66 17.66 1.41 1.41" />
          <path d="M2 12h2" />
          <path d="M20 12h2" />
          <path d="m6.34 17.66-1.41 1.41" />
          <path d="m19.07 4.93-1.41 1.41" />
        </svg>

        <!-- Cloud -->
        <svg
          v-else-if="weatherIcon === 'cloud'"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="h-[24px] w-[24px] text-black"
        >
          <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z" />
        </svg>

        <!-- Rain -->
        <svg
          v-else-if="weatherIcon === 'rain'"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="h-[24px] w-[24px] text-blue-500"
        >
          <path d="M16 13a4 4 0 0 0-8 0" />
          <path d="M12 17v1" />
          <path d="M8 17v1" />
          <path d="M16 17v1" />
        </svg>

        <p class="text-[12px] font-semibold">
          {{ temperature }}°C • {{ locationName }}
        </p>

      </div>

      <!-- Time + Date -->
      <div class="text-end leading-5">

        <h1 class="text-[20px] font-bold">
          {{ currentTime }}
        </h1>

        <p class="text-[12px] text-[#606060]">
          {{ currentDate }}
        </p>

      </div>

    </div>

  </header>
</template>