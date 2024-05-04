import api from './api';

var softCache = false;

class BaImagesService {
    index() {
        if (!softCache) {
            softCache = api.get(`ba-cheetah/v1/user-images`);
        }
        return softCache;
    }

    download(data) {
        return api.post(`ba-cheetah/v1/user-images/download`, data);
    }
}

export default new BaImagesService();
