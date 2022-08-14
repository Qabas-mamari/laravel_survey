<template>
  <PageComponentVue>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
        <router-link to="{name: 'SurveyCreate'}"
          class="bg-emerald-500 px-2 py-2 rounded-md text-white hover:bg-emerald-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -mt-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Add new Survey
        </router-link>
      </div>
    </template>
    <div>
      <pre>{{survey}}</pre>
    </div>
  </PageComponentVue>
</template>

<script setup>
import store from '../store';
import { computed } from 'vue';
import PageComponentVue from '../components/PageComponent.vue';

const survey = computed(() => store.state.surveys);
</script>