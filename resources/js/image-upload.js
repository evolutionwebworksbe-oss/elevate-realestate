export function readFileAsDataUrl(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = event => resolve(event.target.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

export function loadImage(source) {
    return new Promise((resolve, reject) => {
        const image = new Image();
        image.onload = () => resolve(image);
        image.onerror = reject;
        image.src = source;
    });
}

export async function compressImageFile(file, options = {}) {
    const maxWidth = options.maxWidth || 1920;
    const maxHeight = options.maxHeight || 1080;
    const quality = options.quality || 0.82;

    if (!file.type.startsWith('image/')) {
        return file;
    }

    const source = await readFileAsDataUrl(file);
    const image = await loadImage(source);
    const canvas = document.createElement('canvas');

    let width = image.width;
    let height = image.height;
    const ratio = Math.min(maxWidth / width, maxHeight / height, 1);

    width = Math.round(width * ratio);
    height = Math.round(height * ratio);

    canvas.width = width;
    canvas.height = height;

    const context = canvas.getContext('2d');
    context.drawImage(image, 0, 0, width, height);

    const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));

    if (!blob || blob.size >= file.size) {
        return file;
    }

    const compressedName = file.name.replace(/\.[^.]+$/, '') + '.jpg';
    return new File([blob], compressedName, {
        type: 'image/jpeg',
        lastModified: Date.now(),
    });
}
