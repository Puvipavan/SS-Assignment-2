import axios from "axios";

// Base URL
let base_url = builderall_media_localize.root;

const api = axios.create({
    baseURL: base_url,
    headers: {
        "X-WP-Nonce": builderall_media_localize.nonce,
        "Content-Type": "application/json",
    }
});

export default api;

export const localize = () => builderall_media_localize;
