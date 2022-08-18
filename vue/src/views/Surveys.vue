<template>
  <PageComponentVue>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
        <router-link :to="{ name: 'SurveyCreate' }"
          class="bg-emerald-500 px-2 py-2 rounded-md text-white hover:bg-emerald-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -mt-1 inline-block" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Add new Survey
        </router-link>
      </div>
    </template>
    <!-- <pre>{{survey}}</pre> -->
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-1 md:grid-cols-3">
      <div v-for="survey in surveys" :key="survey.id"
        class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[450px]">
        <img :src="survey.image" alt="" class="w-48 h-48 object-cover">
        <h4 class="mt-4 text-lg font-bold">{{ survey.title }}</h4>
        <div v-html="survey.description" class="overflow-hidden flex-1"></div>

        <!-- buttons -->
        <div class="flex justify-between items-center mt-3">
          <router-link 
          :to="{ name: 'SurveyView', params: { id: survey.id } }"
            class="flex py-2 px-4 border-border-transparent text-sm bg-indigo-600 rounded-md text-white hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
          </router-link>
          <button v-if="survey.id" type="button" @click="deleteSurvey(survey)" 
                  class="h-8 w-8 flex items-center rounded-full text-sm text-red-500 border border-transparent focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </PageComponentVue>
</template>

<script setup>
import store from '../store';
import { computed } from 'vue';
import PageComponentVue from '../components/PageComponent.vue';

const surveys = computed(() => store.state.surveys);

function deleteSurvey(survey){
  if(confirm('Are sure ?')){
    // delete survey
  }
}
</script>