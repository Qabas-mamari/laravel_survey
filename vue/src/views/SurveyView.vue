<template>
  <PageComponentVue>
    <template v-slot:header>
      <div class="flex justify-between items-end">
        <h1 class="text-3xl font-bold text-gray-900">
          <!-- check model id, if model exist write the model title, if not create survey -->
          {{ route.params.id ? model.title : 'Create a Survey' }}
        </h1>
        <button v-if="route.params.id" type="button" class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600" @click="deleteSurvey()">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 -mt-1 inline-block" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
              clip-rule="evenodd" />
          </svg>
            Delete Survey
          </button>
      </div>
      <!-- <pre>
                {{model}}
            </pre> -->
      <!-- <pre>{{surveyLoading}}</pre> -->
      <div v-if="surveyLoading" class="flex justify-center">Loading...</div>
      <form v-else @submit.prevent="saveSurvey">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
          <!-- survey fields -->
          <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
            <!-- image -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Image</label>
              <div class="mt-1 flex items-center">
                <img v-if="model.image_url" :src="model.image_url" :alt="model.title" class="w-64 h-48 object-cover">
                <span v-else
                  class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-[80%] w-[80%] text-gray-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </span>
                <button type="button"
                  class="relative overflow-hidden ml-5 py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <input type="file" @change="onImageChoose"
                    class="absolute left-0 top-0 bottom-0 right-0 opacity-0 cursor-pointer">
                  Change
                </button>
              </div>
            </div>

            <!-- Title -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
              <input type="text" name="title" id="title" v-model="model.title" autocomplete="survey_title"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Description -->
            <div>
              <label for="about" class="block text-sm font-medium text-gray-700">Description</label>
              <div class="mt-1">
                <textarea name="description" id="description" rows="3" v-model="model.description"
                  autocomplete="survey_description"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"
                  placeholder="Describe your survey"></textarea>
              </div>
            </div>

            <!-- Expire Date -->
            <div>
              <label for="expire_date" class="block text-sm font-medium text-gray-700">Expire Date</label>
              <input type="date" name="expire_date" id="expire_date" v-model="model.expire_date"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md shadow-sm">
            </div>

            <!-- Status -->
            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input type="checkbox" name="status" id="status" v-model="model.status"
                  class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 rounded">
              </div>
              <div class="ml-3 text-sm">
                <label for="status" class="font-medium text-gray-700">Active</label>
              </div>
            </div>
            <!-- end survey fields -->
          </div>

          <!-- add questions -->
          <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
            <h3 class="text-2xl font-semibold flex items-center justify-between">
              Questions
              <!-- add new question -->
              <button type="button" @click="addQuestion()"
                class="flex items-center rounded-sm border bg-gray-600 text-white px-4 py-1 text-sm hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Questions
              </button>
            </h3>
            <!-- check if model has question -->
            <div v-if="!model.questions.length" class="text-center text-gray-600">
              You don't have any question created
            </div>
            <!-- if question exist, looping -->
            <div v-for="(question, index) in model.questions" :key="question.id">
              <QuestionEditorVue :question="question" :index="index" @change="questionChange" @addQuestion="addQuestion"
                @deleteQuestion="deleteQuestion" />
            </div>
          </div>


          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
          </div>
        </div>
      </form>
    </template>
  </PageComponentVue>
</template>

<script setup>
import PageComponentVue from '../components/PageComponent.vue';
import QuestionEditorVue from '../components/editor/QuestionEditor.vue';
import { ref, watch, computed } from 'vue';
import store from '../store';
import { useRoute, useRouter } from 'vue-router';
import { v4 as uuidv4 } from 'uuid';

const route = useRoute();
const router = useRouter()
const surveyLoading = computed(() => store.state.currentSurvey.loading);

//  create empty survey model
let model = ref({
  title: "",
  status: false,
  image_url: null,
  description: null,
  expire_date: null,
  questions: []
});

//watch to current survey data change and when this happens we update local model
watch(
  () => store.state.currentSurvey.data, //when ever this change we call the following
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: newVal.status !== "draft",

    };
  }
);

if (route.params.id) {
  // update the survey model
  store.dispatch('getSurvey', route.params.id);
}

function onImageChoose(ev) {
  const file = ev.target.files[0];
  const reader = new FileReader();

  reader.onload = () => {
    // send it to backend
    model.value.image = reader.result;

    //send it to frontend => use it here
    model.value.image_url = reader.result;
  }
  reader.readAsDataURL(file);
}

// index is passing from addQuestion() in QuestionEditor.vue
function addQuestion(index) {
  // create new question
  const newQuestion = {
    id: uuidv4,
    type: 'text',
    question: "",
    description: null,
    data: {},
  }
  /** splice()
   * Removes elements from an array and, if necessary, inserts new elements in their place, returning the deleted elements.
      @param start — index
      @param deleteCount — The number of elements to remove. 0
      @param newQuestion - insert new element
      @returns — An array containing the elements that were deleted or inserted.
   */
  model.value.questions.splice(index, 0, newQuestion);
}

// Delete question, @param question is passing from deleteQuestion() in QuestionEditor.vue
function deleteQuestion(question) {
  model.value.questions = model.value.questions.filter(
    (q) => q !== question
  );
}

// edit question, @param question is passing from dataChange() in QuestionEditor.vue
function questionChange(question) {
  model.value.questions = model.value.questions.map((q) => {
    if (q.id === question.id) {
      return JSON.parse(JSON.stringify(question));
    }
    return q
  });
}

// create or update survey
function saveSurvey() {
  store.dispatch('saveSurvey', model.value).then(({ data }) => {
    router.push({
      name: "SurveyView",
      params: { id: data.data.id },
    });
  });
}

// delete survey
function deleteSurvey(){
  if(confirm(`Are you sure you want to delete this survey? Operation can't be undone!!`)){
    store.dispatch('deleteSurvey', model.value.id).then(()=> {
      router.push({
        name: "Surveys",
      });
    });
  }
}
</script>
