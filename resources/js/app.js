import './bootstrap';

import Alpine from 'alpinejs';
import {
    compressImageFile,
    loadImage,
    readFileAsDataUrl,
} from './image-upload';

window.Alpine = Alpine;
window.ImageUploadUtils = {
    compressImageFile,
    loadImage,
    readFileAsDataUrl,
};

Alpine.start();
