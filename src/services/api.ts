import axios from "axios";

const API = axios.create({
  baseURL: "http://localhost/e-tamu-api",
});

export default API;