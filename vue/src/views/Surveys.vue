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
      <SurveyListItem v-for="survey in surveys" :key="survey.id" :survey="survey" @delete="deleteSurvey(survey)"/>
    </div>
  </PageComponentVue>
</template>

<script setup>
import store from '../store';
import { computed } from 'vue';
import PageComponentVue from '../components/PageComponent.vue';
import SurveyListItem from '../components/SurveyListItem.vue';

const surveys = computed(() => store.state.surveys.data);

//get the data from database
store.dispatch('getSurveys');

function deleteSurvey(survey){
  if(confirm('Are sure ?')){
      store.dispatch('deleteSurvey', survey.id).then(()=>{
        store.dispatch('getSurveys'); //reload the item
      });
  }
}
</script>
