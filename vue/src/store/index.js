import {createStore} from 'vuex';
import axiosClient from '../axios';


const store = createStore({
    state: {
        user: {
            data:{},
            token:sessionStorage.getItem("TOKEN"),
        },
        currentSurvey: {
          data: {},
          loading: false,
        },
        surveys: {
          loading: false,
          data: [],
        },
        questionTypes: ['text', 'select', 'radio', 'checkbox', 'textarea'],
    },
    getters: {},
    actions: {
        register({commit}, user){
            return axiosClient.post('/register', user)
            .then(({data}) => {
                commit('setUser', data);
                return data;
            })
        },
        login({commit}, user){
            return axiosClient.post('/login', user)
            .then(({data}) => {
                commit('setUser', data);
                return data;
            })
        },
        logout({commit}){
            return axiosClient.post('/logout')
            .then(response => {
                commit('logout');
                return response;
            })
        },
        saveSurvey({commit}, survey){
          delete survey.image_url; //image_url use only in fronted, no need in the backend
          let response;
          // if survey has id => it means update survey, else create new survey
          if(survey.id){
            // update
            response = axiosClient.put(`/survey/${survey.id}`, survey).then((res)=>{
              commit('setCurrentSurvey', res.data);
              return res;
            });
          }else{
            // create new survey
            response = axiosClient.post("/survey", survey).then((res)=> {
              commit('setCurrentSurvey', res.data);
              return res;
            });
          }
          return response;
        },
        getSurvey({commit}, id){
          commit("setCurrentSurveyLoading", true);
          // make http request
          return axiosClient.get(`/survey/${id}`).then((res)=>{
            commit("setCurrentSurvey", res.data);
            commit("setCurrentSurveyLoading", false); //stop loading
            return res;
          }).catch((err)=>{
            commit("setCurrentSurveyLoading", false);
            throw err;
          });
        },
        deleteSurvey({}, id){
          return axiosClient.delete(`/survey/${id}`);
        },
        getSurveys({commit}){
          //loading the service
          commit("setSurveyLoading", true);
          //get http request
          return axiosClient.get('/survey/').then((res) => {
            // stop loading
            commit("setSurveyLoading", true);
            //set the data
            commit("setSurveys", res.data);
            return res;
          });
        },

    },
    mutations: {
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem("TOKEN");
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem("TOKEN", userData.token);
          },
        setCurrentSurveyLoading: (state, loading)=> {
          state.currentSurvey.loading = loading;
        },
        setCurrentSurvey: (state, survey) =>{
          state.currentSurvey.data = survey.data;
        },
        setSurveyLoading: (state, loading)=> {
          state.surveys.loading = loading;
        },
        setSurveys: (state, surveys) =>{
          state.surveys.data = surveys.data;
        }

    },
    modules: {}
})

export default store;
