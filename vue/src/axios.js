import axios from "axios";
import store from "./store";

// create axios variable 
const axiosClient = axios.create({
  baseURL: `http://127.0.0.1:8000/api`
})

/**
 * Request Header : HTTP request headers are used to provide additional information about the request.
 * For example, details about the requested information, the sender, and how the sender wishes to connect with the recipient.
 * We declare the config object, containing the headers object, which will be supplied as an argument when making requests.
 * 
 * Axios.create is a handy feature within Axios used to create a new instance with a custom configuration.
 * With Axios.create, we can generate a client for any API and reuse the configuration for any calls using the same client.
 */
// create authorization token, so when every access to api will return authorization token 

// Add a request interceptor
axiosClient.interceptors.request.use(config => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`
    return config;
  });

export default axiosClient;